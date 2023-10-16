<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Jobs\NotificationHRJob;
use App\Jobs\RemindEntryJob;
use App\Models\Entry;
use App\Models\Interview;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\UserFavorite;
use App\Models\Work;
use App\Repositories\Contracts\EntryRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class EntryRepository extends BaseRepository implements EntryRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->scopeQuery(function ($query) {
            if (Auth::user()->type == HR) {
                $query = $query->where('hrs.user_id', Auth::user()->id);
            }
            if (Auth::user()->type == COMPANY) {
                $query = $query->where('works.user_id', Auth::user()->id);
            }
            return $query->whereIn('display', ['on', 'stop']);
        });
    }

    public function model()
    {
        return Entry::class;
    }

    public function findOne($id)
    {
        $auLogin = Auth::user();
        $updateItems = [];
        $fileHR = [];
        $mainTbl = (new Entry())->getTable();
        $item = $this->select("*", "$mainTbl.id", 'hrs.full_name', "$mainTbl.status", "$mainTbl.remarks")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at')
            ->where("$mainTbl.status", "!=", ENTRY_STATUS_CONFIRM)
            ->whereIn("$mainTbl.display", ['on', 'stop'])
            ->where("$mainTbl.id", $id)
            ->first();
        if (!$item) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $files = $item->files()->get()->toArray();
        foreach ($files as $value) {
            if ($value['item_type'] == MOTIVATION_FILE) {
                $fileHR['motivation'][] = [
                    'link_dowload' => url('api/download-fidelity/' . $value['id']),
                    'file_name' => $value['file_name'],
                ];
            }
            if ($value['item_type'] == RECOMMENDATION_FILE) {
                $fileHR['recommendation'][] = [
                    'link_dowload' => url('api/download-fidelity/' . $value['id']),
                    'file_name' => $value['file_name'],
                ];
            }
            if ($value['item_type'] == OTHER_FILE) {
                $fileHR['other_file'][] = [
                    'link_dowload' => url('api/download-fidelity/' . $value['id']),
                    'file_name' => $value['file_name'],
                ];
            }
        }
        $weekdays = $item->updated_at->isoFormat('ddd');
        $updateItems = [
            'id' => $item->id,
            'entry_code' => $item->code,
            'request_date' => date('Ymd', strtotime($item->request_date)),
            'hr_id' => $item->hr_id,
            'full_name' => $item->full_name,
            'full_name_ja' => $item->full_name_ja,
            'work_id' => $item->work_id,
            'work_title' => $item->title,
            'updating_date' => date('Ymd', strtotime($item->updated_at)),
            'status' => $item->status,
            'status_name' => ENTRY_STATUS_TEXTS[$item->status],
            'note' => $item->note,
            'remarks' => $item->remarks,
            'file' => $fileHR,
            'updating_date_ja' => $item->updated_at->format('Y年m月d日').' (' . $weekdays . ')'
        ];
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $updateItems);
    }

    public function getTableName()
    {
        return app($this->model())->getTable();
    }

    public function list($attributes, $paginate = true)
    {
        $mainTbl = $this->getTableName();
        $items = $this->select('*', "$mainTbl.id", "$mainTbl.updated_at", "$mainTbl.status", "$mainTbl.remarks")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->where("$mainTbl.status", "!=", ENTRY_STATUS_CONFIRM)
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at');
        $ids = $attributes['ids'] ?? '';
        if ($ids) {
            $items->whereIn("$mainTbl.id", $ids);
        }
        $items = HRRepository::searchFilter($items, $attributes);
        if ($attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'work_title') {
                $attributes->field = 'title';
            } elseif ($field == 'full_name') {
                $attributes->field = "hrs.full_name";
            } elseif ($field == 'entry_code') {
                $attributes->field = 'code';
            } else {
                $attributes->field = "$mainTbl.$field";
            }
            if ($field == 'status') {
                $items = Common::sortArrayText($items, "$mainTbl.status", ENTRY_STATUS_TEXTS, $sortBy == 'asc');
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
                'entry_code' => $item->code,
                'request_date' => date('Ymd', strtotime($item->request_date)),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'work_id' => $item->work_id,
                'work_title' => $item->title,
                'updating_date' => date('Ymd', strtotime($item->updated_at)),
                'status' => $item->status,
                'status_name' => ENTRY_STATUS_TEXTS[$item->status],
                'note' => $item->note,
                'remarks' => $item->remarks,
                'is_favorite_hrs' => UserFavorite::isFavorite($item->hr_id, FAVORITE_TYPE_HRS),
                'is_favorite_job' => UserFavorite::isFavorite($item->work_id, FAVORITE_TYPE_WORK),
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }
        return $items;
    }

    public function create($attributes)
    {
        $authLogin = Auth::user();
        $items = $attributes['items'];
        $checkDataCreateEntry = $this->checkDataCreateEntry($authLogin, $items);
        if ($checkDataCreateEntry['status'] != 'success') {
            return $checkDataCreateEntry;
        }
        $data = [];
        $lastEntry = Entry::query()->latest('id')->first();
        $entryCode = 'E1';
        if ($lastEntry) {
            $lastCode = $lastEntry->code;
            $number = intval(substr($lastCode, 1)) + 1; // Lấy số từ mã cuối cùng và tăng lên 1
            $entryCode = 'E' . $number; // Tạo mã mới dựa trên số đã tăng
        }
        foreach ($items as $item) {
            $motivationFileId = $item['motivation_file_id'];
            $recommendationFileId = $item['recommendation_file_id'];
            $otherFiles = @$item['other_files'];
            $arrIds = [$motivationFileId];
            if ($recommendationFileId) {
                $arrIds = array_merge($arrIds, [$recommendationFileId]);
            }
            if ($otherFiles) {
                $arrIds = array_merge($arrIds, $otherFiles);
            }
            $checkDataExist = Entry::query()->where('hr_id', $item['hr_id'])
                ->where('work_id', $item['work_id'])
                ->where('status', ENTRY_STATUS_REQUESTING)
                ->first();
            if ($checkDataExist) continue;
            $model = $this->model->create([
                'code' => $entryCode,
                'request_date' => date('Y-m-d'),
                'hr_id' => $item['hr_id'],
                'work_id' => $item['work_id'],
                'status' => ENTRY_STATUS_REQUESTING,
                'remarks' => $item['remarks'],
            ]);
            UploadFile::whereIn('id', $arrIds)
                ->update([
                    'item_id' => $model->id
                ]);
            $data[] = $model->load('files');
        }
        if (count($data) != 0) {
            $sendNotiHRDecline = NotificationHRJob::dispatch($authLogin, $data[0], ENTRY_STATUS_REQUESTING, null, $data);
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $data);
    }

    /**
     * @param $attributes
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($attributes, $id)
    {
        try {
            $authLogin = Auth::user();
            $status = $attributes['status'];
            $note = isset($attributes['note']) ? $attributes['note'] : null;
            $entry = Entry::find($id);
            if (!$entry) return self::resp(CODE_NO_ACCESS, trans('messages.data_does_not_exist'));
            $checkUpdateStatus = $this->checkUpdateStatusEntry($authLogin, $entry, $status, $note);
            if ($checkUpdateStatus->original['code'] != CODE_SUCCESS) {
                return $checkUpdateStatus;
            }
            $updateStatus = $entry->update([
                'status' => $status,
                'note' => $note,
                'display' => $status == ENTRY_STATUS_CONFIRM ? 'off' : 'stop'
            ]);
            //tạo mới thông tin ở interview
            if ($status == ENTRY_STATUS_CONFIRM && in_array($authLogin->type, [COMPANY, COMPANY_MANAGER, SUPER_ADMIN])) {
                $createInterview = $this->createInterview($entry);
            }
            $sendNotiHRDecline = NotificationHRJob::dispatch($authLogin, $entry, $status, $note);
            return self::resp(CODE_SUCCESS, Common::getMessageModal('entry', $status));
        } catch (\Exception $exception) {
            Log::error('updateStatusEntry :' . $exception);
            return self::resp(CODE_NO_ACCESS, trans('messages.data_does_not_exist'));
        }

    }

    public function hide($ids)
    {
        try {
            $arrayListIdEntry = array_unique($ids);
            Entry::query()->whereIn('id', $arrayListIdEntry)->update(['display' => 'delete']);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public static function resp($code = CODE_SUCCESS, $message = null, $data = [], $messageContent = '', $internalMessage = '')
    {
        if ($code !== CODE_SUCCESS) {
            return ResponseService::responseJsonError($code, $message, $messageContent, $internalMessage);
        }
        return ResponseService::responseJson($code, $data, $message);
    }

    private function checkUpdateStatusEntry($authLogin, $entry, $status, $note)
    {
        //check status
        if (in_array($entry->status, [ENTRY_STATUS_DECLINE, ENTRY_STATUS_REJECT, ENTRY_STATUS_CONFIRM])) {
            return self::resp(CODE_NO_ACCESS, trans('messages.data_does_not_exist'));
        }
        if ($status == ENTRY_STATUS_REQUESTING) {
            return self::resp(CODE_NO_ACCESS, trans('api.entry.status.invalid'));
        }
        //check permission role HR
        if ($authLogin->type == HR && $entry->hr->user_id != $authLogin->id) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        //check permission role COMPANY
        if ($authLogin->type == COMPANY && $entry->work->user_id != $authLogin->id) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        //check permission status follow role HR and Company
        if (((in_array($authLogin->type, [HR, HR_MANAGER]) && $status != ENTRY_STATUS_DECLINE)) || ((in_array($authLogin->type, [COMPANY, COMPANY_MANAGER]) && (!in_array($status, [ENTRY_STATUS_REJECT, ENTRY_STATUS_CONFIRM]))))) {
            return self::resp(CODE_NO_ACCESS, trans('messages.status_invalid'));
        }
        return self::resp(CODE_SUCCESS, trans('messages.mes.create_success'));
    }

    private function createInterview($entry)
    {
        $checkInterviewInfo = 0;
        $checkTypeGroup = null;
        //Kiểm tra bản ghi đã tồn tại
        $checkTypeGroup = Interview::query()
            ->where('work_id', $entry->work_id)
            ->where('code', $entry->code)
            ->first();
//        infors
        if ($checkTypeGroup) {
            $checkInterviewInfo = $checkTypeGroup->infors()->count();
        }
        $type = $this->getType($checkTypeGroup, $checkInterviewInfo);
        //Update trạng thái type nếu bản ghi đã tồn tại

        $creareInterView = Interview::create([
            "hr_id" => $entry->hr_id,
            "work_id" => $entry->work_id,
            "code" => $entry->code,
            "interview_date" => Carbon::now()->toDateString(),
            "type" => $type,
            "status_selection" => INTERVIEW_STATUS_SELECTION_DOC_PASS,
            "status_interview_adjustment" => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
            "remarks" => '',
            "display" => "on",
            "step" => INTERVIEW_TABLE_STEP_INTERVIEW,
        ]);
        //Đồng thời cập nhật lại chính bản ghi đó
        if ($checkTypeGroup) {
            $update = $checkTypeGroup->update([
                "type" => $type,
            ]);
        }
        return true;
    }

    private function getType($checkTypeGroup, $checkInterviewInfo)
    {
        $type = null;
        if ($checkTypeGroup && $checkInterviewInfo != 0) {
            return INTERVIEW_TYPE_PRIVATE;
        }
        if ($checkTypeGroup && $checkInterviewInfo == 0) {
            return INTERVIEW_TYPE_GROUP;
        }
        return INTERVIEW_TYPE_PRIVATE;
    }

    private function checkDataCreateEntry($authLogin, $items)
    {
        $wordId = $items[0]['work_id'];
        $work = Work::query()->where('id', $wordId)->where('status', '=', WORK_STATUS_RECRUITING)->first();
        if (!$work) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
        $getGoingJob = Common::getGoingJobWork($authLogin, [$wordId]);
        if (count($getGoingJob) == 0) {
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
        }
        $listHrGoingJob = array_unique(array_column($getGoingJob[$wordId], 'hrsid'));
        $listHrEntry = array_unique(array_column($items, 'hr_id'));
        $listArrayOther = array_diff($listHrEntry, $listHrGoingJob);
        if (count($listArrayOther) == 0) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.entry.check.list-hrs.create'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }
}
