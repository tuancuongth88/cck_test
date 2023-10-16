<?php


namespace Helper;

use App\Jobs\NotificationHRJob;
use App\Models\Entry;
use App\Models\Interview;
use App\Models\Company;
use App\Models\HR;
use App\Models\Offer;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Repository\InterviewRepository;

class Common
{

    public static function uploadFile(UploadedFile $file, $path = '', $userId = null)
    {
        $userId = $userId ?? Auth::id();
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $fileName);
    }

    public static function uploadFileWithCustomName(UploadedFile $file, $path = '', $originalName = '', $format_time = 'Ymd')
    {
        $time = $format_time ? Carbon::now()->format($format_time) : '';
        $originalName = $originalName ? $originalName : '';
        $fileName = $originalName . $time . '.' . $file->getClientOriginalExtension();
        return (object)[
            'path_file' => $file->storeAs($path, $fileName),
            'name_file' => $fileName,
        ];
    }


    public static function getOptionOnTable($model, $key, $value)
    {
        return $model::get()->pluck($value, $key)->toArray();
    }

    public static function sortBy($request, $model)
    {
        if ($request->has('sort_by') && $request->sort_by) {
            $model = $model->orderBy($request->field, $request->sort_by);
        } else {
            $model = $model->orderByRaw("$request->field IS NULL, $request->field");
        }
        return $model;
    }

    public static function convertCsvToArray($path, $limit = 500)
    {
        $getEncoding = mb_detect_encoding(file_get_contents($path), mb_list_encodings(), true);
        if ($getEncoding == 'SJIS') {
            $getEncoding = "SJIS-win";
        }
        $file = fopen($path, 'r');
        $delimiter = ",";
        $header = null;
        $iterator = 0;
        $data = array();
        $array_header = [
            '団体名', '団体名（日本語）', '国', '氏名', '氏名（ﾌﾘｶﾞﾅ）', '性別', '生年月日', '勤務形態', '勤務形態（非常勤）', '日本語レベル',
            '最終学歴（年月）', '最終学歴（区分）', '最終学歴（学位）', '最終学歴（学科）大分類', '最終学歴（学科）中分類',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（年月）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（所属）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（職名）',
            '主な職務経歴' . mb_chr(0x245F + 1) . '（詳細）',
            '自己PR・特記事項', '備考', '連絡先電話番号', '携帯電話番号', 'メールアドレス', '現住所（市）', '現住所（町）',
            '現住所（番地）', '現住所（その他）', '出身地住所（市）', '出身地住所（町）', '出身地住所（番地）', '出身地住所（その他）'
        ];

        while (($row = fgetcsv($file, 100000, $delimiter)) !== false) {
            $is_mul_1000 = false;
            if (!mb_check_encoding($row, 'UTF-8')) {
                $row = mb_convert_encoding($row, 'UTF-8', $getEncoding);
            }
            if (!$header) {
                $row[0] = trim($row[0], "\xEF\xBB\xBF");
                $intersect = array_intersect($array_header, $row);
                if (array_search('', $row) || count($intersect) != count($array_header)) {
                    $data = ['error' => trans('errors.hrs.error_file_import.format')];
                }
                $header = implode(',', $row);
            } else {
                $iterator++;
                $data[] = $row;
                if ($iterator != 0 && $iterator % $limit == 0) {
                    $is_mul_1000 = true;
                    $chunk = $data;
                    $data = array();
                    yield $chunk;
                }
            }
        }
        fclose($file);
        if (!$is_mul_1000) {
            yield $data;
        }
        return;
    }

    public static function sortNumber($request, $model)
    {
        if ($request->has('sort_by') && $request->sort_by) {
            $model = $model->orderByRaw($request->field . " IS NULL, CAST(" . $request->field . " as FLOAT)" . $request->sort_by . " ");
        } else {
            $model = $model->orderByRaw($request->field . " IS NULL ,CAST(" . $request->field . " as FLOAT)");
        }
        return $model;
    }

    public static function sortArrayText($items, $field, $arr, $sortAsc = true)
    {
        if ($sortAsc) {
            asort($arr);
        } else {
            arsort($arr);
        }
        $joinedKeys = implode(',', array_keys($arr));
        if (!$arr || !$joinedKeys) {
            return $items;
        }
        return $items->orderByRaw("FIELD($field, $joinedKeys)");
    }

    public static function pagination($request, $data)
    {
        if ($request->per_page < 0) {
            $object = $data->get()->count();
            return $data->paginate($object);
        } else {
            $limit = is_null(request('per_page')) ? config('repository.pagination.limit', 20) : request('per_page');
            return $data->paginate($limit);
        }
    }

    public static function updateStatus($attributes, $model, $id)
    {
        $user = auth()->user();
        $userType = $user->type;
        $userId = $user->id;
        $status = $attributes['status'];
        $model = $model::find($id);
        if (!$model) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        if ((in_array($userType, [HR, HR_MANAGER]) && $status != ENTRY_STATUS_DECLINE) || (in_array($userType, [COMPANY, COMPANY_MANAGER]) && !in_array($status, [ENTRY_STATUS_REJECT]))) {
            return self::resp(CODE_SUCCESS, trans('messages.status_invalid'));
        }
        if ($userType == HR && $model->hr->user_id != $userId) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        if ($userType == COMPANY && $model->work->user_id != $userId) {
            return self::resp(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        $whyReject = '';
        $only = ['status'];
        if ($status == ENTRY_STATUS_DECLINE) {
            $only[] = 'why_reject';
            $whyReject = $attributes['why_reject'] ?? '';
        }
        if ($whyReject == 10) {
            $only[] = 'other_note';
        }
        $attributes = $attributes->only($only);
        $model->update($attributes);
        return self::resp(CODE_SUCCESS, '', $model);
    }

    public static function resp($code = CODE_SUCCESS, $message = null, $data = [], $messageContent = '', $internalMessage = '')
    {
        if ($code !== CODE_SUCCESS) {
            return ResponseService::responseJsonError($code, $message, $messageContent, $internalMessage);
        }
        return ResponseService::responseJson($code, $data, $message);
    }

    public static function exportCSV($data, $columns, $fileName)
    {
        $headers = array(
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );
        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);
            foreach ($data as $value) {
                fputcsv($file, $value);
            }

            fclose($file);
        };

        return response()->stream($callback, CODE_SUCCESS, $headers);
    }

    public static function searchWork($request, $repository)
    {
        $userId = auth()->id();
        $data = $repository->with(['passion', 'languageRequirements', 'company', 'city', 'majorClassification', 'middleClassification'])
            ->select('works.*',
                DB::raw('(CASE WHEN user_favorites.relation_id IS NOT NULL THEN true ELSE false END) AS is_favorite'))
            ->leftJoin('user_favorites', function ($join) use ($userId) {
                $join->on('works.id', '=', 'user_favorites.relation_id')
                    ->where('user_favorites.user_id', '=', $userId)
                    ->where('user_favorites.type', FAVORITE_TYPE_WORK);
            });
        $data = self::getCoditionShowListOffer($data, $request);

        if ($request->has('company_id') && $request->company_id) {
            $data = $data->where('company_id', (int)$request->input('company_id'));
        }
        if ($request->has('middle_classification_id') && $request->middle_classification_id) {
            $listIds = $request->input('middle_classification_id');
            $data = $data->whereIn('middle_classification_id', $listIds);
        }
        if ($request->has('income_from') && $request->income_from) {
            $data = $data->where('expected_income', '>=', (int)$request->input('income_from'));
        }
        if ($request->has('income_to') && $request->income_to) {
            $data = $data->where('expected_income', '<=', (int)$request->input('income_to'));
        }
        if ($request->has('city_id') && $request->city_id) {
            $listIds = $request->input('city_id');
            $data = $data->whereIn('city_id', $listIds);
        }
        if ($request->has('language_requirements') && $request->language_requirements) {
            $listIds = $request->input('language_requirements');
            $data = $data->whereIn('works.id', function ($query) use ($listIds) {
                $query->select('work_id')
                    ->from('language_requirement_works')
                    ->whereIn('language_requirement_id', $listIds);
            });
        }
        if ($request->has('form_of_employment') && $request->form_of_employment) {
            $data = $data->whereIn('form_of_employment', $request->input('form_of_employment'));
        }
        if ($request->has('passion_works') && $request->passion_works) {
            $listIds = $request->input('passion_works');
            $data = $data->whereIn('works.id', function ($query) use ($listIds) {
                $query->select('work_id')
                    ->from('passion_works')
                    ->whereIn('passion_id', $listIds);
            });
        }
        if ($request->has('key_search') && $request->key_search) {
            $listId = Work::search($request->key_search)->get()->pluck('id')->toArray();
            $data = $data->whereIn('works.id', $listId);
        }

        return $data;
    }

//    public static function searchW

    /**
     * @param $workId
     * @return bool
     */
    public static function checkRecruitmentDeadline($workId)
    {
        $work = Work::find($workId);
        if (!$work) return false;
        $now = Carbon::now()->toDateString();
        $startWork = $work->application_period_from;
        $endWork = $work->application_period_to;
        if (strtotime($startWork) > strtotime($now) || strtotime($now) > strtotime($endWork)) {
            return false;
        }
        return true;
    }

    public static function getMessageModal($modelName, $status)
    {
        switch ($modelName) {
            case 'offer':
                return self::getMessageModalOffer($status);
            case 'entry':
                return self::getMessageModalEntry($status);
            case 'interview':
                return self::getMessageModalInterview($status);
            default :
                return null;
        }
    }

    public static function getMessageModalOffer($status)
    {
        switch ($status) {
            case OFFER_STATUS_DECLINE:
                return trans('messages.mes.modal.offer.decline');
            case OFFER_STATUS_CONFIRM:
                return trans('messages.mes.modal.offer.confirm');
            default :
                return null;
        }

    }

    public static function getMessageModalEntry($status)
    {
        switch ($status) {
            case ENTRY_STATUS_REJECT:
                return trans('messages.mes.modal.entry.reject');
            case ENTRY_STATUS_DECLINE:
                return trans('messages.mes.modal.entry.decline');
            case ENTRY_STATUS_CONFIRM:
                return trans('messages.mes.modal.entry.confirm');
            default :
                return null;
        }

    }

    public static function getMessageModalInterview($status)
    {
        switch ($status) {
            case INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING:
                return trans('messages.mes.modal.interview.submit.calendar');
            case INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING:
                return trans('messages.mes.modal.interview.sent');
            case INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED:
                return trans('messages.mes.modal.interview.sent');
            default :
                return null;

        }

    }

    public static function getDataForPermisstion($items, $userLogin)
    {
        if ($userLogin->type == HR) {
            $items = $items->where('hrs.user_id', $userLogin->id);
        }
        if ($userLogin->type == COMPANY) {
            $items = $items->where('works.user_id', $userLogin->id);
        }
        return $items;
    }

    public static function getInterviewInfo($interviewId, $flag = null)
    {
        $interviewInfo = [];
        if (!$interviewId) return $interviewInfo;
        $interview = Interview::find($interviewId);
        if (!$interview) return $interviewInfo;
        $calenders = $interview->infors;
        foreach ($calenders as $calender) {
//            if (in_array($calender['status'], [INTERVIEW_INFO_STATUS_TEMPORARY, INTERVIEW_INFO_STATUS_HR_REJECT, INTERVIEW_INFO_STATUS_HR_DECLINE])) continue;
            if ($calender['status'] == INTERVIEW_INFO_STATUS_HR_REJECT) continue;
            $calendarInterview = self::getCalendarInterview($calender);
            if ($flag) {
                $interviewInfo[] = [
                    'id' => $calender->id,
                    'type_code' => $calender->type,
                    'type_name' => INTERVIEW_INFO_TYPE_TEXTS[$calender->type],
                    'number' => $calender->number,
                    'number_en' => INTERVIEW_INFO_NUMBER_TEXT_EN[$calender->number],
                    'number_ja' => INTERVIEW_INFO_NUMBER_TEXT_JA[$calender->number],
                    'number_selection' => $calender->number_selection,
                    'interview_id' => $calender->interview_id,
                    'status' => $calender->status,
                    'status_en' => INTERVIEW_INFO_STATUS_TEXT_EN[$calender->status],
                    'status_ja' => INTERVIEW_INFO_STATUS_TEXT_JA[$calender->status],
                    'url_zoom' => $calender->url_zoom,
                    'id_zoom' => $calender->id_zoom,
                    'password_zoom' => $calender->password_zoom,
                    'remark' => $calender->remark,
                    'calendarInterview' => $calendarInterview,
                ];
            } else {
                $interviewInfo[] = [
                    'id' => $calender->id,
                    'type_code' => $calender->type,
                    'type_name' => INTERVIEW_INFO_TYPE_TEXTS[$calender->type],
                    'number' => $calender->number,
                    'number_en' => INTERVIEW_INFO_NUMBER_TEXT_EN[$calender->number],
                    'number_ja' => INTERVIEW_INFO_NUMBER_TEXT_JA[$calender->number],
                    'interview_id' => $calender->interview_id,
                    'status' => self::getStatusForllowResult($calender),
                    'status_en' => self::getStatusForllowResult($calender, 'en'),
                    'status_ja' => self::getStatusForllowResult($calender, 'ja'),
                    'url_zoom' => $calender->url_zoom,
                    'id_zoom' => $calender->id_zoom,
                    'password_zoom' => $calender->password_zoom,
                    'remark' => $calender->remark,
                    'date' => $calendarInterview[0]['date'],
                    'start_time' => $calendarInterview[0]['start_time'],
                    'end_time' => $calendarInterview[0]['end_time'],
                    'expected_time' => $calendarInterview[0]['expected_time'],
//                    'at_time' => $calendarInterview[0]['at_time'],
                    'weekdays' => $calendarInterview[0]['weekdays'],
//                    'timeJa' => Common::getTimeFollowJA($calendarInterview[0]['date'], $calendarInterview[0]['start_time'], $calendarInterview[0]['end_time'], $calendarInterview[0]['expected_time'], $calendarInterview[0]['at_time'], $calendarInterview[0]['weekdays']),
//                    'timeJaAP' => Common::getTimeFollowJAFollowAmPm($calendarInterview[0]['date'], $calendarInterview[0]['start_time'], $calendarInterview[0]['end_time'], $calendarInterview[0]['expected_time'], $calendarInterview[0]['at_time'], $calendarInterview[0]['weekdays']),
                    'timeJa' => $calendarInterview[0]['timeJa'],
                    'timeJaAP' => $calendarInterview[0]['timeJaAP'],
                ];
            }
        }
        return $interviewInfo;
    }

    public static function getCalendarInterview($calender)
    {
        $timeCalendar = [];
        $calendarInterview = json_decode($calender->calendar_interview, true);
        foreach ($calendarInterview as $valueCalendarInterview) {
            $expectedTime = (int)$valueCalendarInterview['expected_time'];
//            $timeSrt = $valueCalendarInterview['start_time'] . ' ' . $valueCalendarInterview['start_time_at'];
//            $startTime = Carbon::createFromFormat('g:i A', $timeSrt)->format('Y-m-d H:i:s');
            $startTime = $valueCalendarInterview['start_time'];
            $date = $valueCalendarInterview['date'];
            $endTimeConvert = Carbon::parse($startTime)->addMinute($expectedTime);
//            $endTimeConvert = Carbon::parse($startTime)->addMinute($expectedTime)->format('g:i');
//            $startTimeConvert = Carbon::parse($startTime)->format('g:i');
            $expectedtime = $valueCalendarInterview['expected_time'];
//            $atTime = $valueCalendarInterview['start_time_at'];
            $weekdays = Carbon::parse($valueCalendarInterview['date'])->isoFormat('ddd');
            $timeJa = Common::getTimeFollowJA($date, $startTime, $endTimeConvert, $expectedtime, $weekdays);
            $timeJaAP = Common::getTimeFollowJAFollowAmPm($date, $startTime, $endTimeConvert, $expectedtime, $weekdays);
            $timeCalendar[] = [
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTimeConvert,
                'expected_time' => $expectedtime,
//                'at_time' => $atTime,
                'weekdays' => $weekdays,
                'timeJa' => $timeJa,
                'timeJaAP' => $timeJaAP
            ];
        }
        return $timeCalendar;
    }

    /**
     * @param $date
     * @param $startTime
     * @param $endTime
     * @param $expectedTime
     * @param $atTime
     * @param $weekdays
     * @param null $type
     * @return string
     */
    public static function getTimeFollowJA($date, $startTime, $endTime, $expectedTime, $weekdays, $type = null)
    {
        $dayJa = null;
        $startTimeConvert = Carbon::parse($startTime)->format('H:i');
        $endTimeConvert = Carbon::parse($endTime)->format('H:i');

        $dayJa = Carbon::parse($date)->format('Y年m月d日') . ' (' . $weekdays . ') ' .
            Common::convertTimeHourse($startTimeConvert) . Common::convertTimeMinute($startTimeConvert)
            . '~' .
            Common::convertTimeHourse($endTimeConvert) . Common::convertTimeMinute($endTimeConvert);
        return $dayJa;
    }

    /**
     * @param $date
     * @param $startTime
     * @param $endTime
     * @param $expectedTime
     * @param $atTime
     * @param $weekdays
     * @param null $type
     * @return string
     */
    public static function getTimeFollowJAFollowAmPm($date, $startTime, $endTime, $expectedTime, $weekdays, $type = null)
    {
        $startTimeConvert = Common::convertTimeCalendar($startTime);
        $endTimeConvert = Common::convertTimeCalendar($endTime);
        return Carbon::parse($date)->format('Y年m月d日') . ' (' . $weekdays . ') ' . $startTimeConvert . '~' . $endTimeConvert;
    }

    public static function getStatusForllowResult($calender, $language = null)
    {
        $status = $calender->status == INTERVIEW_INFO_STATUS_COMPANY_OFFER ? INTERVIEW_INFO_STATUS_COMPANY_PASS : $calender->status;
        if ($language == 'en') {
            return INTERVIEW_INFO_STATUS_TEXT_EN[$status];
        }
        if ($language == 'ja') {
            return INTERVIEW_INFO_STATUS_TEXT_JA[$status];
        }
        return $status;
    }

    /**
     * @param $date
     * @return string
     */
    public static function convertDateToTimeJa($date)
    {
        $dateConvert = null;
        $dateConvert = Carbon::parse($date)->format('Y年m月d日') . ' (' . Carbon::parse($date)->isoFormat('ddd') . ')';
        return $dateConvert;
    }

    /**
     * @param $interviewId
     * @return null
     */
    public static function updateDisplayOGoingJob($interviewId)
    {
        if (!$interviewId) return null;
        $interview = Interview::find($interviewId);
        if (!$interview) return null;
        if ($interview->code) {
            $entry = Entry::where('code', '=', $interview->code)->first();
            if (!$entry) return null;
            $entry->update(['display' => 'stop']);
        } else {
            $offer = Offer::where(['hr_id' => $interview->hr_id, 'work_id' => $interview->work_id])->first();
            if (!$offer) return null;
            $offer->update(['display' => 'stop']);
        }
        return null;
    }

    public static function onGoingJob($user, $arrayId, $type)
    {
        switch ($type) {
            case 'hrs' :
                return self::getGoingJobHrs($user, $arrayId);
            case 'work' :
                return self::getGoingJobWork($user, $arrayId);
            default :
                return null;
        }
    }

    public static function getGoingJobHrs($user, $arrayId)
    {
        $listDataOffer = Offer::select("offers.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "offers.status", "offers.request_date", "works.id as workdid", "works.title", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "offers.hr_id")
            ->join('works', 'works.id', '=', "offers.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('hrs.id', $arrayId)
            ->whereIn('offers.display', ['on', 'off'])
            ->orderBy('offers.id', 'desc');
        $listDataOffer = Common::getDataForPermisstion($listDataOffer, $user);
        $listDataOffer = $listDataOffer->get();
        //entry

        $listDataEntry = Entry::select("entries.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "entries.status", "works.id as workdid", "entries.request_date", "works.title", "entries.code", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "entries.hr_id")
            ->join('works', 'works.id', '=', "entries.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('hrs.id', $arrayId)
            ->whereIn('entries.display', ['on', 'off'])
            ->orderBy('entries.id', 'desc');
        $listDataEntry = Common::getDataForPermisstion($listDataEntry, $user);
        $listDataEntry = $listDataEntry->get();

        $listDatas = collect(array_merge($listDataOffer->toArray(), $listDataEntry->toArray()))->groupBy('hrsid')->toArray();

        return $listDatas;
    }

    public static function getGoingJobWork($user, $arrayId)
    {
        $listDataOffer = Offer::select("offers.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "offers.status", "offers.request_date", "works.id as workdid", "works.title", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "offers.hr_id")
            ->join('works', 'works.id', '=', "offers.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('works.id', $arrayId)
            ->whereIn('offers.display', ['on', 'off'])
            ->orderBy('offers.id', 'desc');
        $listDataOffer = Common::getDataForPermisstion($listDataOffer, $user);
        $listDataOffer = $listDataOffer->get();
        //entry
        $listDataEntry = Entry::select("entries.id", "hrs.id as hrsid", 'hrs.full_name', 'hrs.full_name_ja',
            "entries.status", "works.id as workdid", "entries.request_date", "works.title", "entries.code", "companies.company_name", "companies.company_name_jp")
            ->join('hrs', 'hrs.id', '=', "entries.hr_id")
            ->join('works', 'works.id', '=', "entries.work_id")
            ->join('companies', 'companies.id', '=', "works.company_id")
            ->whereIn('works.id', $arrayId)
            ->whereIn('entries.display', ['on', 'off'])
            ->orderBy('entries.id', 'desc');
        $listDataEntry = Common::getDataForPermisstion($listDataEntry, $user);
        $listDataEntry = $listDataEntry->get();
        $listDatas = collect(array_merge($listDataOffer->toArray(), $listDataEntry->toArray()))->groupBy('workdid')->toArray();
        return $listDatas;
    }

    public static function getCoditionShowListOffer($data, $request)
    {
        $listJob = [];
        if ($request->has('display') && $request->display == 'entry') {
            $data = $data->where('status', '=', WORK_STATUS_RECRUITING);
        }
        if ($request->has('display') && $request->display == 'offer') {
            if ($request->has('hrs_id') && $request->hrs_id) {
                $listWorkForHR = Common::getGoingJobHrs(auth()->user(), [$request->hrs_id]);
                if (isset($listWorkForHR[$request->hrs_id]) && count($listWorkForHR[$request->hrs_id]) != 0) {
                    $listJob = array_column($listWorkForHR[$request->hrs_id], 'workdid');
                }
                $data = $data->where('status', '=', WORK_STATUS_RECRUITING)
                    ->whereNotIn('works.id', $listJob);
            }
        }
        return $data;
    }

    public static function getCoditionShowListEntry($data, $request)
    {
        $listHr = [];
        if ($request->has('display') && $request->display == 'entry') {
            $data = $data->where('hrs.status', HRS_STATUS_PUBLIC);
            if ($request->has('work_id') && $request->work_id) {
                $listHrForWork = Common::getGoingJobWork(auth()->user(), [$request->work_id]);
                if (isset($listHrForWork[$request->work_id]) && count($listHrForWork[$request->work_id]) != 0) {
                    $listHr = array_column($listHrForWork[$request->work_id], 'hrsid');
                }
                $data = $data->whereNotIn('hrs.id', $listHr);
            }
        }
        return $data;
    }

    public static function updateDeclineJob($model, $status, $keyStatus = 'status', $display = 'stop')
    {
        //update data matching
        $model->update([
            $keyStatus => $status,
            'note' => WHY_REJECTS[1],
            'display' => $display
        ]);
    }

    /**
     * @param $time
     * @param string $lang
     * @return string|null
     */
    public static function convertTimeMinute($time, $lang = false)
    {
        if (!$time) return null;
        $arrayTime = explode(':', $time);
        if ((int)$arrayTime[1] == 0 && $lang) {
            return null;
        } else {
            if ($lang) {
                return (int)$arrayTime[1] . TEXT_MINUTE;
            } else {
                return ':' . $arrayTime[1];
            }
        }
    }

    public static function convertTimeHourse($time, $lang = false)
    {
        if (!$time) return null;
        $arrayTime = explode(':', $time);
        return (int)$arrayTime[0];
    }

    public static function convertTimeCalendar($time)
    {
        $now = Carbon::parse($time);
        if ($now->hour < 12) {
            $formatted_time = TEXT_MONING . $now->format('g') . TEXT_TIME . Common::convertTimeMinute($time, true);
        } else {
            $formatted_time = TEXT_AFTERNOON . $now->format('g') . TEXT_TIME . Common::convertTimeMinute($time, true);
        }
        return $formatted_time;
    }
}
