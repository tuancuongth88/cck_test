<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\HR;
use App\Rules\CheckWorkIdRule;
use App\Rules\OfferRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (Route::getCurrentRoute()->getActionMethod()) {
            case 'index':
                return $this->getCustomRule();
            case 'update':
                return $this->getCustomRule();
            case 'store':
                return $this->getCustomRule();
            case 'removeOffer':
                return $this->getCustomRule();
            case 'detail':
                return $this->getCustomRule();
            case 'updateStatus':
                return $this->getCustomRule();
            default:
                return [];
        }
    }

    public function getCustomRule()
    {
        if (Route::getCurrentRoute()->getActionMethod() == 'index') {
            Validator::extend('greater_than', function ($attribute, $value, $parameters) {
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
            Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
                return str_replace('_', ' ', 'The ' . $attribute . ' must be greater than the ' . $params[0]);
            });
            $ageFrom = request('age_from') ?? '';
            $eduDateFrom = request('edu_date_from') ?? '';
            return [
                'hr_org_id' => 'nullable|exists:hr_organization,id',
                'gender' => 'nullable|array',
                'gender.*' => 'required|in:' . implode(',', [HRS_GENDER_MALE, HRS_GENDER_FEMALE]),
                'age_from' => 'nullable|numeric|min:18|max:60',
                'age_to' => ['nullable', 'numeric', 'greater_than:age_from,' . $ageFrom],
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
                'middle_class.*' => 'nullable|exists:job_info,id',
                'main_job_ids' => 'nullable|array',
                'main_job_ids.*' => 'nullable|exists:hrs_main_job_career,id,deleted_at,NULL',
                'field' => 'nullable|in:id,offer_date,full_name,work_title,status',
                'sort_by' => 'required_unless:field,null|string|in:asc,desc'
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'update') {
            return [
                'id' => 'required',
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'detail') {
            return [

            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'updateStatus') {
            return [
                'status' => ['required',Rule::in([OFFER_STATUS_CONFIRM,OFFER_STATUS_DECLINE])],
                'note' => 'nullable|string|max:1000',
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'store') {
            return [
                'hr_id' => 'required|exists:hrs,id,status,' . HRS_STATUS_PUBLIC,
                'work_id' => ['required', 'exists:works,id,status,' . WORK_STATUS_RECRUITING, new CheckWorkIdRule()]
            ];
        }
        if (Route::getCurrentRoute()->getActionMethod() == 'removeOffer') {
            return [
                'ids' => ['required','array',new OfferRule()],
                'ids.*' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'hr_org_id.*' => trans('api.offer.hrs.invalid'),
            'gender.*' => trans('api.offer.gender.invalid'),
            'age_from.numeric' => trans('api.offer.age.from.numeric'),
            'age_from.min' => trans('api.offer.age.from.numeric.min'),
            'age_from.max' => trans('api.offer.age.from.numeric.max'),
            'age_to.numeric' => trans('api.offer.age.to.numeric'),
            'age_to.min' => trans('api.offer.age.to.numeric.min'),
            'age_to.max' => trans('api.offer.age.to.numeric.max'),
            'edu_date_from.date_format' => trans('api.offer.edu.date_from.format'),
            'edu_date_to.date_format' => trans('api.offer.edu.date_to.format'),
            'edu_date_to.greater_than' => trans('api.offer.edu.date_to.greater_than'),
            'edu_class.*' => trans('api.offer.edu.edu_class.array'),
            'edu_degree.*' => trans('api.offer.edu.edu_degree.array'),
            'work_forms.*' => trans('api.offer.work.forms.array'),
            'work_hour.*' => trans('api.offer.work.hour.array'),
            'japan_levels.*' => trans('api.offer.japan.levels'),
            'middle_class.*' => trans('api.offer.middle.class.array'),
            'main_job_ids.*' => trans('api.offer.main_job_ids.invalid'),
            'field.*' => trans('api.field.search'),
            'sort_by.*' => trans('api.sort_by.search'),
            'ids.*' => trans('api.offer.ids.array'),
            'id.*' => trans('api.offer.ids.array'),
            'status.*' => trans('api.offer.status'),
        ];
    }
}
