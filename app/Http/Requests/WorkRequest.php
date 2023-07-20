<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Work;
use App\Rules\ModelRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class WorkRequest extends FormRequest
{
    const MAX_LENGTH = 2000;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isUpdateStatusWork()) {
            return $this->updateStatusWorkRules();
        }

        if ($this->isUpdate()) {
            return $this->updateRules();
        }

        if ($this->isStore()) {
            return $this->storeRules();
        }

        return [];
    }

    public function messages()
    {
        return [
            Work::TITLE.'.required'                     => trans('api.work.title.required'),
            Work::MAJOR_CLASSIFICATION_ID.'.required'   => trans('api.work.major_classification_id.required'),
            Work::MIDDLE_CLASSIFICATION_ID.'.required'  => trans('api.work.middle_classification_id.required'),
            Work::COMPANY_ID.'.required'                => trans('api.work.company_id.required'),
            Work::APPLICATION_PERIOD_FROM.'.required'   => trans('api.work.application_period_from.required'),
            Work::APPLICATION_PERIOD_TO.'.required'     => trans('api.work.application_period_to.required'),
            Work::JOB_DESCRIPTION.'.required'           => trans('api.work.job_description.required'),
            Work::JOB_DESCRIPTION.'.max'           => trans('api.work.job_description.max'),
            Work::APPLICATION_CONDITION.'.required'     => trans('api.work.application_condition.required'),
            Work::APPLICATION_REQUIREMENT.'.required'   => trans('api.work.application_requirement.required'),
            'language_requirements.required'            => trans('api.work.language_requirements.required'),
            Work::OTHER_SKILL.'.required'               => trans('api.work.other_skill.required'),
            Work::OTHER_SKILL.'.max'               => trans('api.work.other_skill.max'),
            Work::PREFERRED_SKILL.'.max'           => trans('api.work.preferred_skill.max'),
            Work::FORM_OF_EMPLOYMENT.'.required'        => trans('api.work.form_of_employment.required'),
            Work::WORKING_TIME_FROM.'.required'         => trans('api.work.working_time_from.required'),
            Work::WORKING_TIME_TO.'.required'           => trans('api.work.working_time_to.required'),
            Work::VACATION.'.required'                  => trans('api.work.vacation.required'),
            Work::VACATION.'.max'                  => trans('api.work.vacation.max'),
            Work::EXPECTED_INCOME.'.required'           => trans('api.work.expected_income.required'),
            Work::CITY_ID.'.required'                   => trans('api.work.city_id.required'),
            Work::WORKING_PALACE_DETAIL.'.max'     => trans('api.work.working_palace_detail.max'),
            Work::TREATMENT_WELFARE.'.required'         => trans('api.work.treatment_welfare.required'),
            Work::TREATMENT_WELFARE.'.max'              => trans('api.work.treatment_welfare.max'),
            Work::COMPANY_PR_APPEAL.'.max'         => trans('api.work.company_pr_appeal.max'),
            Work::BONUS_PAY_RISE.'.required'            => trans('api.work.bonus_pay_rise.required'),
            Work::OVER_TIME.'.required'                 => trans('api.work.over_time.required'),
            Work::TRANSFER.'.required'                  => trans('api.work.transfer.required'),
            Work::INTERVIEW_FOLLOW.'.required'          => trans('api.work.interview_follow.required'),
        ];
    }

    private function storeRules()
    {
        $rules = [
            Work::TITLE                     => 'required|max:50',
            Work::MAJOR_CLASSIFICATION_ID   => 'required|exists:job_type,id',
            Work::MIDDLE_CLASSIFICATION_ID  => 'required|exists:job_info,id',
            Work::COMPANY_ID                => $this->getCompanyRule(),
            Work::APPLICATION_PERIOD_FROM   => 'required|date_format:Y-m-d|after:yesterday',
            Work::APPLICATION_PERIOD_TO     => 'required|date_format:Y-m-d|after:application_period_from',
            Work::JOB_DESCRIPTION           => "required|max:".self::MAX_LENGTH,
            Work::APPLICATION_CONDITION     => "required|max:".self::MAX_LENGTH,
            Work::APPLICATION_REQUIREMENT   => "required|max:".self::MAX_LENGTH,
            'language_requirements'         => 'required',
            Work::OTHER_SKILL               => "required|max:".self::MAX_LENGTH,
            Work::PREFERRED_SKILL           => "max:".self::MAX_LENGTH,
            Work::FORM_OF_EMPLOYMENT        => ['required', Rule::in([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE])],
            Work::WORKING_TIME_FROM         => 'required|date_format:H:i',
            Work::WORKING_TIME_TO           => 'required|date_format:H:i',
            Work::VACATION                  => "required|max:".self::MAX_LENGTH,
            Work::EXPECTED_INCOME           => 'required|numeric',
            Work::CITY_ID                   => ['required', new ModelRule(new City())],
            Work::WORKING_PALACE_DETAIL     => "max:".self::MAX_LENGTH,
            Work::TREATMENT_WELFARE         => "required|max:".self::MAX_LENGTH,
            Work::COMPANY_PR_APPEAL         => "max:".self::MAX_LENGTH,
            Work::BONUS_PAY_RISE            => ['required', Rule::in([WORK_CONFIRM_YES, WORK_CONFIRM_NO])],
            Work::OVER_TIME                 => ['required', Rule::in([WORK_CONFIRM_YES, WORK_CONFIRM_NO])],
            Work::TRANSFER                  => ['required', Rule::in([WORK_CONFIRM_YES, WORK_CONFIRM_NO])],
            Work::INTERVIEW_FOLLOW          => ['required', Rule::in(array_keys(Work::$interviewFlow))],
        ];

        return $rules;
    }

    private function updateRules()
    {
        $rules = $this->storeRules();

        $rules[Work::COMPANY_ID] = $this->getCompanyRule();

        return $rules;
    }

    private function updateStatusWorkRules()
    {
        return [
            Work::STATUS => 'required|numeric|in:1,2,3'
        ];
    }

    private function getCompanyRule()
    {
        if (Auth::user()->type != COMPANY) {
            return 'required|exists:companies,id';
        }
        return '';
    }

    private function isUpdate()
    {
        return Route::getCurrentRoute()->getActionMethod() == 'update';
    }

    private function isStore()
    {
        return Route::getCurrentRoute()->getActionMethod() == 'store';
    }

    private function isUpdateStatusWork()
    {
        return Route::getCurrentRoute()->getActionMethod() == 'updateStatusWork';
    }
}
