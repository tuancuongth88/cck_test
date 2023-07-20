<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Models\Entry;
use App\Models\HR;
use App\Models\Interview;
use App\Models\Offer;
use App\Models\Result;
use App\Models\Work;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Rules\PhoneRule;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class InterviewRepository extends BaseRepository implements InterviewRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->scopeQuery(function ($query) {
            if (Auth::user()->type == HR) {
                return $query->whereIn('interviews.hr_id', HR::query()->where(HR::USER_ID, Auth::id())->pluck('id'))
                             ->where('interviews.display', 'on');
            }
            if (Auth::user()->type == COMPANY) {
                return $query->whereIn('interviews.work_id', Work::query()->where(Work::USER_ID, Auth::id())->pluck('id'))
                             ->where('interviews.display', 'on');
            }
            return $query;
        });

    }

    /**
       * Instantiate model
       *
       * @param Interview $model
       */

    public function model(): string
    {
        return Interview::class;
    }

    public function findOne($id, $addText = true)
    {
        $this->join('hrs', 'hrs.id', '=', "interviews.hr_id")
            ->join('works', 'works.id', '=', "interviews.work_id");
        $model = $this->select([
            'id',
            'hr_id',
            'work_id',
            'code',
            'interview_date',
            'type',
            'status_selection',
            'status_interview_adjustment',
            'remarks'
        ])->with([
            'hr' => function ($q) {
                $q->select('id', 'full_name', 'full_name_ja');
            },
            'work' => function ($q) {
                $q->select('id', 'title');
            },
            'infors'
        ])->find($id);
        if ($addText) {
            $model = $this->setText($model);
        }
        return $model;
    }

    private function setText($model)
    {
        $model->statusSelectionText = STATUS_SELECTIONS[$model->status_selection];
        $model->statusInterviewAdjustmentText = STATUS_ADJUSTINGS[$model->status_interview_adjustment];
        $model->interview_from = $model->code ? 'entry': 'offer';
        $model->people->each(function ($item) {
            $item->load([
                'hr' => function ($q) {
                    $q->select('id', 'full_name', 'full_name_ja');
                }
            ]);
        });
        return $model;
    }

    public function getTableName()
    {
        return app($this->model())->getTable();
    }

    public function list($attributes, $paginate = true)
    {
        $mainTbl = $this->getTableName();
        $items = $this->select("*", "$mainTbl.id", 'hrs.full_name')
            ->with([
                'hr' => function ($q) {
                    $q->select('id', 'user_id');
                },
                'work' => function ($q) {
                    $q->select('id', 'user_id', 'title');
                },
            ]);

        $items->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id");

        $items->join('works', 'works.id', '=', "$mainTbl.work_id");

        $ids = $attributes['ids'] ?? '';

        if ($ids) {
            $items->whereIn("$mainTbl.id", $ids);
        }

        $items = HRRepository::searchFilter($items, $attributes);

        if (is_object($attributes) && $attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'job_name') {
                $attributes->field = 'title';
            }
            if ($field == 'status_selection') {
                $items = Common::sortArrayText($items, "$mainTbl.$field", STATUS_SELECTIONS, $sortBy == 'asc');
            } elseif ($field == 'status_interview_adjustment') {
                $items = Common::sortArrayText($items, "$mainTbl.$field", STATUS_ADJUSTINGS, $sortBy == 'asc');
            } else {
                if ($field == 'id') {
                    $attributes->field = "$mainTbl.$field";
                }
                $items = Common::sortBy($attributes, $items);
            }
        }
        if ($paginate) {
            $items = Common::pagination($attributes, $items);
        }
        $updateItems = [];
        $items->each(function ($item) use (&$updateItems) {
            $updateItems[] = [
                'id' => $item->id,
                'entry_code' => $item->code,
                'interview_date' => date('Ymd', strtotime($item->interview_date)),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'work_id' => $item->work_id,
                'job_name' => $item->title,
                'updating_date' => date('Ymd', strtotime($item->updated_at)),
                'status_selection' => $item->status_selection,
                'status_interview_adjustment' => $item->status_interview_adjustment
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }

        return $items;
    }

    public function create($attributes)
    {
        $entryId = $attributes['entry_id'] ?? '';
        $offerId = $attributes['offer_id'] ?? '';
        $itemId = $entryId ?? $offerId;
        if ($entryId) {
            $class = Entry::class;
        } else {
            $class = Offer::class;
        }
        $fromModel = $class::find($itemId);
        $model = $this->model()::create([
            'hr_id' => $fromModel->hr_id,
            'work_id' => $fromModel->work_id,
            'entry_code' => $entryId ? $fromModel->code: null,
            'status_selection' => $entryId ?INTERVIEW_STATUS_SELECTION_DOC_PASS: INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM,
            'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
            'remarks' => null
        ]);
        // remove data from entry table
        $fromModel->delete();

        return $model;

    }

    public function setupCalendar($attributes, $id)
    {
        $model = $this->findOne($id, false);
        $interviewType = $attributes['interview_type'];
        $times = array_combine(range(1, count($attributes['times'])), array_values($attributes['times']));

        if ($this->isPrivate($interviewType)) {
            $this->createCalendar($model, $interviewType, $times);
        } elseif ($model->code) {
            // apply for other sample entry code
            $models = $model->people()->get();
            foreach ($models as $m) {
                 $this->createCalendar($m, $interviewType, $times);
            }
        }
        return self::resp(CODE_SUCCESS, null, $this->setText($model->refresh()));
    }

    public function confirmedCalendar($attributes, $id)
    {
        $model = $this->findOne($id, false);
        $confirmed = $attributes['confirmed'];
        $confirmedTime = $attributes['confirmed_time'] ?? '';
        if ($confirmed == 'no') {
            $this->cancelInterviews($model, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE);
        } elseif (!$confirmedTime || $confirmedTime == 6) {
            $this->changeToOtherDates($model);
        } else {
            $this->dateConfirmed($model, $confirmedTime);
        }

        return self::resp(CODE_SUCCESS, null, $this->setText($model->refresh()));
    }

    public function setupZoom($attributes, $id)
    {
        $model = $this->findOne($id, false);
        if ($this->isPrivate($model->type)) {
            $this->updateZoom($model, $attributes);
        } else {
            $models = $model->people()->get();
            foreach ($models as $m) {
                $this->updateZoom($m, $attributes);
            }
        }

        return self::resp(CODE_SUCCESS, null, $this->setText($model->refresh()));
    }

    public function review($attributes, $id)
    {
        $model = $this->findOne($id, false);
        $reviewed = $attributes['reviewed'];
        if ($reviewed == 'no') {
            $this->updateFields($model, [
                'status_selection' => INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL
            ]);
        } else {
            $result = $attributes['option'];
            if ($result == REVIEW_PASS) {
                $goto = $attributes['goto'];
                $statusSelection = $model->status_selection == INTERVIEW_STATUS_SELECTION_DOC_PASS ? INTERVIEW_STATUS_SELECTION_FIRST_PASS: $model->status_selection + 1;
                if ($goto != REVIEW_PASS_AND_GO_NEXT_STEP) {
                    $statusSelection = intval($model->work->interview_follow) ?? $statusSelection;
                }
                $this->updateFields($model, [
                    'status_selection' => $statusSelection,
                    'type' => INTERVIEW_TYPE_PRIVATE,
                    'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT
                ]);

            } else {
                $statusSelection = $result;
                if ($result == 3) {
                    $statusSelection = 1;
                }

                Result::create([
                    'hr_id' => $model->hr_id,
                    'work_id' => $model->work_id,
                    'status_selection' => $statusSelection
                ]);
                $model->delete();
            }
        }

        return self::resp(CODE_SUCCESS, trans('Reviewed success'), $this->setText($model->refresh()));
    }

    private function updateZoom($model, $attributes)
    {
        $calendar = $model->infors->last();
        $calendar->fill($attributes);
        $calendar->save();
        $this->updateFields($model, [
            'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED
        ]);
    }

    private function createCalendar($model, $type, $times)
    {
        $model->infors()->create([
            'calendar_interview' => $times
        ]);
        $this->updateFields($model, [
            'type' => $type,
            'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING,
        ]);
    }

    private function dateConfirmed($model, $confirmedTime)
    {
        $date = '';
        foreach ($model->infors as $info) {
            $calendars = $info->calendar_interview;
            $dt = $calendars[$confirmedTime] ?? '';
            if ($dt) {
                $date = $dt['date'];
                $info->status = CALENDAR_CONFIRMED;
                break;
            }
        }
        if ($this->isPrivate($model->type)) {
            $this->updateCalendar($model, $date);
        } else {
            $models = $model->people()->get();
            foreach ($models as $m) {
                $this->updateCalendar($m, $date);
            }
        }
    }

    private function updateCalendar($model, $date)
    {
        $this->updateFields($model, [
            'interview_date' => $date,
            'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING
        ]);
        $model->infors->last()->update([
            'status' => CALENDAR_CONFIRMED
        ]);
    }

    private function changeToOtherDates($model)
    {
        if ($this->isPrivate($model->type)) {
            $this->declineCalendar($model);
        } else {
            $this->changeToOtherDatesForGroup($model);
        }
    }

    private function changeToOtherDatesForGroup($model)
    {
        $models = $model->people()->get();
        foreach ($models as $m) {
            $this->declineCalendar($m);
        }
    }

    private function declineCalendar($model)
    {
        $calendar = $model->infors->last();
        $this->updateFields($calendar, [
            'status' => CALENDAR_DECLINE
        ]);

        $this->updateFields($calendar, [
            'status_interview_adjustment' => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT
        ]);
    }

    private function cancelInterviews($model, $cancelType)
    {
        $fields = [
            'status_selection' => $cancelType
        ];
        if ($this->isPrivate($model->type)) {
            $this->updateFields($model, $fields);
        } else {
            $this->updateFields($model->people(), $fields);
        }
    }

    private function updateFields($model, $fields = [])
    {
        $model->update($fields);
    }

    private function isPrivate($type)
    {
        return $type == INTERVIEW_TYPE_PRIVATE;
    }

    public function hide($ids)
    {
        return $this->whereIn('interviews.id', $ids)->update(['interviews.display' => 'off']);

    }

    public function isOwner($ids = [])
    {
        $items = $this->list(new Request(['ids' => $ids]), false);
        return $items->count() == count($ids);
    }

    public static function resp($code = CODE_SUCCESS, $message = null, $data = [], $messageContent = '', $internalMessage = '')
    {
        if ($code !== CODE_SUCCESS) {
            return ResponseService::responseJsonError($code, $message, $messageContent, $internalMessage);
        }
        return ResponseService::responseJson($code, $data, $message);
    }
}
