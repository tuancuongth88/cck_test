<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\HR;
use App\Rules\EntryRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class EntryRequest extends FormRequest
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
            $this->changeFormat(),
            $this->container->call([$this, 'rules']),
            $this->messages()
        );
    }

    public function changeFormat()
    {
        $id = Request::route('id') ?? '';
        if ($id) {
            $this->merge([
                'id' => $id
            ]);
        }
        if ($this->has('items') && !is_array($this->input('items'))) {
            $this->merge([
                'items' => json_decode($this->input('items'), true)
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
            'items' => 'required|array',
            'items.*.work_id' => 'required|exists:works,id,deleted_at,NULL',
            'items.*.hr_id' => 'required|exists:hrs,id,status,1,deleted_at,NULL',
            'items.*.motivation_file_id' => 'required|exists:files,id,item_type,' . MOTIVATION_FILE . ',item_id,NULL,deleted_at,NULL',
            'items.*.recommendation_file_id' => 'nullable|exists:files,id,item_type,' . RECOMMENDATION_FILE . ',item_id,NULL,deleted_at,NULL',
            'items.*.other_files' => 'nullable|array',
            'items.*.other_files.*' => 'required|exists:files,id,item_type,' . OTHER_FILE . ',item_id,NULL,deleted_at,NULL',
            'items.*.remarks' => 'nullable|string|max:2000'
        ];
        switch (Route::getCurrentRoute()->getActionMethod()) {
            case 'hide':
                return [
                    'ids' => ['required','array',new EntryRule()],
                    'ids.*' => 'required',
                ];
            case 'updateStatus':
                $listStatus = array_keys(array_filter(ENTRY_STATUS_TEXTS, function($k) {
                    return $k != ENTRY_STATUS_REQUESTING;
                }, ARRAY_FILTER_USE_KEY));
                return [
                    'id' => 'required',
                    'status' => 'required|in:' . implode(',', $listStatus),
                    'note' => 'nullable|string|max:1000'
                ];
            case 'index':
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
                    'middle_class.*' => 'required|exists:job_info,id',
                    'main_job_ids' => 'nullable|array',
                    'main_job_ids.*' => 'required|exists:hrs_main_job_career,id,deleted_at,NULL',
                    'field' => 'nullable|in:id,entry_code,full_name,work_title,request_date,updated_at,status',
                    'sort_by' => 'required_unless:field,null|string|in:asc,desc'
                ];
            case 'update':
                return $rules;
            case 'store':
                return $rules;
            default:
                return [];
          }
    }

    public function messages()
    {
        return [
            'hr_org_id.*' => trans('api.entry.hrs.invalid'),
            'gender.*' => trans('api.entry.gender.invalid'),
            'age_from.numeric' => trans('api.entry.age.from.numeric'),
            'age_from.min' => trans('api.entry.age.from.numeric.min'),
            'age_from.max' => trans('api.entry.age.from.numeric.max'),
            'age_to.numeric' => trans('api.entry.age.to.numeric'),
            'age_to.min' => trans('api.entry.age.to.numeric.min'),
            'age_to.max' => trans('api.entry.age.to.numeric.max'),
            'edu_date_from.date_format' => trans('api.entry.edu.date_from.format'),
            'edu_date_to.date_format' => trans('api.entry.edu.date_to.format'),
            'edu_date_to.greater_than' => trans('api.entry.edu.date_to.greater_than'),
            'edu_class.*' => trans('api.entry.edu.edu_class.array'),
            'edu_degree.*' => trans('api.entry.edu.edu_degree.array'),
            'work_forms.*' => trans('api.entry.work.forms.array'),
            'work_hour.*' => trans('api.entry.work.hour.array'),
            'japan_levels.*' => trans('api.entry.japan.levels'),
            'middle_class.*' => trans('api.entry.middle.class.array'),
            'main_job_ids.*' => trans('api.entry.main_job_ids.invalid'),
            'field.*' => trans('api.field.search'),
            'sort_by.*' => trans('api.sort_by.search'),
            'ids.*' => trans('api.entry.ids.array'),
        ];
    }
}
