<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\HR;
use App\Rules\PhoneRule;
use App\Rules\ValidateMainJobs;
use Enum\CountryEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class HRRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validator($factory)
    {
        return $factory->make(
            $this->changeFormat(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    public function changeFormat()
    {
        if ($this->has('main_jobs') && !is_array($this->input('main_jobs'))) {
            $this->merge([
                'main_jobs' => json_decode($this->input('main_jobs'), true)
            ]);
        }

        return $this->all();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
//            Hr::COUNTRY_ID => 'required|in:' . implode(',', array_keys(CountryEnum::COUNTRY)),
            Hr::HR_ORGANIZATION_ID => 'required|exists:hr_organization,id',
            Hr::FULL_NAME => 'required|string|max:50',
            Hr::FULL_NAME_JA => 'required|string|max:50',
            Hr::GENDER => 'nullable|in:' . HRS_GENDER_MALE . ',' . HRS_GENDER_FEMALE,
            Hr::DATE_OF_BIRTH => 'required|date|date_format:Y-m-d',
            Hr::WORK_FORM => 'nullable|in:' . implode(',', HRS_WORK_FORM),
            Hr::PREFERRED_WORKING_HOURS => 'nullable|numeric|min:1|max:99',
            Hr::JAPANESE_LEVEL => 'required|exists:language_requirements,id',
            Hr::FINAL_EDUCATION_DATE => 'required|date_format:Y-m',
            Hr::FINAL_EDUCATION_CLASSIFICATION => 'required|in:' . implode(',', HR_FINAL_EDUCATION),
            Hr::FINAL_EDUCATION_DEGREE => 'required|in:' . implode(',', array_keys(HR_EDUCATION_DEGREES)),
            Hr::MAJOR_CLASSIFICATION_ID => 'required|exists:job_type,id,type,'.JOB_TYPE_COL_2,
            Hr::MIDDLE_CLASSIFICATION_ID => 'required|exists:job_info,id',
            'main_jobs' => ['nullable', 'array', new ValidateMainJobs()],
            Hr::PERSONAL_PR_SPECIAL_NOTES => 'nullable|string|max:2000',
            Hr::REMARKS => 'nullable|string|max:2000',
//            Hr::TELEPHONE_NUMBER => ['nullable', new PhoneRule()],
//            Hr::MOBILE_PHONE_NUMBER => ['nullable', new PhoneRule()],
            Hr::MAIL_ADDRESS => 'required|email|max:50',
            Hr::ADDRESS_CITY => 'nullable|string|max:50',
            Hr::ADDRESS_DISTRICT => 'nullable|string|max:50',
            Hr::ADDRESS_NUMBER => 'nullable|string|max:50',
            Hr::ADDRESS_OTHER => 'nullable|string|max:50',
            Hr::HOMETOWN_CITY => 'nullable|string|max:50',
            Hr::HOME_TOWN_DISTRICT => 'nullable|string|max:50',
            Hr::HOME_TOWN_NUMBER => 'nullable|string|max:50',
            Hr::HOME_TOWN_OTHER => 'nullable|string|max:50',
            Hr::STATUS => 'nullable|in:1,2,3'
        ];
        switch (Route::getCurrentRoute()->getActionMethod()){
            case 'index':
            case 'download':
                Validator::extend('greater_than', function($attribute, $value, $parameters)
                {
                    if (isset($parameters[1]) && $parameters[1] > 0) {
                       $other = $parameters[1];
                       if (strtotime($other)) {
                           $other = strtotime($other);
                       }
                       if (strtotime($value)) {
                           $value = strtotime($value);
                       }
                       return intval($value) > intval($other);
                    } else {
                      return true;
                   }
                });
                Validator::replacer('greater_than', function($message, $attribute, $rule, $params) {
                    return str_replace('_', ' ' , 'The '. $attribute .' must be greater than the ' .$params[0]);
                });
                $ageFrom = request('age_from') ?? '';
                $eduDateFrom = request('edu_date_from') ?? '';
                return [
                    'hr_org_id' => 'nullable|exists:hr_organization,id',
                    'country_id' => 'nullable|in:' . implode(',', array_keys(CountryEnum::COUNTRY)),
                    'gender' => 'nullable|array',
                    'gender.*' => 'required|in:' . implode(',', [HRS_GENDER_MALE, HRS_GENDER_FEMALE]),
                    'age_from' => 'nullable|numeric',
                    'age_to' => ['nullable', 'numeric', 'gte:age_from,' . $ageFrom],
                    'edu_date_from' => 'nullable|string|date_format:Y-m',
                    'edu_date_to' => ['nullable', 'date_format:Y-m', 'greater_than:edu_date_from,' . $eduDateFrom],
                    'edu_class' => 'nullable|array',
                    'edu_class.*' => 'required|in:' . implode(',', HR_FINAL_EDUCATION),
                    'edu_degree' => 'nullable|array',
                    'edu_degree.*' => 'required|in:1,2',
                    'work_forms' => 'nullable|array',
                    'work_forms.*' => 'required|in:' . implode(',', HRS_WORK_FORM),
                    'work_hour' => 'nullable',
                    'japan_levels' => 'nullable|array',
                    'japan_levels.*' => 'required|exists:language_requirements,id',
                    'middle_class' => 'nullable|array',
                    'middle_class.*' => 'required|exists:job_info,id',
                    'main_job_ids' => 'nullable|array',
                    'main_job_ids.*' => 'required|exists:job_info,id',
                    'field' => 'nullable|in:id,full_name,date_of_birth,japanese_level,corporate_name_en,status',
                    'sort_by' => 'required_unless:field,null|string|in:asc,desc'
                ];
            case 'store':
            case 'update':
                return $rules;
            case 'updateFileHR':
                return [
                    'file_cv_id' => 'required|exists:files,id',
                    'file_job_id' => 'required|exists:files,id',
                    'file_others' => 'nullable|array',
                    'file_others.*.name' => 'required',
                    'file_others.*.file_id' => 'required|exists:files,id',
                    'file_others.*.file_path' => 'required',
                ];
            case 'checkFileImport':
                return [
                    'file_id' => 'required|exists:files,id'
                ];
            case 'importFile':
                return [
                    'file_id' => 'required|exists:files,id,file_model,'.HR::class
                ];
            case 'hide':
                return [
                    'ids' => ['required','array'],
                    'ids.*' => 'required',
                ];
            default:
                return [];
        }

    }

    public function attributes()
    {
        return [
//            HR::COUNTRY_ID => '国',
            Hr::HR_ORGANIZATION_ID => '団体名',
            Hr::FULL_NAME => '氏名',
            Hr::FULL_NAME_JA => '氏名（ﾌﾘｶﾞﾅ）',
            Hr::GENDER => '性別',
            Hr::DATE_OF_BIRTH => '生年月日',
            Hr::WORK_FORM => '勤務形態',
            Hr::PREFERRED_WORKING_HOURS => '希望勤務時間',
            Hr::JAPANESE_LEVEL => '日本語レベル',
            Hr::FINAL_EDUCATION_DATE => '最終学歴（年月）',
            Hr::FINAL_EDUCATION_CLASSIFICATION => '最終学歴（区分）',
            Hr::FINAL_EDUCATION_DEGREE => '最終学歴（学位）',
            Hr::MAJOR_CLASSIFICATION_ID => '大分類',
            Hr::MIDDLE_CLASSIFICATION_ID => '中分類',
            Hr::PERSONAL_PR_SPECIAL_NOTES => '自己PR・特記事項',
            Hr::REMARKS => '備考',
            Hr::TELEPHONE_NUMBER => '連絡先電話番号',
            Hr::MOBILE_PHONE_NUMBER => '携帯電話番号',
            Hr::MAIL_ADDRESS => 'メールアドレス',
            Hr::ADDRESS_CITY => '市',
            Hr::ADDRESS_DISTRICT => '町',
            Hr::ADDRESS_NUMBER => '番地',
            Hr::ADDRESS_OTHER => 'その他',
            Hr::HOMETOWN_CITY => '市',
            Hr::HOME_TOWN_DISTRICT => '町',
            Hr::HOME_TOWN_NUMBER => '番地',
            Hr::HOME_TOWN_OTHER => 'その他',
            'main_jobs' => '主な職務経歴'
        ];
    }

    public function messages()
    {
        return [
//            Hr::COUNTRY_ID.'required' => trans('api.hrs.company_id_not_found'),
//            Hr::COUNTRY_ID.'in' => trans('api.hrs.contry.in'),
            Hr::HR_ORGANIZATION_ID.'*' => trans('api.hrs.hr_organization'),
            Hr::FULL_NAME.'*' => trans('api.hrs.full_name'),
            Hr::FULL_NAME_JA.'*' => trans('api.hrs.full_name_ja'),
            Hr::GENDER.'required' => trans('api.hrs.gender.required'),
            Hr::GENDER.'in' =>trans('api.hrs.gender.in'),
            Hr::DATE_OF_BIRTH.'*' => trans('api.hrs.date_of_birth'),
            Hr::WORK_FORM.'required' => trans('api.hrs.work_form.required'),
            Hr::WORK_FORM.'in' => trans('api.hrs.work_form.in'),
            Hr::PREFERRED_WORKING_HOURS.'*' => trans('api.hrs.preferred_working_hours'),
            Hr::JAPANESE_LEVEL.'required' => trans('api.hrs.japanese_level.required'),
            Hr::JAPANESE_LEVEL.'exists' => trans('api.hrs.japanese_level.exists'),
            Hr::FINAL_EDUCATION_DATE.'*' => trans('api.hrs.final_education_date'),
            Hr::FINAL_EDUCATION_CLASSIFICATION.'*' => trans('api.hrs.final_education_classification'),
            Hr::FINAL_EDUCATION_DEGREE.'*' => trans('api.hrs.final_education_degree'),
            Hr::MAJOR_CLASSIFICATION_ID.'*' => trans('api.hrs.major_classification_id'),
            Hr::MIDDLE_CLASSIFICATION_ID.'*' =>trans('api.hrs.middle_classification_id'),
            Hr::PERSONAL_PR_SPECIAL_NOTES.'*' => trans('api.hrs.personal_pr_special_notes'),
            Hr::REMARKS.'*' => trans('api.hrs.remarks'),
          /*  Hr::TELEPHONE_NUMBER.'*' => '',
            Hr::MOBILE_PHONE_NUMBER => '',*/
            Hr::MAIL_ADDRESS.'required' => trans('api.hrs.mail_address.required'),
            Hr::MAIL_ADDRESS.'email' => trans('api.hrs.mail_address.email'),
            Hr::MAIL_ADDRESS.'max' => trans('api.hrs.mail_address.max'),
            Hr::ADDRESS_CITY.'*' => trans('api.hrs.address_city'),
            Hr::ADDRESS_DISTRICT.'*' => trans('api.hrs.address_district'),
            Hr::ADDRESS_NUMBER.'*' => trans('api.hrs.address_number'),
            Hr::ADDRESS_OTHER.'*' => trans('api.hrs.address_other'),
            Hr::HOMETOWN_CITY.'*' => trans('api.hrs.hometown_city'),
            Hr::HOME_TOWN_DISTRICT.'*' => trans('api.hrs.home_town_district'),
            Hr::HOME_TOWN_NUMBER.'*' => trans('api.hrs.home_town_number'),
            Hr::HOME_TOWN_OTHER.'*' => trans('api.hrs.home_town_other'),

            'file_cv_id.*' => trans('api.hrs.file_cv_id'),
            'file_job_id.*' => trans('api.hrs.file_job_id'),
            'file_others.*' =>  trans('api.hrs.file_others'),
            'file_id.*' => trans('api.hrs.file_id'),
            'ids.*' => trans('api.hrs.ids'),
            'main_jobs.required' =>trans('api.hrs.main_jobs.required'),
            'main_jobs.*.main_job_career_date_from' =>trans('api.hrs.main_jobs.main_job_career_date_from'),
            'main_jobs.*.to_now' => trans('api.hrs.main_jobs.to_now'),
            'main_jobs.*.main_job_career_date_to' => trans('api.hrs.main_jobs.main_job_career_date_to'),
            'main_jobs.*.department_id' => trans('api.hrs.main_jobs.department_id'),
            'main_jobs.*.job_id' => trans('api.hrs.main_jobs.job_id'),
            'main_jobs.*.detail' => trans('api.hrs.main_jobs.detail'),



            'hr_org_id.*' =>  trans('api.hrs.hr_organization'),
            'gender.in' => trans('api.hrs.gender.in'),

            'age_from.*' => trans('api.hrs.search.age_from'),
            'age_to.*' => trans('api.hrs.search.age_to'),
            'edu_date_from.*' => trans('api.hrs.search.edu_date_from'),
            'edu_date_to.*' => trans('api.hrs.search.edu_date_to'),
            'edu_class.*' => trans('api.hrs.search.edu_class'),
            'edu_degree.*' => trans('api.hrs.search.edu_degree'),
            'edu_course.*' => trans('api.hrs.search.edu_course'),
            'work_forms.*.required' =>  trans('api.hrs.work_form.required'),
            'work_forms.*.in' =>  trans('api.hrs.work_form.in'),
            'japan_levels.*.exists' =>  trans('api.hrs.japanese_level.exists'),
            'japan_levels.*.required' =>  trans('api.hrs.japanese_level.required'),
            'main_job_ids.*.required' => trans('api.hrs.main_jobs.job_id'),
            'main_job_ids.*.exists' => trans('api.hrs.main_jobs.job_id'),


            'middle_class.array' => trans('api.hrs.middle_class.array'),
            'middle_class.*.required' => trans('api.hrs.middle_class.required'),
            'middle_class.*.exists' => trans('api.hrs.middle_class.exists'),
            'field.*' =>trans('api.field.search'),
            'sort_by.*' => trans('api.sort_by.search'),
        ];
    }
}
