<?php

/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Jobs\NotificationInterviewJob;
use App\Models\Entry;
use App\Models\HR;
use App\Models\Interview;
use App\Models\InterviewInfo;
use App\Models\Offer;
use App\Models\Result;
use App\Models\Work;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use App\Rules\PhoneRule;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class InterviewRepository extends BaseRepository implements InterviewRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
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

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return app($this->model())->getTable();
    }

    /**
     * @param $id
     * @param bool $addText
     * @return array
     */
    public function findOne($id, $addText = true)
    {
        $userLogin = Auth::user();
        $detailInterview = [];
        $mainTbl = $this->getTableName();
        $item = $this->select("*", "$mainTbl.id", 'hrs.full_name', "$mainTbl.updated_at as interview_update", "$mainTbl.remarks as remark_interview")
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at')
            ->where("$mainTbl.display", 'on')
            ->where("$mainTbl.id", $id);
        $item = Common::getDataForPermisstion($item, $userLogin);
        $item = $item->first();
        if (!$item) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $detailInterview = [
            'id' => $item->id,
            'entry_code' => $item->code,
            'type_code' => $item->type,
            'type_name' => INTERVIEW_TYPE_TEXTS[$item->type],
            'interview_from' => $item->code ? 'entry' : 'offer',
            'interview_date' => date('Ymd', strtotime($item->interview_date)),
            'hr_id' => $item->hr_id,
            'full_name' => $item->full_name,
            'full_name_ja' => $item->full_name_ja,
            'work_id' => $item->work_id,
            'job_name' => $item->title,
            'updating_date' => date('Y-m-d', strtotime($item->interview_update)),
            'updating_date_ja' => Common::convertDateToTimeJa($item->interview_update),
            'status_selection' => $item->status_selection,
            'status_selection_name' => STATUS_SELECTIONS[$item->status_selection],
            'status_interview_adjustment' => $item->status_interview_adjustment,
            'status_interview_adjustment_name' => STATUS_ADJUSTINGS[$item->status_interview_adjustment],
            'display' => $item->display,
            'note' => $item->remark_interview,
            'calendarTemporary' => $this->calender($item, 'temporary'),
            'calendar' => $addText == false ? $this->calender($item) : Common::getInterviewInfo($item->id, 'interviewInfo'),
            //            'calendar1' => Common::getInterviewInfo($item->id),
            'candidateList' => $this->candidateList($item),

        ];
        $detailInterview['optionSelectRound'] = $this->optionSelectRound($detailInterview);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $detailInterview);
    }

    private function calender($item, $temporary = null)
    {
        $arrayCalender = [];
        $calenders = $item->infors;
        foreach ($calenders as $calender) {
            if ($temporary && $calender['status'] != INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            $arrayCalender[] = [
                'id' => $calender->id,
                'type_code' => $calender->type,
                'type_name' => INTERVIEW_INFO_TYPE_TEXTS[$calender->type],
                'number' => $calender->number,
                'number_selection' => $calender->number_selection,
                'interview_id' => $calender->interview_id,
                'status' => $calender->status,
                'status_name' => INTERVIEW_INFO_STATUS_TEXT_EN[$calender->status],
                'url_zoom' => $calender->url_zoom,
                'id_zoom' => $calender->id_zoom,
                'password_zoom' => $calender->password_zoom,
                'remark' => $calender->remark,
                'calendar_interview' => json_decode($calender->calendar_interview),
                'calendar_interview_convert' => $this->calendarInterviewConvert($calender),
            ];
        }
        return $arrayCalender;
    }

    private function candidateList($item)
    {
        $candidateList = [];
        $peoples = $item->people;
        foreach ($peoples as $people) {
            if ($item->id == $people->id) continue;
            $candidateList[] = [
                'id' => $people->id,
                'hr_id' => $people->hr_id,
                'entry_code' => $people->code,
                'type_code' => $people->type,
                'type_name' => INTERVIEW_TYPE_TEXTS[$people->type],
                'full_name' => $people->hr->full_name,
                'full_name_ja' => $people->hr->full_name_ja,
                'status_selection' => $people->status_selection,
                'status_selection_name' => STATUS_SELECTIONS[$people->status_selection],
                'status_interview_adjustment' => $people->status_interview_adjustment,
                'status_interview_adjustment_name' => STATUS_ADJUSTINGS[$people->status_interview_adjustment],
                'display' => $people->display,
                'calendar' => $this->calender($people),
            ];
        }
        return $candidateList;
    }

    /**
     * @param $attributes
     * @param bool $paginate
     * @return mixed
     */
    public function list($attributes, $paginate = true)
    {
        $userLogin = Auth::user();
        $mainTbl = $this->getTableName();
        $items = $this->select("*", "$mainTbl.id", 'hrs.full_name')
            ->join('hrs', 'hrs.id', '=', "$mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$mainTbl.work_id")
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at')
            ->where("$mainTbl.display", 'on');
        $items = Common::getDataForPermisstion($items, $userLogin);
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
                'interview_date' => date('Ymd', strtotime($item->interview_date)),
                'hr_id' => $item->hr_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'work_id' => $item->work_id,
                'job_name' => $item->title,
                'updating_date' => date('Ymd', strtotime($item->updated_at)),
                'status_selection' => $item->status_selection,
                'status_selection_name' => STATUS_SELECTIONS[$item->status_selection],
                'status_interview_adjustment' => $item->status_interview_adjustment,
                'status_interview_adjustment_name' => STATUS_ADJUSTINGS[$item->status_interview_adjustment]
            ];
        });
        if ($paginate) {
            $items->setCollection(collect($updateItems));
        }
        return $items;
    }

    /**
     * @param $attributes
     * @param $id
     * @return array
     */
    public function setupCalendar($attributes, $id)
    {

        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $interview = $interviewData['data'];
        $type = $attributes['interview_type'];
        $times = $attributes['times'];
        $number = $this->getNumberCalendarInfo($interview['calendar']);
        switch ($interview['type_code']) {
            case INTERVIEW_TYPE_PRIVATE:
                return $this->createCalendarInterviewInfosOffer($interview, $times, $type, $number['numberCalenderSelection']);
            case INTERVIEW_TYPE_GROUP:
                return $this->createCalendarInterviewInfosEntry($interview, $times, $type, $number['numberCalenderSelection']);
            default:
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
    }

    private function createCalendarInterviewInfosEntry($interview, $times, $type, $number)
    {
        $checkCreateCalendar = $this->checkCreateCalendar($interview, $type);
        if ($checkCreateCalendar['status'] != 'success') {
            return $checkCreateCalendar;
        }
        switch ($type) {
            case INTERVIEW_TYPE_PRIVATE:
                return $this->createCalendarInterviewInfosEntryTypePrivate($interview, $times, INTERVIEW_INFO_TYPE_PRIVATE, $number);
            case INTERVIEW_TYPE_GROUP:
                return $this->createCalendarInterviewInfosEntryTypeGroup($interview, $times, INTERVIEW_INFO_TYPE_GROUP, $number);
            default:
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        }
    }

    private function createCalendarInterviewInfosEntryTypePrivate($interview, $times, $type, $number)
    {
        $createInterviewInfo = $this->createInterviewInfo($interview, $times, $type, $number);
        $updateInterview = $this->updateInterview($interview, null, INTERVIEW_TYPE_PRIVATE, null, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING);
        $detailInterview = $this->findOne($interview['id'], false);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $detailInterview['data']);
    }

    private function createCalendarInterviewInfosEntryTypeGroup($interview, $times, $type, $number)
    {
        $listPeoples = $interview['candidateList'];
        $createInterviewInfo = $this->createInterviewInfo($interview, $times, $type, $number);
        $updateInterview = $this->updateInterview($interview, null, null, null, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING, null, null, null);
        foreach ($listPeoples as $listPeople) {
            $createInterviewInfo = $this->createInterviewInfo($listPeople, $times, $type, $number);
            $updateInterview = $this->updateInterview($listPeople, null, null, null, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING, null, null, null, false);
        }
        $detailInterview = $this->findOne($interview['id'], false);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $detailInterview['data']);
    }

    /**
     * @param $attributes
     * @param $id
     * @return array
     */
    public function confirmedCalendar($attributes, $id)
    {
        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $time = $attributes['time'];
        $interview = $interviewData['data'];
        $checkTimeCalendar = $this->checkTimeCalendar($interview, $time);
        if ($checkTimeCalendar['status'] != 'success') return $checkTimeCalendar;
        $getDataTimeCalender = $this->getDataTimeCalendar($interview, $time);
        $updateInterview = $this->updateInterview($interview, $getDataTimeCalender['date'], null, $getDataTimeCalender['statusSelection'], $getDataTimeCalender['statusAdjustment']);
        $updateInterview = $this->updateCalendarInterviewInfos($getDataTimeCalender['calendarTem'], $getDataTimeCalender['timeChoose'], $getDataTimeCalender['statusCalendarInfo'], null, null, null, null, null, $getDataTimeCalender['numberCalendarInfo'], null, $getDataTimeCalender['dateInterview']);

        $listPeoples = $interview['candidateList'];
        foreach ($listPeoples as $listPeople) {
            $getDataTimeCalenderGroup = $this->getDataTimeCalendar($listPeople, $time);
            $updateInterview = $this->updateInterview($listPeople, $getDataTimeCalenderGroup['date'], null, $getDataTimeCalenderGroup['statusSelection'], $getDataTimeCalenderGroup['statusAdjustment']);
            $updateInterview = $this->updateCalendarInterviewInfos($getDataTimeCalenderGroup['calendarTem'], $getDataTimeCalenderGroup['timeChoose'], $getDataTimeCalenderGroup['statusCalendarInfo'], null, null, null, null, null, $getDataTimeCalenderGroup['numberCalendarInfo'], null, $getDataTimeCalenderGroup['dateInterview']);
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /**
     * @param $id
     * @param $note
     * @return array
     */
    public function confirmedInterviewHrDecline($id, $note)
    {
        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $interview = $interviewData['data'];
        $checkValidateHrDecline = $this->checkValidateCancelInterview($interview);
        if ($checkValidateHrDecline['status'] != 'success') {
            return $checkValidateHrDecline;
        }
        $getDataValidateHrDecline = $this->getDataCancelInterview($interview);
        $updateInterview = $this->updateInterview($interview, null, null, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, null, $note);
        if ($getDataValidateHrDecline) {
            $updateInterview = $this->updateCalendarInterviewInfos($getDataValidateHrDecline, null, INTERVIEW_INFO_STATUS_HR_DECLINE, null, null, null, $note);
        }
        Common::updateDisplayOGoingJob($id);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /**
     * @param $id
     * @param $note
     * @return array
     */
    public function confirmedInterviewCompanyCancel($id, $note)
    {
        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $interview = $interviewData['data'];
        $checkValidateConpanyCancel = $this->checkValidateCancelInterview($interview);
        if ($checkValidateConpanyCancel['status'] != 'success') {
            return $checkValidateConpanyCancel;
        }
        $getDataValidateHrDecline = $this->getDataCancelInterview($interview);
        $updateInterview = $this->updateInterview($interview, null, null, INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, null, $note,);
        if ($getDataValidateHrDecline) {
            $updateInterview = $this->updateCalendarInterviewInfos($getDataValidateHrDecline, null, INTERVIEW_INFO_STATUS_COMPANY_CANCEL, null, null, null, $note);
        }
        Common::updateDisplayOGoingJob($id);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /**
     * @param $attributes
     * @param $id
     * @return array
     */
    public function setupZoom($attributes, $id)
    {
        $authLogin = Auth::user();
        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $interview = $interviewData['data'];
        $checkSetupZoom = $this->checkSetUpZoom($interview, $authLogin);
        if ($checkSetupZoom['status'] != 'success') {
            return $checkSetupZoom;
        }
        $getDataUpdateZoom = $this->getDataUpdateZoom($interview);
        $updateInterview = $this->updateInterview($interview, $getDataUpdateZoom['date'], null, $getDataUpdateZoom['statusSelection'], $getDataUpdateZoom['statusAdjustment']);
        $updateInterviewInfo = $this->updateCalendarInterviewInfos($getDataUpdateZoom['calendarInfo'], null, $getDataUpdateZoom['statusCalendarInfo'], $attributes['url_zoom'], $attributes['id_zoom'], $attributes['password_zoom'], null, null, $getDataUpdateZoom['numberCalendarInfo']);
        $listPeoples = $interview['candidateList'];
        foreach ($listPeoples as $listPeople) {
            $getDataUpdateGroup = $this->getDataUpdateZoom($listPeople);
            $updateInterview = $this->updateInterview($listPeople, $getDataUpdateGroup['date'], null, $getDataUpdateGroup['statusSelection'], $getDataUpdateGroup['statusAdjustment']);
            $updateInterviewInfo = $this->updateCalendarInterviewInfos($getDataUpdateGroup['calendarInfo'], null, $getDataUpdateGroup['statusCalendarInfo'], $attributes['url_zoom'], $attributes['id_zoom'], $attributes['password_zoom'], null, null, $getDataUpdateGroup['numberCalendarInfo']);
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /**
     * @param $attributes
     * @param $id
     * @return array
     */
    public function review($attributes, $id)
    {
        $authLogin = Auth::user();
        $interviewData = $this->findOne($id, false);
        if ($interviewData['status'] != 'success') {
            return $interviewData;
        }
        $interview = $interviewData['data'];
        $status = $attributes['status'];
        $dateOffer = isset($attributes['date_offer']) ? $attributes['date_offer'] : null;
        $action = isset($attributes['action']) ? $attributes['action'] : null;
        $checkReview = $this->checkReview($interview, $status, $dateOffer, $action);
        if ($checkReview['status'] != 'success') {
            return $checkReview;
        }
        $getDataReview = $this->getDataReview($interview);
        switch ($attributes['status']) {
            case INTERVIEW_STATUS_REVIEW_PASS:
                return $this->comfirmReviewPass($interview, $getDataReview, $status, $dateOffer, $action);
            case INTERVIEW_STATUS_REVIEW_NO_PASS:
                return $this->comfirmReviewNoPass($interview, $getDataReview, $status, $dateOffer, $action);
            case INTERVIEW_STATUS_REVIEW_OFFICIAL_OFFER:
                return $this->comfirmReviewOfficialOffer($interview, $getDataReview, $status, $dateOffer, $action);
            default:
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', 'error');
        }
    }

    /* update Offer */
    private function comfirmReviewOfficialOffer($interview, $getDataReview, $status, $dateOffer, $action)
    {
        $updateInterview = $this->updateInterview($interview, null, null, null, null, null, 'off', INTERVIEW_TABLE_STEP_RESULT);
        $updateInterviewInfo = $this->updateCalendarInterviewInfos($getDataReview, null, INTERVIEW_INFO_STATUS_COMPANY_OFFER);
        $createResult = $this->createResult($interview, RESULT_STATUS_SELECTION_OFFER, $dateOffer);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /* Pass - chỉ cập nhật đến vòng 5. Nếu pass vòng 5 thì đẩy sang offer or nopass */
    private function comfirmReviewPass($interview, $getDataReview, $status, $dateOffer, $action)
    {
        // cần check thêm điều kiện khi đến vòng 5 mà vẫn chọn vào đây
        $getDataComfirmReviewPass = $this->getDataComfirmReviewPass($interview);
        $updateInterview = $this->updateInterview($interview, null, INTERVIEW_TYPE_PRIVATE, $getDataComfirmReviewPass['statusSelection'], $getDataComfirmReviewPass['statusAdjustment']);
        $updateInterviewInfo = $this->updateCalendarInterviewInfos($getDataReview, null, INTERVIEW_INFO_STATUS_COMPANY_PASS, null, null, null, null, null, null, $action);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /* No Pass */
    private function comfirmReviewNoPass($interview, $getDataReview, $status, $dateOffer, $action)
    {
        $updateInterview = $this->updateInterview($interview, null, null, null, null, null, 'off', INTERVIEW_TABLE_STEP_RESULT);
        $updateInterviewInfo = $this->updateCalendarInterviewInfos($getDataReview, null, INTERVIEW_INFO_STATUS_COMPANY_NO_PASS);
        $createResult = $this->createResult($interview, RESULT_STATUS_SELECTION_NOT_PASS);
        Common::updateDisplayOGoingJob($interview['id']);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    /**
     * @param $ids
     * @return bool
     */
    public function hide($ids)
    {
        try {
            $arrayListIdInterview = array_unique($ids);
            $updateStatus = Interview::query()->whereIn('id', $arrayListIdInterview)->update([
                'display' => 'off',
            ]);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param $interview
     * @param $calendar
     * @param $type
     * @return array
     */
    private function createCalendarInterviewInfosOffer($interview, $calendar, $type, $number)
    {
        //check da ton tai lịch da phong van chua
        $checkCreateCalendar = $this->checkCreateCalendar($interview, $type);
        if ($checkCreateCalendar['status'] != 'success') {
            return $checkCreateCalendar;
        }
        $createInterviewInfo = $this->createInterviewInfo($interview, $calendar, INTERVIEW_TYPE_PRIVATE, $number);
        if ($createInterviewInfo['status'] != 'success') {
            return $createInterviewInfo;
        }
        // Sau khi tạo lịch phỏng vấn thành công thì quay lại update trạng thái cho interview
        $updateInterview = $this->updateInterview($interview, null, null, null, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING);
        $detailInterview = $this->findOne($interview['id'], false);
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $detailInterview['data']);
    }

    private function updateCalendarInterviewInfos($interviewInfo, $calendar = null, $status = null, $urlZoom = null, $idZoom = null, $passwordZoom = null, $remark = null, $type = null, $number = null, $numberSelection = null, $dateInterview = null)
    {
        // update lại lịch phỏng vấn khi hr không đồng ý
        try {
            $interviewInfo = InterviewInfo::find($interviewInfo['id']);
            if ($calendar) $interviewInfo->calendar_interview = json_encode($calendar);
            if ($status) $interviewInfo->status = $status;
            if ($type) $interviewInfo->type = $type;
            if ($number) $interviewInfo->number = $number;
            if ($numberSelection) $interviewInfo->number_selection = $numberSelection;
            if ($dateInterview) $interviewInfo->date_interview = $dateInterview;
            if ($urlZoom) $interviewInfo->url_zoom = $urlZoom;
            if ($idZoom) $interviewInfo->id_zoom = $idZoom;
            if ($passwordZoom) $interviewInfo->password_zoom = $passwordZoom;
            if ($remark) $interviewInfo->remark = $remark;
            $interviewInfo->save();

            if ($status == INTERVIEW_INFO_STATUS_HR_REJECT) {
                $interview = $interviewInfo->interview;
                $sendNotiOffer = NotificationInterviewJob::dispatch($interview, 'hr_reject');
            }
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $interviewInfo->toArray());
            //update lại lịch phỏng vấn  trạng thái , rom , chỉ có company mới được update zoom
        } catch (\Exception $exception) {
            Log::error('updateInterviewInfo' . $exception);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.error.update'));
        }
    }

    private function updateInterview($interview, $interviewDate = null, $type = null, $statusSelection = null, $statusAdjustment = null, $remarks = null, $display = null, $step = null, $flagNoti = true)
    {
        try {
            $dataNotiAdjustmentAdjusting = $this->getDataNotiAdjustmentAdjusting($interview);
            $checkUpdateInterview = $this->checkUpdateInterview($interview);
            $interview = Interview::find($interview['id']);
            if ($interviewDate) $interview->interview_date = $interviewDate;
            if ($type) $interview->type = $type;
            if ($statusSelection) $interview->status_selection = $statusSelection;
            if ($statusAdjustment) $interview->status_interview_adjustment = $statusAdjustment;
            if ($remarks) $interview->remarks = $remarks;
            if ($display) $interview->display = $display;
            if ($step) $interview->step = $step;
            $interview->save();
            // Bắn notification theo các trạng thái truyền vào
            if ($statusAdjustment || $statusSelection) {
                if ($flagNoti) {
                    $sendNotiOffer = NotificationInterviewJob::dispatch($interview, $statusAdjustment, $statusSelection, $dataNotiAdjustmentAdjusting);
                }
            }
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $interview->toArray());
        } catch (\Exception $exception) {
            Log::error('createInterview' . $exception);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.error.update'));
        }
    }

    /**
     * @param $interview
     * @param $calendar
     * @param $type
     * @return array
     */
    private function createInterviewInfo($interview, $calendar, $type = null, $number = null)
    {
        try {
            $createInterviewInfo = InterviewInfo::create([
                'interview_id' => $interview['id'],
                'number' => $number,
                'type' => $type,
                'calendar_interview' => json_encode($calendar),
                'status' => INTERVIEW_INFO_STATUS_TEMPORARY,
            ]);
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
        } catch (\Exception $e) {
            Log::error('createInterviewInfo' . $e);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.info.error.create'));
        }
    }

    private function checkUpdateInterview($interview)
    {
        if ($interview['display'] == 'off') {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.error.update.display'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }


    /** DK : chưa có lịch phỏng vấn or lịch phỏng vấn bị HR từ chối hay đã phỏng vấn pass vòng 1
     * @param $interview
     * @return array
     */
    private function checkCreateCalendar($interview, $type)
    {
        // kiểm tra đã tồn tại chưa
        $calendars = $interview['calendar'];
        if ($interview['type_code'] == INTERVIEW_TYPE_PRIVATE && $type == INTERVIEW_TYPE_GROUP) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.type'));
        }
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.decline'));
        }
        if ($interview['status_interview_adjustment'] != INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.status'));
        }
        if (count($calendars) == 0) {
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
        }
        foreach ($calendars as $calendar) {
            if (in_array($calendar['status'], [INTERVIEW_INFO_STATUS_HR_DECLINE, INTERVIEW_INFO_STATUS_COMPANY_NO_PASS, INTERVIEW_INFO_STATUS_COMPANY_OFFER, INTERVIEW_INFO_STATUS_TEMPORARY])) {
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.info.check.create'));
            }
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    private function checkTimeCalendar($interview, $time)
    {
        $calendars = $interview['calendar'];
        // chi chỉ cập nhật thời gian cho những bản ghi của calendar đang ở trạng thái tạm thời
        if (count($calendars) == 0) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.check'));
        }
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.decline'));
        }
        if ($interview['status_interview_adjustment'] != INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.status'));
        }
        $listStatusForCalendar = array_column($calendars, 'status');
        if (!in_array(INTERVIEW_INFO_STATUS_TEMPORARY, $listStatusForCalendar)) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.check'));
        }
        foreach ($calendars as $keyCalendar => $valueCalendar) {
            if ($valueCalendar['status'] != INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            if ($time != CALENDAR_TIMELINE_OTHER && !in_array($time - 1, array_keys($valueCalendar['calendar_interview']))) {
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.check'));
            }
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    private function checkValidateCancelInterview($interview)
    {
        $calendars = $interview['calendar'];
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.decline'));
        }
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_DOC_PASS, INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM])
            && $interview['status_interview_adjustment'] == INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.beginning'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    private function checkSetUpZoom($interview, $authLogin)
    {
        $dataConfim = [];
        $calendars = $interview['calendar'];
        if (!in_array($authLogin->type, [HR_MANAGER, SUPER_ADMIN])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.zoom.permission'));
        }
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.decline'));
        }
        if ($interview['status_interview_adjustment'] != INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.url.empty'));
        }
        if (count($calendars) == 0) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.check'));
        }
        $listStatusForCalendar = array_column($calendars, 'status');
        if (!in_array(INTERVIEW_INFO_STATUS_HR_CONFIRM, $listStatusForCalendar)) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.check'));
        }
        if (end($calendars)['status'] != INTERVIEW_INFO_STATUS_HR_CONFIRM) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.confrim.calendar.url.empty'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $dataConfim);
    }

    private function getDataTimeCalendar($interview, $time)
    {
        $dataConfim['calendarTem'] = null;
        $dataConfim['timeChoose'] = null;
        $dataConfim['date'] = null;
        $calendars = $interview['calendar'];
        foreach ($calendars as $keyCalendar => $valueCalendar) {
            if ($valueCalendar['status'] != INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            $dataConfim['calendarTem'] = $valueCalendar;
            $dataConfim['timeChoose'][] = $time != CALENDAR_TIMELINE_OTHER ? (array)$valueCalendar['calendar_interview'][$time - 1] : null;
        }
        if ($time == CALENDAR_TIMELINE_OTHER) {
            $data['timeChoose'] = null;
            $data['calendarTem'] = $dataConfim['calendarTem'];
            $data['date'] = null;
            $data['statusCalendarInfo'] = INTERVIEW_INFO_STATUS_HR_REJECT;
            $data['statusSelection'] = $interview['status_selection'];
            $data['statusAdjustment'] = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT;
            $data['numberCalendarInfo'] = null;
            $data['dateInterview'] = null;
        } else {
            $data['timeChoose'] = $dataConfim['timeChoose'];
            $data['calendarTem'] = $dataConfim['calendarTem'];
            $data['date'] = $dataConfim['timeChoose'][0]['date'];
            $data['statusCalendarInfo'] = INTERVIEW_INFO_STATUS_HR_CONFIRM;
            $data['statusSelection'] = $interview['status_selection'];
            $data['statusAdjustment'] = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING;
            $data['numberCalendarInfo'] = null;
            $data['dateInterview'] = $dataConfim['timeChoose'][0]['date'];
        }
        return $data;
    }

    private function getDataCancelInterview($interview)
    {
        $dataConfim = null;
        $calendars = $interview['calendar'];
        foreach ($calendars as $keyCalendar => $valueCalendar) {
//            if ($valueCalendar['status'] == INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            $dataConfim = $valueCalendar;
        }
        return $dataConfim;
    }

    private function getDataUpdateZoom($interview)
    {
        $data = [];
        $calendars = $interview['calendar'];
        $calendarInfo = end($interview['calendar']);
        $data['calendarInfo'] = $calendarInfo;
        $data['date'] = null;
        $data['statusSelection'] = null;
        $data['statusAdjustment'] = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED;
        $data['statusCalendarInfo'] = INTERVIEW_INFO_STATUS_HR_SET_URL;
        $data['numberCalendarInfo'] = null;
        return $data;
    }

    private function getStatusSelection($interview)
    {
        $statusPresent = $interview['status_selection'];
        if (in_array($statusPresent, [INTERVIEW_STATUS_SELECTION_DOC_PASS, INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM])) {
            return INTERVIEW_STATUS_SELECTION_FIRST_PASS;
        }
        if ($statusPresent == INTERVIEW_STATUS_SELECTION_FIRST_PASS) {
            return INTERVIEW_STATUS_SELECTION_FIRST_PASS + 1;
        }
        return $statusPresent + 1;
    }

    private function getStatusCalendarInfo($calendarInfo)
    {
        $statusCalenderInfoPresent = $calendarInfo['status'];
        if ($statusCalenderInfoPresent == INTERVIEW_INFO_STATUS_HR_CONFIRM) {
            return INTERVIEW_INFO_STATUS_COMPANY_FIRST;
        }
        if ($statusCalenderInfoPresent == INTERVIEW_INFO_STATUS_COMPANY_FIRST) {
            return INTERVIEW_INFO_STATUS_COMPANY_FIRST + 1;
        }
        return $statusCalenderInfoPresent + 1;
    }

    private function getNumberCalendarInfo($calendarInfo)
    {
        $dataConfirm = [];
        $numberCalenderInfo = [];
        if (count($calendarInfo) == 0) {
            $numberCalenderInfo = $this->getDataNumberCalendarInfo($dataConfirm);
            return $numberCalenderInfo;
        }
        foreach ($calendarInfo as $info) {
            if ($info['status'] == INTERVIEW_INFO_STATUS_HR_REJECT) continue;
            $dataConfirm = $info;
        }
        if (count($dataConfirm) == 0) {
            $numberCalenderInfo = $this->getDataNumberCalendarInfo($dataConfirm);
            return $numberCalenderInfo;
        }
        $numberCalenderInfo = $this->getDataNumberCalendarInfo($dataConfirm);
        return $numberCalenderInfo;
    }

    private function checkReview($interview, $status, $dateOffer, $action)
    {
        $dataConfim = [];
        $calendars = $interview['calendar'];
        if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.hr.decline'));
        }
        if ($interview['status_interview_adjustment'] != INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.status'));
        }
        if ($status == INTERVIEW_STATUS_REVIEW_OFFICIAL_OFFER && $dateOffer == null) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.status.offer'));
        }
        if ($status == INTERVIEW_STATUS_REVIEW_PASS && $action == null) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.pass.action'));
        }
        if ($status == INTERVIEW_STATUS_REVIEW_PASS && $action) {
            //2 cái này có vẻ check trùng
            if (in_array($interview['status_selection'], [INTERVIEW_STATUS_SELECTION_DOC_PASS, INTERVIEW_STATUS_SELECTION_OFFER_CONFIRM])) {
                if ($action < 2) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.pass.round'));
            }
            //Lấy giá trị lớn nhất trong list lịch phỏng vấn
            $numberMaxCalendar = max(array_column($calendars, 'number'));
            if ($numberMaxCalendar == INTERVIEW_INFO_NUMBER_FIFTH) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.pass.round.end'));
            if ($action <= $numberMaxCalendar) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.pass.round'));
        }
        foreach ($calendars as $keyCalendar => $valueCalendar) {
            if ($valueCalendar['status'] == INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            $dataConfim = $valueCalendar;
        }
        if ($dataConfim['status'] != INTERVIEW_INFO_STATUS_HR_SET_URL) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.review.check.status'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    private function getDataReview($interview)
    {
        $dataConfim = null;
        $calendars = $interview['calendar'];
        foreach ($calendars as $keyCalendar => $valueCalendar) {
            if ($valueCalendar['status'] == INTERVIEW_INFO_STATUS_TEMPORARY) continue;
            $dataConfim = $valueCalendar;
        }
        return $dataConfim;
    }

    private function createResult($interview, $status, $dateOffer = null)
    {
        try {
            $result = new Result();
            $result->interview_id = $interview['id'];
            $result->hr_id = $interview['hr_id'];
            $result->work_id = $interview['work_id'];
            $result->code = $interview['entry_code'];
            $result->status_selection = $status;
            $result->time = $this->convertDateYmd($dateOffer);
            $result->hire_date = null;
            $result->decline_date = null;
            $result->note = null;
            $result->display = 'on';
            $result->save();
            $statusSelection = ($status == 1) ? 'offer' : 'no_pass';
            $interview = Interview::find($interview['id']);
            $sendNotiOffer = NotificationInterviewJob::dispatch($interview, null, $statusSelection);
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success', $result->toArray());
        } catch (\Exception $exception) {
            Log::error('createResult ' . $exception);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.interview.error.update'));
        }
    }

    private function getDataComfirmReviewPass($interview)
    {
        $data = [];
        $data['statusSelection'] = $this->getStatusSelection($interview);
        $data['statusAdjustment'] = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT;
        return $data;
    }

    private function convertDateYmd($date)
    {
        if (!$date) return null;
        $dateConvert = Carbon::parse($date)->format('Ymd');
        return $dateConvert;
    }

    private function optionSelectRound($item)
    {
        $data = [];
        $roundConfig = $this->getNumberCalendarInfo($item['calendar']);
        $data['round_current_number'] = $roundConfig['numberCalenderInfoCurent'] == INTERVIEW_INFO_NUMBER_FIFTH ? INTERVIEW_INFO_NUMBER_FIFTH : $roundConfig['numberCalenderInfoCurent'];
        $data['round_current_option'] = $this->getCurrentRound($roundConfig);
        $data['round_next_number'] = $roundConfig['numberCalenderInfoNext'];
        $data['round_next_option'] =  InterviewInfo::$optionSelectRound[$roundConfig['numberCalenderInfoNext']];
        $data['round_select_number'] = $roundConfig['numberCalenderInfoNext'] == INTERVIEW_INFO_NUMBER_FIFTH ? INTERVIEW_INFO_NUMBER_FIFTH : $roundConfig['numberCalenderCanSelection'];
        $data['round_select_option'] = $roundConfig['numberCalenderInfoNext'] == INTERVIEW_INFO_NUMBER_FIFTH ? INTERVIEW_INFO_NUMBER_FIFTH : $roundConfig['numberCalenderCanSelectionFull'];
        $data['round_final_number'] = INTERVIEW_INFO_NUMBER_FIFTH;
        $data['round_final_option'] = InterviewInfo::$optionSelectRound[INTERVIEW_INFO_NUMBER_FIFTH];
        $data['round_option_number'] = [$data['round_next_option']['round_number'], $data['round_final_option']['round_number']];
        $data['round_option_inteview'] = $this->getDataRoundOptionIntervew($data['round_next_option'], $data['round_final_option']);
        return $data;
    }

    private function calendarInterviewConvert($calendarInterviews)
    {
        $arrayTimeConvert = [];
        $arrayTimeCalendars = Common::getCalendarInterview($calendarInterviews);
        foreach ($arrayTimeCalendars as $arrayTimeCalendar) {
            $arrayTimeConvert[] = [
                "date" => $arrayTimeCalendar["date"],
                "start_time" => $arrayTimeCalendar["start_time"],
                "end_time" => $arrayTimeCalendar["end_time"],
                "expected_time" => $arrayTimeCalendar["expected_time"],
//                "at_time" => $arrayTimeCalendar["at_time"],
                "weekdays" => $arrayTimeCalendar["weekdays"],
                "timeJa" => $arrayTimeCalendar['timeJa'],
                "timeJaAP" => $arrayTimeCalendar['timeJaAP'],
            ];
        }
        return $arrayTimeConvert;
    }

    private function numberCalenderCanSelection($numberCalenderInfo)
    {
        $calendarCanSelectionOption = [];
        $calendarFirst = $numberCalenderInfo['numberCalenderInfoNext'];
        $calendarFinnal = INTERVIEW_INFO_NUMBER_FIFTH;
        for ($i = $calendarFirst; $i <= $calendarFinnal; $i++) {
            $calendarCanSelectionOption['optionFull'][] = InterviewInfo::$optionSelectRound[$i];
            $calendarCanSelectionOption['number'][] = $i;
        }
        return $calendarCanSelectionOption;
    }

    private function getCurrentRound($roundConfig)
    {
//        if ($roundConfig['numberCalenderInfoNext'] - 1 == 0) {
//            return InterviewInfo::$optionSelectRound[1];
//        }
//        if ($roundConfig['numberCalenderInfoNext'] == 5) {
//            return InterviewInfo::$optionSelectRound[5];
//        }
        return InterviewInfo::$optionSelectRound[$roundConfig['numberCalenderInfoCurent']];
    }

    private function getDataRoundOptionIntervew($roundNextOtion, $roundFinalOption)
    {
        $dataRoundOptionIntervew = [];
        if ($roundFinalOption == $roundNextOtion) {
            $dataRoundOptionIntervew[] = $roundFinalOption;
        } else {
            $dataRoundOptionIntervew = [$roundNextOtion, $roundFinalOption];
        }
        return $dataRoundOptionIntervew;
    }

    private function getDataNotiAdjustmentAdjusting($interview)
    {
        $dataAdjustmentAdjusting = [];
        $dataAdjustmentAdjusting[] = [
            'hrs_id' => $interview['hr_id'],
            'full_name_ja' => $interview['full_name_ja'],
            'full_name' => $interview['full_name'],
        ];
        $candidateList = isset($interview['candidateList']) ? $interview['candidateList'] : [];
        foreach ($candidateList as $person) {
            $dataAdjustmentAdjusting[] = [
                'hrs_id' => $person['hr_id'],
                'full_name_ja' => $person['full_name_ja'],
                'full_name' => $person['full_name'],
            ];
        }
        return $dataAdjustmentAdjusting;
    }

    private function getDataNumberCalendarInfo($dataConfirm = [])
    {
        $numberCalenderInfo = [];
        if (count($dataConfirm) == 0) {
            $numberCalenderInfo['numberCalenderInfoCurent'] = INTERVIEW_INFO_NUMBER_FIRST;
            $numberCalenderInfo['numberCalenderInfoNext'] = INTERVIEW_INFO_NUMBER_FIRST + 1;
            $numberCalenderInfo['numberCalenderSelection'] = INTERVIEW_INFO_NUMBER_FIRST;
            $numberCalenderInfo['numberCalenderCanSelection'] = $this->numberCalenderCanSelection($numberCalenderInfo)['number'];
            $numberCalenderInfo['numberCalenderCanSelectionFull'] = $this->numberCalenderCanSelection($numberCalenderInfo)['optionFull'];
            return $numberCalenderInfo;
        } else {
            $numberCalenderInfo['numberCalenderInfoCurent'] = $this->numberCalenderInfoCurent($dataConfirm);
            $numberCalenderInfo['numberCalenderInfoNext'] = $numberCalenderInfo['numberCalenderInfoCurent'] == INTERVIEW_INFO_NUMBER_FIFTH ? INTERVIEW_INFO_NUMBER_FIFTH : $numberCalenderInfo['numberCalenderInfoCurent'] + 1;
            $numberCalenderInfo['numberCalenderSelection'] = (isset($dataConfirm['number_selection']) && $dataConfirm['number_selection']) ? $dataConfirm['number_selection'] : $numberCalenderInfo['numberCalenderInfoNext'];
            $numberCalenderInfo['numberCalenderCanSelection'] = $this->numberCalenderCanSelection($numberCalenderInfo)['number'];
            $numberCalenderInfo['numberCalenderCanSelectionFull'] = $this->numberCalenderCanSelection($numberCalenderInfo)['optionFull'];
            return $numberCalenderInfo;
        }
    }

    private function numberCalenderInfoCurent($dataConfirm)
    {
        $numberInCalender = $dataConfirm['number'];
        if (in_array($dataConfirm['status'], [INTERVIEW_INFO_STATUS_COMPANY_PASS])) {
            if ($numberInCalender == INTERVIEW_INFO_NUMBER_FIFTH) {
                return INTERVIEW_INFO_NUMBER_FIFTH;
            } else {
                return $numberInCalender + 1;
            }
        }
        return $numberInCalender;
    }
}
