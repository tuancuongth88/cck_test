<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Jobs\NotificationHRJob;
use App\Jobs\NotificationInterviewJob;
use App\Jobs\NotificationOfferJob;
use App\Jobs\NotificationResultJob;
use App\Models\Entry;
use App\Models\HR;
use App\Models\Interview;
use App\Models\Offer;
use App\Models\Result;
use App\Models\UserFavorite;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class ResultRepository extends BaseRepository implements ResultRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Instantiate model
     *
     * @param Result $model
     */

    public function model()
    {
        return Result::class;
    }

    public function list($attributes, $paginate = true)
    {
        $mainTbl = $this->getTableName();
        $items = $this->select('*', "$mainTbl.id", "$mainTbl.updated_at", "$mainTbl.status_selection")->with([
            'hr' => function ($q) {
                $q->select('id', 'hrs.user_id');
            },
            'work' => function ($q) {
                $q->select('id', 'works.user_id', 'title');
            },
        ]);
        $items->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id");
        $items->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at');
        $ids = $attributes['ids'] ?? '';
        if ($ids) {
            $items->whereIn("$mainTbl.id", $ids);
        }
        $items = $items->where("$mainTbl.display", 'on');
        $items = HRRepository::searchFilter($items, $attributes);
        $items = Common::getDataForPermisstion($items, auth()->user());
        if (isset($attributes['field']) && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'id') {
                $attributes->field = "$mainTbl.id";
            }
            if ($field == 'job_title') {
                $attributes->field = 'title';
            }
            if ($field == 'updating_date') {
                $attributes->field = "$mainTbl.updated_at";
            }
            if ($field == 'status_selection') {
                $items = Common::sortArrayText($items, "$mainTbl.status_selection", ENTRY_STATUS_TEXTS, $sortBy == 'asc');
            } else {
                $items = Common::sortBy($attributes, $items);
            }
        } else {
            $items = $items->orderBy("$mainTbl.id", 'desc');
        }
        if ($paginate) {
            $items = Common::pagination($attributes, $items);
        }
        $updateItems = [];
        $items->each(function ($item) use (&$updateItems) {
            $updateItems[] = [
                'id' => $item->id,
                'code' => $item->code,
                'updating_date' => $item->updated_at->format('Ymd'),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'job_id' => $item->work_id,
                'job_title' => $item->title,
                'status_selection' => $item->status_selection,
                'status_selection_name' => RESULT_STATUS_LIST[$item->status_selection],
                'time' => $item->time,
                'is_favorite_hrs' => UserFavorite::isFavorite($item->hr_id, FAVORITE_TYPE_HRS),
                'is_favorite_job' => UserFavorite::isFavorite($item->work_id, FAVORITE_TYPE_WORK),
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }
        return $items;
    }

    public function isOwner($ids = [])
    {
        if ($ids) {
            $items = $this->findWhereIn('id', $ids)
                ->whereIn('status_selection', [RESULT_STATUS_SELECTION_NOT_PASS, RESULT_STATUS_SELECTION_OFFER_DECLINE]);
            return $items->count() == count($ids);
        } else {
            return false;
        }
    }

    public function hide($ids)
    {
        try {
            $listData = $this->model->whereIn('id', $ids)->get();
            foreach ($listData as $item) {
                if (in_array($item->status_selection, [RESULT_STATUS_SELECTION_NOT_PASS, RESULT_STATUS_SELECTION_OFFER_DECLINE])) {
                    $item->display = 'off';
                    $item->save();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $ids['ids'] = [$id];
        $result = $this->list($ids, false)
            ->where('time', '>=', Carbon::now()->format('Ymd'))
            ->orWhere(function ($query) {
                $query->where(Result::STATUS_SELECTION, RESULT_STATUS_SELECTION_OFFER_CONFIRM)
                    ->whereNull('time');
            })
            ->first();
        if (!$result) {
            return ResponseService::responseJsonError(CODE_NO_ACCESS, trans('messages.data_does_not_exist'));
        }

        //confirm or decline
        if (in_array($request['status'], [RESULT_STATUS_SELECTION_OFFER_CONFIRM, RESULT_STATUS_SELECTION_OFFER_DECLINE])
            && !isset($request['hire_date'])) {
            return $this->updateDeclineOrConfirmResult($request, $id);
        }
        //update time expiration
        if ($request['status'] == RESULT_STATUS_SELECTION_OFFER
            && isset($request['time'])) {
            return $this->updateTimeResult($request, $id);
        }

        //update hire_date
        if ($request['status'] == RESULT_STATUS_SELECTION_OFFER_CONFIRM
            && isset($request['hire_date'])) {
            return $this->updateHireDate($request, $id);
        }
        return $this->detail($id);
    }

    private function updateDeclineOrConfirmResult($request, $id)
    {
        try {
            $request = $request->only(['status', 'note']);
            $status = @$request['status'];
            $rules = [
                'status' => 'required|in:3,4',
                'note' => ($status == RESULT_STATUS_SELECTION_OFFER_DECLINE) ? 'required' : '',
            ];
            $messages = [
                'status.required' => __('api.result.status.required'),
                'note.required' => __('api.result.note.required'),
            ];
            $validator = Validator::make(request()->all(), $rules, $messages);
            if (!$validator->fails()) {
                if (!$this->checkRole([\HR, HR_MANAGER, SUPER_ADMIN])) {
                    return ResponseService::responseJsonError(CODE_UNAUTHORIZED, __('messages.mes.permission'));
                }
                $request['status_selection'] = $request['status'];
                $request['time'] = null;
                if ($request['status'] == RESULT_STATUS_SELECTION_OFFER_DECLINE) {
                    $request['decline_date'] = Carbon::now();
                    Common::updateDisplayOGoingJob($id);
                }
                return $this->updateAndSendNotify($id, $request, $request['status'], @$request['note']);
            } else {
                return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, $validator->errors()->all());
            }
        } catch (\Exception $exception) {
            Log::error($exception);
            return ResponseService::responseJsonError($exception->getCode(), $exception->getMessage());
        }
    }

    private function updateTimeResult($request, $id)
    {
        $request = $request->only(['status', 'time']);
        if (!$this->checkRole([COMPANY, COMPANY_MANAGER, SUPER_ADMIN])) {
            return ResponseService::responseJsonError(CODE_UNAUTHORIZED, trans('messages.mes.permission'));
        }
        $rules = [
            'status' => 'required|in:1',
            'time' => 'required|date_format:Ymd|after:yesterday',
        ];
        $messages = [
            'status.required' => __('api.result.status.required'),
            'time.required' => __('api.result.time.required')
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->fails()) {
            $request['status_selection'] = $request['status'];
            return $this->updateAndSendNotify($id, $request, $request['status']);
        } else {
            return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, $validator->errors()->all());
        }
    }

    private function updateHireDate($request, $id)
    {
        $request = $request->only(['status', 'hire_date']);
        $rules = [
            'status' => 'required|in:3',
            'hire_date' => 'required|date_format:Y-m-d|after:yesterday',
        ];
        $messages = [
            'status.required' => __('api.result.status.required'),
            'hire_date.required' => __('api.result.hire_date.required'),
        ];
        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->fails()) {
            if (!$this->checkRole([COMPANY_MANAGER, SUPER_ADMIN])) {
                return ResponseService::responseJsonError(CODE_UNAUTHORIZED, trans('messages.mes.permission'));
            }
            Common::updateDisplayOGoingJob($id);
            return $this->updateAndSendNotify($id, $request, $request['status']);
        } else {
            return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, $validator->errors()->all());
        }
    }


    private function checkRole($listRole): bool
    {
        $user = auth()->user();
        if (in_array($user->type, $listRole)) {
            return true;
        }
        return false;
    }

    public function updateAndSendNotify($id, $attributes, $status, $note = '')
    {
        try {
            $result = parent::update($attributes, $id);
            if ($result && $attributes['status'] == RESULT_STATUS_SELECTION_OFFER_CONFIRM) {
                //set hr to official offer
                $hr = HR::query()->find($result->hr_id);
                $hr->status = HRS_STATUS_CONFIRM;
                $hr->save();
                $this->getAllJobNeedDecline($result->hr_id, $id);
            }
            $authLogin = Auth::user();
            NotificationResultJob::dispatch($authLogin, $result, $status, $note);
            return $this->detail($id);
        } catch (\Exception $exception) {
            Log::error($exception);
            return ResponseService::responseJsonError($exception->getCode(), $exception->getMessage());

        }
    }

    private function getAllJobNeedDecline($hrId, $resultId)
    {
        $entries = Entry::where(Entry::STATUS, ENTRY_STATUS_REQUESTING)
            ->where(Entry::HR_ID, $hrId)
            ->get();

        $offers = Offer::where(Offer::STATUS, OFFER_STATUS_REQUESTING)
            ->where(Offer::HR_ID, $hrId)
            ->get();

        $interviews = Interview::where('display', 'on')
            ->whereNotIn(Interview::STATUS_SELECTION, [INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL])
            ->where(Interview::HR_ID, $hrId)
            ->get();

        $result = Result::where(Result::STATUS_SELECTION, RESULT_STATUS_SELECTION_OFFER)
            ->where('id', '<>', $resultId)
            ->where(Result::HR_ID, $hrId)
            ->get();
        // Thực hiện xử lý kết quả và trả về kết quả cần thiết
        $data = [
            'entry' => $entries,
            'offer' => $offers,
            'interview' => $interviews,
            'result' => $result
        ];
        $this->declineJob($data);
    }

    private function declineJob($data)
    {
        try {
            $user = Auth::user();
            foreach ($data as $type => $values) {
                foreach ($values as $value) {
                    if ($value instanceof Entry) {
                        Common::updateDeclineJob($value, ENTRY_STATUS_DECLINE);
                        NotificationHRJob::dispatch($user, $value, ENTRY_STATUS_DECLINE, WHY_REJECTS[1]);
                    } elseif ($value instanceof Offer) {
                        Common::updateDeclineJob($value, OFFER_STATUS_DECLINE);
                        NotificationOfferJob::dispatch($user, $value, OFFER_STATUS_DECLINE, WHY_REJECTS[1]);
                    } elseif ($value instanceof Interview) {
                        Common::updateDeclineJob($value, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, Interview::STATUS_SELECTION, 'on');
                        $value->remarks = WHY_REJECTS[1];
                        NotificationInterviewJob::dispatch($value, null, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE);
                    } elseif ($value instanceof Result) {
                        Common::updateDeclineJob($value, RESULT_STATUS_SELECTION_OFFER_DECLINE, Interview::STATUS_SELECTION, 'on');
                        NotificationResultJob::dispatch($user, $value, RESULT_STATUS_SELECTION_OFFER_DECLINE, WHY_REJECTS[1]);
                    }
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }


    /**
     * @param $id
     * @return array|false
     */
    public function detail($id)
    {
        $item = $this->model->whereHas('hr', function ($queryHr) {
            $queryHr->whereNull('hrs.deleted_at');
        })->whereHas('work', function ($queryWork) {
            $queryWork->whereNull('works.deleted_at');
        })->with(['hr', 'work', 'interview', 'interview.infors', 'mainJobs'])->find($id);
        if (!$item) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
        $weekdays = $item->time > 0 ? Carbon::createFromFormat('Ymd', $item->time)->isoFormat('ddd') : null;
        $timeJP = $item->time > 0 ? Carbon::createFromFormat('Ymd', $item->time)->format('Y年m月d日') : null;
        $hireDate = $item->hire_date > 0 ? Carbon::parse($item->hire_date)->format('Y年m月d日') : null;
        $updateItems = [
            'id' => $item->id,
            'code' => $item->code,
            'hr_id' => $item->hr_id,
            'full_name' => @$item->hr->full_name,
            'full_name_ja' => @$item->hr->full_name_ja,
            'job_id' => $item->work_id,
            'job_title' => @$item->work->title,
            'status_selection' => $item->status_selection,
            'status_selection_text' => RESULT_STATUS_LIST[$item->status_selection],
            'time' => $item->time,
            'time_jp' => $timeJP,
            'weekDay' => $weekdays,
            'hire_date' => $hireDate,
            'decline_date' => $item->decline_date,
            'display' => $item->display,
            'note' => $item->note,
            'is_favorite_hrs' => UserFavorite::isFavorite($item->hr_id, FAVORITE_TYPE_HRS),
            'is_favorite_job' => UserFavorite::isFavorite($item->work_id, FAVORITE_TYPE_WORK),
            'interview_info' => Common::getInterviewInfo($item->interview_id),
            'create_at' => Carbon::parse($item->created_at)->format('Y年m月d日'),
            'update_at' =>  Carbon::parse($item->updated_at)->format('Y年m月d日'),
        ];
        return ResponseService::responseData(CODE_SUCCESS, 'success', '保存しました', $updateItems);
    }

}
