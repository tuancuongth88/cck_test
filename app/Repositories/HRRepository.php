<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Http\Requests\HRRequest;
use App\Http\Resources\HRResource;
use App\Jobs\DownloadFileJob;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Offer;
use App\Models\Result;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\UserFavorite;
use App\Repositories\Contracts\HRRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class HRRepository extends BaseRepository implements HRRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        if(\auth()->check()){
            $this->scopeQuery(function ($query) {
                if (Auth::user()->type == HR) {
                    return $query->where('hrs.user_id', Auth::user()->id);
                }
                if (Auth::user()->type == COMPANY || Auth::user()->type == COMPANY_MANAGER) {
                    return $query->where('hrs.status', HRS_STATUS_PUBLIC);
                }
                return $query;
            });
        }
    }

    /**
     * Instantiate model
     *
     * @param HR $model
     */

    public function model()
    {
        return HR::class;
    }

    public function list($attributes, $paginate = true, $is_download = false)
    {
        $user = Auth::user();
        $items = $this->select('hrs.*', 'hrs.user_id', 'hrs.status', 'hr_organization.corporate_name_en',
            'hr_organization.corporate_name_ja', 'job_type.name_ja as job_type', 'job_info.name_ja as job_info',
        'countries.name_en as country_en', 'countries.name_ja as country_ja')->with(['mainJobs']);

        $items->join('hr_organization', 'hr_organization.id', '=', 'hrs.hr_organization_id');
        $items->join('job_type', 'job_type.id', '=', 'hrs.major_classification_id');
        $items->join('job_info', 'job_info.id', '=', 'hrs.middle_classification_id');
        $items->leftJoin('countries', 'countries.id', '=', 'hrs.country_id');
        $items = Common::getCoditionShowListEntry($items, $attributes);
        $items = $this->searchFilter($items, $attributes);

        if ($attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'status') {
                $items = Common::sortArrayText($items, 'hrs.status', HRS_STATUS_TEXTS, $sortBy == 'asc');
            } else {
                $items = Common::sortBy(request(), $items);
            }

        } else {
            $items = $items->orderBy("hrs.id", 'desc');
        }
//        if ($is_download) {
//            $data = $items->withCount('mainJobs')->get();
//            return $this->downloadFile($data);
//        }

        if ($paginate) {
            $items = Common::pagination($attributes, $items);
        }
        $updateItems = [];
        $arrayId = $items->pluck('id')->toArray();
        $checkOnGoingJob = Common::onGoingJob(Auth::user(), $arrayId, 'hrs');
        $checkResultWork = $this->checkResultWork($arrayId);
        $items->each(function ($item) use (&$updateItems, $checkOnGoingJob, $checkResultWork) {
            $updateItems[] = [
                'id' => $item->id,
                'corporate_name_en' => $item->corporate_name_en,
                'corporate_name_ja' => $item->corporate_name_ja,
                'hr_organization_id' => $item->hr_organization_id,
                'country_id' => $item->country_id,
                'country_name' => $item->country_name ?? '',
                'country_ja' => $item->country_ja ?? '',
                'country_en' => $item->country_en ?? '',
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'status' => $item->status,
                'age' => Carbon::parse($item->date_of_birth)->age,
                'japanese_level' => $item->japanese_level,
                'is_favorite' => UserFavorite::isFavorite($item->id, FAVORITE_TYPE_HRS),
                'is_move_entry' => Result::isResult($item->id),
                'on_job' => $this->getOnGoingJob($item, $checkOnGoingJob, $checkResultWork),
            ];
        });
        $items->setCollection(collect($updateItems));
        return $items;
    }

    public function create(array $attributes)
    {
        $user = \auth()->user();
        if ($user->type == HR) {
            if (!$user->hrOrganization) {
                return ResponseService::responseJsonError(HTTP_UNPROCESSABLE_ENTITY, trans('api.hrs.company_id_not_found'));
            }
            $attributes[Hr::HR_ORGANIZATION_ID] = $user->hrOrganization->id;
            $userId = $user->id;
        } else {
            $hrOrganization = HrOrganization::find($attributes[Hr::HR_ORGANIZATION_ID]);
            $userId = $hrOrganization->user_id;
        }
        $attributes[Hr::CREATED_BY] = $user->id;
        $attributes[Hr::USER_ID] = $userId;
        $attributes[Hr::STATUS] = HRS_STATUS_PRIVATE;
        $attributes[HR::FINAL_EDUCATION_DATE] = Carbon::parse($attributes[HR::FINAL_EDUCATION_DATE])->toDateString();
        $model = $this->model->create($attributes);
        if (isset($attributes['main_jobs'])) {
            $mainJobs = $attributes['main_jobs'];
            $this->addMainJobs($model, $mainJobs);
            $model->load('mainJobs');
        }
        return ResponseService::responseJson(CODE_SUCCESS, new HRResource($model));
    }

    public function update(array $attributes, $id)
    {
        $user = \auth()->user();
        if ($user->type == HR) {
            $attributes[Hr::HR_ORGANIZATION_ID] = $user->hrOrganization->id;
        }
        $message = trans('api.hrs.edit.success');
        $key = 'success';
        $attributes[Hr::CREATED_BY] = $user->id;
        $attributes[HR::FINAL_EDUCATION_DATE] = Carbon::parse($attributes[HR::FINAL_EDUCATION_DATE])->toDateString();
        $hrs = HR::find($id);
        if (!$hrs) return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.data_does_not_exist'));
        $checkHrs = $this->checkValidateHrs($user, $hrs);
        if ($checkHrs['status'] != 'success') {
            return $checkHrs;
        }
        if ($attributes['status'] == HRS_STATUS_PRIVATE && $hrs->status != HRS_STATUS_PRIVATE) {
            $checkOnGoingJob = Common::onGoingJob($user, [$id], 'hrs');
            if (count($checkOnGoingJob) != 0) {
                unset($attributes['status']);
                $key = 'warring';
                $message = trans('api.hrs.edit.ongoing-job');
            }
        }
        if ($hrs->status == HRS_STATUS_CONFIRM){
            unset($attributes['status']);
        }
        $model = parent::update($attributes, $id);
        HRMainJobCareer::where('hrs_id', $model->id)->delete();
        $mainJobs = $attributes['main_jobs'];
        $this->addMainJobs($model, $mainJobs);
        $model->load('mainJobs');
        return ResponseService::responseData(CODE_SUCCESS, 'success', $message, $key);
    }

    public function updateFileHR(array $attributes, $id)
    {
        $model = parent::update($attributes, $id);
        $model->load(['fileCV', 'fileJob']);
        return $model;
    }

    public function addMainJobs($model, $mainJobs = [])
    {
        foreach ($mainJobs as $mainJob) {
            $mainJob['hrs_id'] = $model->id;
            $startDate = strtotime(@$mainJob['main_job_career_date_from']) ?: '';
            $endDate = strtotime(@$mainJob['main_job_career_date_to']) ?: '';
            if ($startDate === '') {
                $mainJob['main_job_career_date_from'] = null;
            } else {
                $mainJob['main_job_career_date_from'] = date('Y-m-d', $startDate);
            }
            if ($endDate === '') {
                $mainJob['main_job_career_date_to'] = null;
            } else {
                $mainJob['main_job_career_date_to'] = date('Y-m-d', $endDate);
            }
            if (isset($mainJob['to_now']) && $mainJob['to_now'] == 'yes') {
                $mainJob['main_job_career_date_to'] = null;
            }
            HRMainJobCareer::create($mainJob);
        }
    }

    public static function searchFilter($items, $attrs, $user_download_type = null)
    {
        $hrOrgId = $attrs['hr_org_id'] ?? '';
        $countryId = $attrs['country_id'] ?? '';
        $gender = $attrs['gender'] ?? '';
        $ageFrom = $attrs['age_from'] ?? '';
        $ageTo = $attrs['age_to'] ?? '';
        $eduFrom = $attrs['edu_date_from'] ?? '';
        $eduTo = $attrs['edu_date_to'] ?? '';
        $eduClass = $attrs['edu_class'] ?? '';
        $eduDegree = $attrs['edu_degree'] ?? '';
        $workForms = $attrs['work_forms'] ?? '';
        $workHour = $attrs['work_hour'] ?? '';
        $japanLevels = $attrs['japan_levels'] ?? '';
        $middleClass = $attrs['middle_class'] ?? '';
        $mainJobIds = $attrs['main_job_ids'] ?? '';
        $search = $attrs['search'] ?? '';
        if(isset($user_download_type) && $user_download_type == \HR)
            $hrOrgId = '';
        if (!$user_download_type && Auth::user()->type == HR) {
            $hrOrgId = '';
        }
        if ($hrOrgId) {
            $items->where('hr_organization_id', $hrOrgId);
        }
        if($countryId) {
            $items->where('country_id', $countryId);
        }
        if ($gender) {
            $items->whereIn('gender', $gender);
        }
        if ($ageFrom) {
            $items->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) >= ?", [$ageFrom]);
        }
        if ($ageTo) {
            $items->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) <= ?", [$ageTo]);
        }
        if ($eduFrom) {
            $dateFrom = Carbon::parse($eduFrom)->startOfMonth()->toDateString();
            $items->where('final_education_date', '>=', $dateFrom);
        }
        if ($eduTo) {
            $dateTo = Carbon::parse($eduTo)->endOfMonth()->toDateString();
            $items->where('final_education_date', '<=', $dateTo);
        }
        if ($eduClass) {
            $items->whereIn('final_education_classification', $eduClass);
        }
        if ($eduDegree && (in_array(HR_EDUCATION_DEGREES_TYPE['university_or_more'], $eduDegree) ||
                in_array(HR_EDUCATION_DEGREES_TYPE['aside_from_university'], $eduDegree))) {
            if (in_array(HR_EDUCATION_DEGREES_TYPE['university_or_more'], $eduDegree)) {
                $items->whereIn('final_education_degree', array_keys(array_slice(HR_EDUCATION_DEGREES, 0, 3, true)));
            }
            if (in_array(HR_EDUCATION_DEGREES_TYPE['aside_from_university'], $eduDegree)) {
                $items->whereIn('final_education_degree', array_keys(array_slice(HR_EDUCATION_DEGREES, 3, 3, true)));
            }
        }
        if ($workForms) {
            $items->whereIn('work_form', $workForms);
        }
        if ($japanLevels) {
            $items->whereIn('japanese_level', $japanLevels);
        }
        if ($middleClass) {
            $items->whereIn('hrs.middle_classification_id', $middleClass);
        }
        if ($workHour) {
            $items->where('preferred_working_hours', '<=', 28);
        }
        if ($mainJobIds) {
            $items->whereHas('mainJobs', fn($q) => $q->whereIn(HRMainJobCareer::JOB_ID, $mainJobIds));
        }
        if ($search) {
            // search name|etc
            $listId = HR::search($search)->get()->pluck('id')->toArray();
            $items->whereIn('hrs.id', $listId);
        }
        return $items;
    }

    public function downloadFile($attributes, $userId)
    {
        $user = User::query()
            ->where('id', $userId)
            ->whereNotIn(User::TYPE, [COMPANY_MANAGER, COMPANY])
            ->first();
        if (!$user) {
            return false;
        }

        $items = $this->select('hrs.*', 'hrs.user_id', 'hrs.status', 'hr_organization.corporate_name_en', 'hr_organization.corporate_name_ja', 'job_type.name_ja as job_type', 'job_info.name_ja as job_info')
            ->with(['mainJobs']);

        $items->join('hr_organization', 'hr_organization.id', '=', 'hrs.hr_organization_id');
        $items->join('job_type', 'job_type.id', '=', 'hrs.major_classification_id');
        $items->join('job_info', 'job_info.id', '=', 'hrs.middle_classification_id');

        if ($user['type'] == HR) {
            $items->where('hrs.user_id', $userId);
        }
        if ($user['type'] == COMPANY || $user['type'] == COMPANY_MANAGER) {
            $items->where('hrs.status', HRS_STATUS_PUBLIC);
        }

        $items = $this->searchFilter($items, $attributes, $user['type']);

        if ($attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'status') {
                $items = Common::sortArrayText($items, 'hrs.status', HRS_STATUS_TEXTS, $sortBy == 'asc');
            } else {
                $items = Common::sortBy(request(), $items);
            }

        } else {
            $items = $items->orderBy("hrs.id", 'desc');
        }
        $data = $items->withCount('mainJobs')->get()->toArray();
        DownloadFileJob::dispatch($data);
        return ResponseService::responseJson(CODE_SUCCESS);
    }

    public function checkFileImport($request)
    {
        try {
            $file = UploadFile::where('id', $request->file_id)->first();
            if ($file->file_extension != 'csv') {
                return ['error_file' => trans('errors.hrs.error_file_import.extension')];
            }

            $file->file_model = HR::class;
            $file->save();
            $path = $file->file_path;
            $data_error = [];
            $result = [];
            $array_success = [];
            $key_data_import = 'HR_IMPORT_' . $file->id;

            foreach (Common::convertCsvToArray($path, 200) as $key => $data) {

//                if (!$data) {
//                    return false;
//                }
                if (isset($data['error']) && $data['error'] != null) {
                    return ['error_file' => $data['error']];
                }
                foreach ($data as $index => $item) {
                    $attribute = $this->processData($item, $data_error);
                    if (!$attribute) {
                        continue;
                    }
                    $array_success[] = $attribute;
                }
            }
            $result['success'] = $array_success;
            $result['error'] = $data_error;
            Cache::put($key_data_import, $result);
            return $result;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function processData($array, &$data_error)
    {
        $count = count($array);
        $attribute = [
            Hr::HR_ORGANIZATION_ID => null,
            HR::COUNTRY_ID => null,
            Hr::FULL_NAME => $array[3],
            Hr::FULL_NAME_JA => $array[4],
            Hr::GENDER => empty($array[5]) ? null : ($array[5] == '男性' ? HRS_GENDER_MALE : HRS_GENDER_FEMALE),
            Hr::DATE_OF_BIRTH => $array[6],
            Hr::WORK_FORM => null,
            Hr::PREFERRED_WORKING_HOURS => null,
            Hr::JAPANESE_LEVEL => null,
            Hr::FINAL_EDUCATION_DATE => null,
            Hr::FINAL_EDUCATION_CLASSIFICATION => null,
            Hr::FINAL_EDUCATION_DEGREE => null,
            Hr::MAJOR_CLASSIFICATION_ID => null,
            Hr::MIDDLE_CLASSIFICATION_ID => null,
            'main_jobs' => null,
            Hr::PERSONAL_PR_SPECIAL_NOTES => empty($array[$count - 13]) ? null : $array[$count - 13],
            Hr::REMARKS => empty($array[$count - 12]) ? null : $array[$count - 12],
            Hr::TELEPHONE_NUMBER => empty($array[$count - 11]) ? null : $array[$count - 11],
            Hr::MOBILE_PHONE_NUMBER => empty($array[$count - 10]) ? null : $array[$count - 10],
            Hr::MAIL_ADDRESS => $array[$count - 9],
            Hr::ADDRESS_CITY => empty($array[$count - 8]) ? null : $array[$count - 8],
            Hr::ADDRESS_DISTRICT => empty($array[$count - 7]) ? null : $array[$count - 7],
            Hr::ADDRESS_NUMBER => empty($array[$count - 6]) ? null : $array[$count - 6],
            Hr::ADDRESS_OTHER => empty($array[$count - 5]) ? null : $array[$count - 5],
            Hr::HOMETOWN_CITY => empty($array[$count - 4]) ? null : $array[$count - 4],
            Hr::HOME_TOWN_DISTRICT => empty($array[$count - 3]) ? null : $array[$count - 3],
            Hr::HOME_TOWN_NUMBER => empty($array[$count - 2]) ? null : $array[$count - 2],
            Hr::HOME_TOWN_OTHER => empty($array[$count - 1]) ? null : $array[$count - 1],
            Hr::STATUS => HRS_STATUS_PRIVATE,
        ];
        $mainJobs = [];

        for ($i = 15; $i < $count - 13; $i += 4) {
            $mainJob = [];
            if ($array[$i] || $array[$i + 1] || $array[$i + 2] || $array[$i + 3]) {
                if (!empty($array[$i])) {
                    $date = explode('〜', $array[$i]);
                    if ($date[0]) {
                        if (!$this->checkDate($date[0], 'Y年m月', $attribute, $data_error)) {
                            continue;
                        }
                        $mainJob['main_job_career_date_from'] = $this->convertDate($date[0]);
                    }
                    if (@$date[1]) {
                        if ($date[1] == '現在') {
                            $mainJob['main_job_career_date_to'] = '';
                            $mainJob['to_now'] = 'yes';

                        } else {
                            if (!$this->checkDate($date[0], 'Y年m月', $attribute, $data_error)) {
                                continue;
                            }
                            $mainJob['main_job_career_date_to'] = $this->convertDate($date[1]);
                        }
                    }
                }
                if (!empty($array[$i + 1]) && !empty($array[$i + 2])) {
                    $is_exist = $this->checkJob(JOB_TYPE_COL_1, $array[$i + 1], $array[$i + 2], '主な職務経歴の所属の項目が存在しません。', '主な職務経歴の職名の項目が存在しません。', $attribute, $data_error);
                    if (!$is_exist) {
                        continue;
                    }
                    $mainJob['department_id'] = $is_exist['job_type_id'];
                    $mainJob['job_id'] = $is_exist['job_info_id'];
                }
                if (!empty($array[$i + 3])) {
                    if (strlen($array[$i + 3]) > 2000) {
                        $attribute['message'] = $array[$i + 3] . ' が 2000 文字を超えています。';
                        array_push($data_error, (object)$attribute);
                        continue;
                    }
                    $mainJob['detail'] = str_replace("\n", " ", $array[$i + 3]);
                }
                array_push($mainJobs, $mainJob);
            }
        }
        $attribute['main_jobs'] = $mainJobs;

        $values = [$array[$count - 13], $array[$count - 12]];
        if (!$this->checkLenght($values, 2000, $attribute, $data_error)) {
            return false;
        }

        $columns = [
            '団体名' => $array[0],
            '団体名（日本語）' => $array[1],
            '氏名 ' => $array[3],
            '氏名（ﾌﾘｶﾞﾅ）' => $array[4],
            '生年月日' => $array[6],
            '日本語レベル' => $array[9],
            '最終学歴（年月）' => $array[10],
            '最終学歴（区分）' => $array[11],
            '最終学歴（学位）' => $array[12],
            '最終学歴（学科）大分類' => $array[13],
            '最終学歴（学科）中分類' => $array[14],
            'メールアドレス' => $array[$count - 9],
        ];
        foreach ($columns as $key => $value) {
            if (!$value) {
                $attribute['message'] = '必須項目の ' . $key . ' が入力されていません。';
                array_push($data_error, (object)$attribute);
                return false;
            }
        }

        $values = [
            $array[3], $array[4], $array[$count - 9], $array[$count - 8], $array[$count - 7], $array[$count - 6],
            $array[$count - 5], $array[$count - 4], $array[$count - 3], $array[$count - 2], $array[$count - 1]
        ];
        if (!$this->checkLenght($values, 50, $attribute, $data_error)) {
            return false;
        }

        if ($array[0] && $array[1]) {
            $org = HrOrganization::where(HrOrganization::CORPORATE_NAME_EN, $array[0])
                ->where(HrOrganization::CORPORATE_NAME_JA, $array[1]);
            if (Auth::user()->type == HR) {
                $org->where(HrOrganization::USER_ID, Auth::id());
            }
            $org = $org->first();
            if (!$org) {
                $attribute['message'] = '団体名、団体名（日本語）が存在しません。';
                array_push($data_error, (object)$attribute);
                return false;
            }
            $attribute[Hr::HR_ORGANIZATION_ID] = $org->id;
        }

        if ($array[6]) {
            if (!$this->checkDate($array[6], 'Y年m月d日', $attribute, $data_error)) {
                return false;
            }
            $attribute[HR::DATE_OF_BIRTH] = Carbon::createFromFormat('Y年m月d日', $array[6])->format('Y-m-d');
        }

        if ($array[7]) {
            if (!$this->getIndex($array[7], HRS_WORK_FORM_TEXT, '勤務形態の項目が存在しません。', $attribute, $data_error, Hr::WORK_FORM)) {
                return false;
            }
        }

        if ($array[8]) {
            if ($array[8] < 1 || $array[8] > 99) {
                $attribute['message'] = '希望勤務時間は1から99で入力してください。';
                array_push($data_error, (object)$attribute);
                return false;
            }
            $attribute[HR::PREFERRED_WORKING_HOURS] = $array[8];
        }

        if ($array[9]) {
            $japan = LanguageRequirement::where(LanguageRequirement::NAME, $array[9])->first();
            if (!$japan) {
                $attribute['message'] = '日本語レベルの項目が存在しません。';
                array_push($data_error, (object)$attribute);
                return false;
            }
            $attribute[Hr::JAPANESE_LEVEL] = $japan->id;
        }

        if ($array[10]) {
            if (!$this->checkDate($array[10], 'Y年m月', $attribute, $data_error)) {
                return false;
            }
            $attribute[HR::FINAL_EDUCATION_DATE] = $this->convertDate($array[10]);
        }

        if ($array[11]) {
            if (!$this->getIndex($array[11], HR_FINAL_EDUCATION_TEXT, '最終学歴（区分）の項目が存在しません。', $attribute, $data_error, Hr::FINAL_EDUCATION_CLASSIFICATION))
                return false;
        }

        if ($array[12]) {
            if (!$this->getIndex($array[12], HR_EDUCATION_DEGREES, '最終学歴（学位）の項目が存在しません。', $attribute, $data_error, Hr::FINAL_EDUCATION_DEGREE))
                return false;
        }

        if ($array[13] && $array[14]) {
            $is_exist = $this->checkJob(JOB_TYPE_COL_2, $array[13], $array[14], '最終学歴（学科）の大分類の項目が存在しません。', '最終学歴（学科）の中分類の項目が存在しません。', $attribute, $data_error);
            if (!$is_exist) {
                return false;
            }
            $attribute[Hr::MAJOR_CLASSIFICATION_ID] = $is_exist['job_type_id'];
            $attribute[Hr::MIDDLE_CLASSIFICATION_ID] = $is_exist['job_info_id'];
        }
        return $attribute;
    }

    public function getIndex($value, $array, $msg, &$attribute, &$data_error, $item)
    {
        $key = array_search($value, $array);
        if (!$key) {
            $attribute['message'] = $msg;
            array_push($data_error, (object)$attribute);
            return false;
        }
        $attribute[$item] = $key;
        return $attribute;
    }

    public function checkJob($type, $jobTypeName, $jobInfoName, $msg1, $msg2, &$attribute, &$data_error)
    {
        $jobType = JobType::where(JobType::NAME_JA, $jobTypeName)->where(JobType::TYPE, $type)->first();
        if (!$jobType) {
            $attribute['message'] = $msg1;
            array_push($data_error, (object)$attribute);
            return false;
        }
        $jobInfo = JobInfo::where(JobInfo::NAME_JA, $jobInfoName)->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        if (!$jobInfo) {
            $attribute['message'] = $msg2;
            array_push($data_error, (object)$attribute);
            return false;
        }

        return ['job_type_id' => $jobType->id, 'job_info_id' => $jobInfo->id];
    }

    public function checkLenght($values, $max, &$attribute, &$data_error)
    {
        foreach ($values as $str) {
            if (strlen($str) > $max) {
                $attribute['message'] = $str . ' が ' . $max . ' 文字を超えています。';
                array_push($data_error, (object)$attribute);
                return false;
            }
        }
        return true;
    }

    private function convertDate($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 7, 2);
        return $year . '-' . $month;
    }

    public function checkDate($date, $format, &$attribute, &$data_error)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => 'date_format:' . $format,
        ]);
        if ($validator->passes()) {
            return true;
        }
        $attribute['message'] = $date . ' 日付のフォーマットが正しくありません。' . $format . ' のフォーマットで入力してください。 ';
        array_push($data_error, (object)$attribute);
        return false;
    }

    public function importCSV($request)
    {
        try {
            $key_data_import = 'HR_IMPORT_' . $request->file_id;
            $data = Cache::get($key_data_import);
            if (!$data) {
                return false;
            }
            foreach (array_chunk($data['success'], 100) as $value) {
                foreach ($value as $key => $attributes) {
                    $this->create($attributes);
                }
            }
            Cache::forget($key_data_import);
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function hide(HRRequest $request)
    {
        try {
            $user = Auth::user();
            $HrId = $request->ids;
            $arrayListIHr = array_unique($HrId);
            $checkOnGoingJob = Common::onGoingJob($user, $arrayListIHr, 'hrs');
            $checkResultWork = $this->checkResultWork($arrayListIHr);
            if (count($checkOnGoingJob) != 0 || count($checkResultWork) != 0) {
                return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('api.hrs.edit.delete.ongoing-job'));
            }
            $updateStatus = $this->updateFollowListHRDelete($arrayListIHr);
            $updateStatus = HR::query()->whereIn('id', $arrayListIHr)->delete();
            return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
        } catch (\Exception $exception) {
            Log::error($exception);
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', 'error');

        }
    }

    public function find($id, $columns = ['*'])
    {
        $item = $this->model->find($id);
        if (!$item)
            return false;

        $item['entry'] = $this->getDetailRelationMatching($item->entries);
        $item['offer'] = $this->getDetailRelationMatching($item->offers);
        $item['interview'] = $this->getDetailRelationMatching($item->interviews);
        $item['result'] = $this->getDetailRelationMatching($item->results);
        $item['fileCV'] = $item->fileCV;
        $item['fileJob'] = $item->fileJob;
        $item['is_favorite'] = UserFavorite::isFavorite($item->id, FAVORITE_TYPE_HRS);
        $item['hr_org'] = $item->hrOrg;
        $item->load('mainJobs');
        Arr::forget($item, ['entries', 'offers', 'interviews', 'results']);
        return $item;
    }

    private function getDetailRelationMatching($relation)
    {
        $output = array();
        foreach ($relation as $key => $value) {
            if ($value instanceof Entry) {
                if ($value->display == 'off' || $value->status == ENTRY_STATUS_CONFIRM) {
                    continue;
                }
                $output[$key]['status'] = $value->status;
            }
            if ($value instanceof Offer) {
                if ($value->display == 'off' || $value->status == OFFER_STATUS_CONFIRM) {
                    continue;
                }
                $output[$key]['status'] = $value->status;
            }
            if ($value instanceof Interview || $value instanceof Result) {
                if ($value->display == 'off') {
                    continue;
                }
                $output[$key]['status'] = $value->status_selection;
            }
            $output[$key]['id'] = $value->id;
            $output[$key]['job_title'] = $value->work->title;
            $output[$key]['company_name'] = $value->work->company->company_name;
            $output[$key]['updated_at'] = $value->updated_at->format('Y年m月d日');
        }

        return $output;
    }

    private function checkValidateHrs($user, $hrs)
    {
        if (in_array($user->type, [COMPANY, COMPANY_MANAGER])) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.mes.not.action.permission'));
        }
        if ($user->type == HR && $hrs->user_id != $user->id) {
            return ResponseService::responseData(CODE_NO_ACCESS, 'error', trans('messages.mes.not.action.permission'));
        }
        return ResponseService::responseData(CODE_SUCCESS, 'success', 'success');
    }

    private function getOnGoingJob($item, $checkOnGoingJob, $checkResultWork)
    {
        if (isset($checkOnGoingJob[$item->id]) || isset($checkResultWork[$item->id])) {
            return true;
        }
        return false;
    }

    private function checkResultWork($arrayListIHr)
    {
        $dataResult = Result::query()->whereIn('hr_id', $arrayListIHr)
            ->whereIn('status_selection', [RESULT_STATUS_SELECTION_OFFER, RESULT_STATUS_SELECTION_OFFER_CONFIRM])
            ->get()->keyBy('hr_id')->toArray();
        return $dataResult;
    }

    private function updateFollowListHRDelete($arrayListIHr)
    {
        $entry = Entry::query()->whereIn('hr_id', $arrayListIHr)->update(['display' => 'delete']);
        $offer = Offer::query()->whereIn('hr_id', $arrayListIHr)->update(['display' => 'delete']);
        $entry = Interview::query()->whereIn('hr_id', $arrayListIHr)->update(['display' => 'off']);
        $entry = Result::query()->whereIn('hr_id', $arrayListIHr)->update(['display' => 'off']);
    }
}
