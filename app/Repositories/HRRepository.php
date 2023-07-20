<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace Repository;

use App\Http\Requests\HRRequest;
use App\Http\Resources\HRResource;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Result;
use App\Models\UploadFile;
use App\Models\UserFavorite;
use App\Repositories\Contracts\HRRepositoryInterface;
use Carbon\Carbon;
use Helper\Common;
use Helper\ResponseService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Repository\BaseRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class HRRepository extends BaseRepository implements HRRepositoryInterface
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->scopeQuery(function ($query) {
        if (Auth::user()->type == HR) {
            return $query->where('hrs.user_id', Auth::user()->id);
        }
        return $query;
        });
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
        $items = $this->select('hrs.*', 'hrs.user_id', 'hrs.status', 'hr_organization.corporate_name_en', 'hr_organization.corporate_name_ja', 'job_type.name_ja as job_type', 'job_info.name_ja as job_info')->with(['mainJobs']);

        $items->join('hr_organization', 'hr_organization.id', '=', 'hrs.hr_organization_id');
        $items->join('job_type', 'job_type.id', '=', 'hrs.major_classification_id');
        $items->join('job_info', 'job_info.id', '=', 'hrs.middle_classification_id');

        $items = $this->searchFilter($items, $attributes);


        if (Auth::user()->type == HR && $attributes->has('field') && $attributes->field == 'corporate_name_en') {
            $attributes->field = '';
        }

        if ($attributes->has('field') && $attributes->field) {
            $field = $attributes->field;
            $sortBy = $attributes->sort_by;
            if ($field == 'date_of_birth' && $sortBy == 'asc') {
                $attributes->sort_by = 'desc';
            } elseif ($field == 'date_of_birth' && $sortBy == 'desc') {
                $attributes->sort_by = 'asc';
            }
            if ($field == 'status') {
                $items = Common::sortArrayText($items, 'hrs.status', HRS_STATUS_TEXTS, $sortBy == 'asc');
            }

        }else{
            $items = $items->orderBy("hrs.id", 'desc');
        }
        if($is_download) {
            $data = $items->withCount('mainJobs')->get();
            return $this->downloadFile($data);
        }

        if ($paginate) {
            $items = Common::pagination($attributes, $items);
        }
        $updateItems = [];
        $items->each(function ($item) use (&$updateItems) {
            $updateItems[] = [
                'id' => $item->id,
                'corporate_name_en' => $item->corporate_name_en,
                'corporate_name_ja' => $item->corporate_name_ja,
                'hr_organization_id' => $item->hr_organization_id,
                'full_name' => $item->full_name,
                'full_name_ja' => $item->full_name_ja,
                'status' => $item->status,
                'age' => Carbon::parse($item->date_of_birth)->age,
                'japanese_level' => $item->japanese_level,
                'is_favorite' => UserFavorite::isFavorite($item->id, FAVORITE_TYPE_HRS),
                'is_move_entry' => Result::isResult($item->id),
            ];
        });
        $items->setCollection(collect($updateItems));
        return $items;
    }
    public function create(array $attributes)
    {
        $user = \auth()->user();
        if ($user->type == HR) {
            if (!$user->hrOrganization){
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
        $attributes[HR::FINAL_EDUCATION_DATE] = Carbon::parse($attributes[HR::FINAL_EDUCATION_DATE])->toDateString();
        $model = $this->model->create($attributes);
        $mainJobs = $attributes['main_jobs'];
        $this->addMainJobs($model, $mainJobs);
        $model->load('mainJobs');

        return ResponseService::responseJson(CODE_SUCCESS, new HRResource($model));
    }
    public function update(array $attributes, $id)
    {
        $user = \auth()->user();
        $userId = 0;
        if ($user->type == HR) {
            $attributes[Hr::HR_ORGANIZATION_ID] = $user->hrOrganization->id;
            $userId = $user->id;
        }
        $attributes[Hr::CREATED_BY] = $user->id;
        $attributes[Hr::USER_ID] = $userId;
        $attributes[HR::FINAL_EDUCATION_DATE] = Carbon::parse($attributes[HR::FINAL_EDUCATION_DATE])->toDateString();
        $model = parent::update($attributes, $id);
        HRMainJobCareer::where('hrs_id', $model->id)->delete();
        $mainJobs = $attributes['main_jobs'];
        $this->addMainJobs($model, $mainJobs);
        $model->load('mainJobs');
        return $model;
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
            $startDate = strtotime($mainJob['main_job_career_date_from']);
            $endDate = strtotime($mainJob['main_job_career_date_to']);
            $mainJob['main_job_career_date_from'] = date('Y-m-d', $startDate);
            $mainJob['main_job_career_date_to'] = $endDate ?date('Y-m-d', $endDate): '';
            if (isset($mainJob['to_now']) && $mainJob['to_now'] == 'yes') {
                $mainJob['main_job_career_date_to'] = null;
            }
            HRMainJobCareer::create($mainJob);
        }
    }

    public static function searchFilter($items, $attrs)
    {
        $hrOrgId = $attrs['hr_org_id'] ?? '';
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
        if (Auth::user()->type == HR) {
            $hrOrgId = '';
        }
        if ($hrOrgId) {
            $items->where('hr_organization_id', $hrOrgId);
        }
        if ($gender) {
            $items->whereIn('gender', $gender);
        }
        if ($ageFrom) {
            $dateFrom = Carbon::now()->subYears($ageFrom)->toDateString();

            $items->where('date_of_birth', '<=', $dateFrom);
        }
        if ($ageTo) {
            $dateTo = Carbon::now()->subYears($ageTo)->toDateString();
            $items->where('date_of_birth', '>=', $dateTo);
        }
        if ($eduFrom) {
            $dateFrom = Carbon::parse($eduFrom)->toDateString();
            $items->where('final_education_date', '>=', $dateFrom);
        }
        if ($eduTo) {
            $dateTo = Carbon::parse($eduTo)->endOfMonth()->toDateString();
            $items->where('final_education_date', '<=', $dateTo);
        }
        if ($eduClass) {
            $items->whereIn('final_education_classification', $eduClass);
        }
        if ($eduDegree && (!in_array(HR_EDUCATION_DEGREES_TYPE['university_or_more'], $eduDegree) ||
                !in_array(HR_EDUCATION_DEGREES_TYPE['aside_from_university'], $eduDegree))) {
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
            $items->whereNotNull('preferred_working_hours');
        }
        if ($mainJobIds) {
            $items->whereHas('mainJobs', fn ($q) => $q->whereIn('id', $mainJobIds));
        }
        if ($search) {
            // search name|etc
            $items->where([['full_name', 'LIKE', "%$search%"]]);
            $items->orWhere('full_name_ja', 'LIKE', "%$search%");
        }
        return $items;
    }

    public function downloadFile($data)
    {
        $rowData = [];
        $max = $data->max('main_jobs_count');
        foreach ($data as $key => $row) {
            $rowData[$key]['corporate_name_en'] = $row['corporate_name_en'];
            $rowData[$key]['corporate_name_ja'] = $row['corporate_name_ja'];
            $rowData[$key]['country'] = empty($row['country_id']) ? null : COUNTRY[$row['country_id']];
            $rowData[$key]['full_name'] = $row['full_name'];
            $rowData[$key]['full_name_ja'] = $row['full_name_ja'];
            $rowData[$key]['gender'] = isset($row['gender']) ? ($row['gender'] == 1 ? '男性' : '女性') : null;
            $rowData[$key]['date_of_birth'] = Carbon::parse($row['date_of_birth'])->format('Y年m月d日');
            $rowData[$key]['work_form'] = empty($row['work_form']) ? null : HRS_WORK_FORM_TEXT[$row['work_form']];
            $rowData[$key]['preferred_working_hours'] = $row['preferred_working_hours'];
            $rowData[$key]['japanese_level'] = LanguageRequirement::find($row['japanese_level'])->name;
            $rowData[$key]['final_education_date'] = Carbon::parse($row['final_education_date'])->format('Y年m月');
            $rowData[$key]['final_education_classification'] = HR_FINAL_EDUCATION_TEXT[$row['final_education_classification']];
            $rowData[$key]['final_education_degree'] = HR_EDUCATION_DEGREES[$row['final_education_degree']];
            $rowData[$key]['major_classification'] = $row['job_type'];
            $rowData[$key]['middle_classification'] = $row['job_info'];

            for($i = 0; $i < $max; $i++) {
                if(isset($row['mainJobs'][$i])) {
                    $dateFrom = Carbon::parse($row['mainJobs'][$i]['main_job_career_date_from'])->format('Y年m月');
                    $dateTo = Carbon::parse($row['mainJobs'][$i]['main_job_career_date_to'])->format('Y年m月');
                    $job_type = JobType::query()->find($row['mainJobs'][$i]['department_id']);
                    $job_info = JobInfo::query()->find($row['mainJobs'][$i]['job_id']);
                    $rowData[$key]['main_job_date_' . $i] = $dateFrom .'〜'. $dateTo;
                    $rowData[$key]['main_job_department_' . $i] = $job_type['name_ja'];
                    $rowData[$key]['main_job_title_' . $i] = $job_info['name_ja'];
                    $rowData[$key]['main_job_detail_' . $i] = $row['mainJobs'][$i]['detail'];
                } else {
                    $rowData[$key]['main_job_date_' . $i] = null;
                    $rowData[$key]['main_job_department_' . $i] = null;
                    $rowData[$key]['main_job_title_' . $i] = null;
                    $rowData[$key]['main_job_detail_' . $i] = null;
                }
            }

            $rowData[$key]['personal_pr_special_notes'] = $row['personal_pr_special_notes'];
            $rowData[$key]['remarks'] = $row['remarks'];
            $rowData[$key]['telephone_number'] = $row['telephone_number'];
            $rowData[$key]['mobile_phone_number'] = $row['mobile_phone_number'];
            $rowData[$key]['mail_address'] = $row['mail_address'];
            $rowData[$key]['address_city'] = $row['address_city'];
            $rowData[$key]['address_district'] = $row['address_district'];
            $rowData[$key]['address_number'] = $row['address_number'];
            $rowData[$key]['address_other'] = $row['address_other'];
            $rowData[$key]['hometown_city'] = $row['hometown_city'];
            $rowData[$key]['home_town_district'] = $row['home_town_district'];
            $rowData[$key]['home_town_number'] = $row['home_town_number'];
            $rowData[$key]['home_town_other'] = $row['home_town_other'];
        }

        $headers = [
            '団体名', '団体名（日本語）', '国', '氏名', '氏名（ﾌﾘｶﾞﾅ）', '性別', '生年月日', '勤務形態', '勤務形態（非常勤）', '日本語レベル',
            '最終学歴（年月）', '最終学歴（区分）', '最終学歴（学位）', '最終学歴（学科）大分類', '最終学歴（学科）中分類'
        ];

        for ($i = 1; $i <= $max; $i++) {
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（年月）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（所属）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（職名）';
            $headers[] = '主な職務経歴' . mb_chr(0x245F + $i) . '（詳細）';
        }
        $headers = array_merge($headers, [
            '自己PR・特記事項', '備考', '連絡先電話番号', '携帯電話番号', 'メールアドレス', '現住所（市）', '現住所（町）',
            '現住所（番地）', '現住所（その他）', '出身地住所（市）', '出身地住所（町）', '出身地住所（番地）', '出身地住所（その他）'
        ]);

        return Common::exportCSV($rowData, $headers, 'HR_list');
    }

    public function checkFileImport($request)
    {
        try {
            $file = UploadFile::where('id', $request->file_id)->first();
            $file->file_model = HR::class;
            $file->save();
            $path = $file->file_path;
            $data_error = [];
            $result = [];
            $array_success = [];
            $key_data_import = 'HR_IMPORT_' . $file->id;

            foreach (Common::convertCsvToArray($path, 200) as $key => $data) {
                if (!$data) {
                    return false;
                }
                if(isset($data['error']) && $data['error'] != null) {
                    return ['error_file' => $data['error']];
                }
                foreach ($data as $index => $item) {
                    $array = explode(',', implode('>', $item));
                    $array = array_map(function ($x){
                        return preg_replace('/(^")|("$)/', '', $x);
                    },$array);

                    $attribute = $this->processData($array, $data_error);
                    if(!$attribute) {
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

    public function processData($array, &$data_error) {
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
            Hr::PERSONAL_PR_SPECIAL_NOTES => empty($array[$count-13]) ? null : $array[$count-13],
            Hr::REMARKS => empty($array[$count-12]) ? null : $array[$count-12],
            Hr::TELEPHONE_NUMBER => empty($array[$count-11]) ? null : $array[$count-11],
            Hr::MOBILE_PHONE_NUMBER => empty($array[$count-10]) ? null : $array[$count-10],
            Hr::MAIL_ADDRESS => $array[$count-9],
            Hr::ADDRESS_CITY => empty($array[$count-8]) ? null : $array[$count-8],
            Hr::ADDRESS_DISTRICT => empty($array[$count-7]) ? null : $array[$count-7],
            Hr::ADDRESS_NUMBER => empty($array[$count-6]) ? null : $array[$count-6],
            Hr::ADDRESS_OTHER => empty($array[$count-5]) ? null : $array[$count-5],
            Hr::HOMETOWN_CITY => empty($array[$count-4]) ? null : $array[$count-4],
            Hr::HOME_TOWN_DISTRICT => empty($array[$count-3]) ? null : $array[$count-3],
            Hr::HOME_TOWN_NUMBER => empty($array[$count-2]) ? null : $array[$count-2],
            Hr::HOME_TOWN_OTHER => empty($array[$count-1]) ? null : $array[$count-1],
        ];
        $mainJobs = [];
        $flag = false;

        for($i = 15; $i < $count-13; $i += 4) {
            if($array[$i] && $array[$i+1] && $array[$i+2] && $array[$i+3]) {
                $flag = true;
                $date = explode('〜', $array[$i]);
                $is_exist = $this->checkJob(JOB_TYPE_COL_1, $array[$i+1], $array[$i+2], '主な職務経歴の所属の項目が存在しません。', '主な職務経歴の職名の項目が存在しません。', $attribute, $data_error);
                if(!$is_exist) {
                    continue;
                }

                $values = [$array[$i+3], $array[$count-13], $array[$count-12]];
                if (!$this->checkLenght($values, 2000, $attribute, $data_error)) {
                    continue;
                }

                if (!$this->checkDate($date[0], 'Y年m月', $attribute, $data_error) ||
                    !$this->checkDate($date[1], 'Y年m月', $attribute, $data_error)) {
                    continue;
                }
                $mainJob = [
                    'main_job_career_date_from' => Carbon::createFromFormat('Y年m月', $date[0])->format('Y-m'),
                    'main_job_career_date_to' => Carbon::createFromFormat('Y年m月', $date[1])->format('Y-m'),
                    'department_id' => $is_exist['job_type_id'],
                    'job_id' => $is_exist['job_info_id'],
                    'detail' => $array[$i+3]
                ];
                array_push($mainJobs, $mainJob);
            }
        }

        $attribute['main_jobs'] = $mainJobs;

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
            '主な職務経歴' => $flag,
        ];
        foreach ($columns as $key => $value) {
            if(!$value) {
                $attribute['message'] = '必須項目の '.$key.' が入力されていません。';
                array_push($data_error, (object)$attribute);
                return false;
            }
        }

        $values = [
            $array[3], $array[4], $array[$count-9], $array[$count-8], $array[$count-7], $array[$count-6],
            $array[$count-5], $array[$count-4], $array[$count-3], $array[$count-2], $array[$count-1]
        ];
        if (!$this->checkLenght($values, 50, $attribute, $data_error)) {
            return false;
        }

        if ($array[0] && $array[1]) {
            $org = HrOrganization::where(HrOrganization::CORPORATE_NAME_EN, $array[0])
                ->where(HrOrganization::CORPORATE_NAME_JA, $array[1])
                ->first();
            if (!$org) {
                $attribute['message'] = '団体名、団体名（日本語）が存在しません。';
                array_push($data_error, (object)$attribute);
                return false;
            }
            $attribute[Hr::HR_ORGANIZATION_ID] = $org->id;
        }

        if ($array[6]){
            if(!$this->checkDate($array[6], 'Y年m月d日', $attribute, $data_error)) {
                return false;
            }
            $attribute[HR::DATE_OF_BIRTH] = Carbon::createFromFormat('Y年m月d日', $array[6])->format('Y-m-d');
        }

        if($array[7]) {
            if($this->getIndex($array[7], HRS_WORK_FORM_TEXT, '勤務形態の項目が存在しません。', $attribute, $data_error, Hr::WORK_FORM)) {
                return false;
            }
        }

        if ($array[8]) {
            if($array[8]<1 || $array[8]>99) {
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

        if ($array[10]){
            if(!$this->checkDate($array[10], 'Y年m月', $attribute, $data_error)) {
                return false;
            }
            $attribute[HR::FINAL_EDUCATION_DATE] = Carbon::createFromFormat('Y年m月', $array[10])->format('Y-m');
        }

        if ($array[11]) {
            if($this->getIndex($array[11], HR_FINAL_EDUCATION_TEXT, '最終学歴（区分）の項目が存在しません。', $attribute, $data_error, Hr::FINAL_EDUCATION_CLASSIFICATION))
                return false;
        }

        if ($array[12]) {
            if($this->getIndex($array[12], HR_EDUCATION_DEGREES, '最終学歴（学位）の項目が存在しません。', $attribute, $data_error, Hr::FINAL_EDUCATION_DEGREE))
                return false;
        }

        if ($array[13] && $array[14]) {
            $is_exist = $this->checkJob(JOB_TYPE_COL_2, $array[13], $array[14], '最終学歴（学科）の大分類の項目が存在しません。', '最終学歴（学科）の中分類の項目が存在しません。', $attribute, $data_error);
            if(!$is_exist) {
                return false;
            }
            $attribute[Hr::MAJOR_CLASSIFICATION_ID] = $is_exist['job_type_id'];
            $attribute[Hr::MIDDLE_CLASSIFICATION_ID] = $is_exist['job_info_id'];
        }
        return $attribute;
    }

    public function getIndex($value, $array, $msg, &$attribute, &$data_error, $item) {
        $key = array_search($value, $array);
        if (!$key) {
            $attribute['message'] = $msg;
            array_push($data_error, (object)$attribute);
            return false;
        }
        $attribute[$item] = $key;
    }

    public function checkJob($type, $jobTypeName, $jobInfoName, $msg1, $msg2, &$attribute, &$data_error) {
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

    public function checkLenght($values, $max, &$attribute, &$data_error) {
        foreach ($values as $str) {
            if(strlen($str) > $max) {
                $attribute['message'] = $str . ' が ' . $max . ' 文字を超えています。';
                array_push($data_error, (object)$attribute);
                return false;
            }
        }
        return true;
    }

    public function checkDate($date, $format, &$attribute, &$data_error) {
        $dateObj = \DateTime::createFromFormat($format, $date);
            if($dateObj === false || $dateObj->format($format) !== $date) {
                $attribute['message'] = $date . ' 日付のフォーマットが正しくありません。'.$format.' のフォーマットで入力してください。 ';
                array_push($data_error, (object)$attribute);
                return false;
            }
        return true;
    }

    public function importCSV($request)
    {
        try {
            $key_data_import = 'HR_IMPORT_' . $request->file_id;
            $data = Cache::get($key_data_import);
            if (!$data){
                return false;
            }
            foreach (array_chunk($data['success'], 100) as $value){
                foreach ($value as $key => $attributes){
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
    public function hide(HRRequest $request){
        try {
            $HrId = $request->ids;
            $arrayListIHr = array_unique($HrId);
            $updateStatus = HR::query()->whereIn('id', $arrayListIHr)->delete();
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }
}
