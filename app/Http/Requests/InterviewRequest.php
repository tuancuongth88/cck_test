<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\HR;
use App\Models\Interview;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class InterviewRequest extends FormRequest
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

    public function changeFormat(): array
    {
        $id = intval($this->segment(3)) ?: '';
        if ($id) {
            $this->merge([
                'id' => $id
            ]);
        }
        return $this->all();
    }

    public function rules()
    {
        $action = Route::getCurrentRoute()->getActionMethod();
        if ($action == 'store') {
            return [
                'entry_id' => 'nullable|exists:entries,id,status,' . ENTRY_STATUS_REQUESTING . ',display,on,deleted_at,NULL',
                'offer_id' => 'nullable|required_without:entry_id|exists:offers,id,status,' . OFFER_STATUS_REQUESTING . ',display,on,deleted_at,NULL'
            ];
        }

        if ($action == 'hide') {
            return [
                'ids' => 'required|array',
                'ids.*' => 'required|exists:interviews,id,status_selection,' . INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE . ',display,on,deleted_at,NULL',
            ];
        }

        if ($action == 'index') {
            Validator::extend('greater_than', function ($attribute, $value, $parameters)
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
            Validator::replacer('greater_than', function ($message, $attribute, $rule, $params) {
                return str_replace('_', ' ', 'The '. $attribute .' must be greater than the ' .$params[0]);
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
                'field' => 'nullable|in:id,entry_code,interview_date,full_name,job_name,status_selection,status_interview_adjustment',
                'sort_by' => 'required_unless:field,null|string|in:asc,desc'
            ];
        }

        if ($action == 'setupCalendar') {
            $id = $this->route('id');
            $statusAdjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT;
            return [
                'id' => $this->getIdRules($statusAdjustment),
                'interview_type' => [
                    'required',
                    'in:1,2',
                    function ($attribute, $value, $fail) use ($id) {
                        if ($value == INTERVIEW_TYPE_GROUP) {
                            $item = Interview::find($id);

                            if (!$item->code || $item->status_selection >= INTERVIEW_STATUS_SELECTION_FIRST_PASS) {
                                $fail(trans('The selected interview type is invalid.'));
                            }
                        }
                    }
                ],
                'times' => 'required|array|size:1',
                'times.*' => 'required|array',
                'times.*.date' => 'required|date|date_format:Y-m-d|after:today',
                'times.*.start_time' => 'required|string|date_format:g',
                'times.*.start_time_at' => 'required|in:AM,PM',
                'times.*.expected_time' => 'required|in:30,60,90'
            ];
        }

        if ($action == 'confirmedCalendar') {
            $statusAdjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING;
            return [
                'id' => $this->getIdRules($statusAdjustment),
                'confirmed' => 'required|in:yes,no',
                'confirmed_time' => 'nullable|in:1,2,3,4,5,6'
            ];
        }

        if ($action == 'setupZoom') {
            $statusAdjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING;
            return [
                'id' => $this->getIdRules($statusAdjustment),
                'url_zoom' => 'required|string|max:50',
                'id_zoom' => 'required|string|max:50',
                'password_zoom' => 'required|string|max:50'
            ];
        }

        if ($action == 'review') {
            $id = $this->route('id');
            $statusAdjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED;
            return [
                'id' => $this->getIdRules($statusAdjustment),
                'reviewed' => 'required|in:yes,no',
                'option' => $this->getOptionRules($id),
                'goto' => [
                    'nullable',
                    'required_if:option,' . REVIEW_PASS,
                    'in:' . implode(',', array_keys(GOTOSTEPS))
                ]
            ];
        }

        return [];
    }

    private function getOptionRules($id)
    {
        return [
            'nullable',
            'required_if:reviewed,yes',
            'in:1,2,3',
            function ($attribute, $value, $fail) use ($id) {
                if ($value == REVIEW_PASS) {
                    $item = Interview::find($id);
                    $max = intval($item->work->interview_follow) ?? 0;
                    $currentInterview = $item->infors()->where('status', 1)->count();
                    if ($currentInterview >= 5 || $currentInterview >= $max) {
                        $fail(trans('The selected option is invalid.'));
                    }
                }
            }
        ];
    }

    private function getIdRules($statusAdjustment, $isRejected = false)
    {
        return [
            "required",
            "exists:interviews,id,status_interview_adjustment,$statusAdjustment,deleted_at,NULL",
            function ($attribute, $value, $fail) use ($isRejected) {
                $query = Interview::where('id', $value);
                    if (!$isRejected) {
                        $query->where('status_selection', '<=', INTERVIEW_STATUS_SELECTION_FOURTH_PASS);
                    } else {
                        $query->whereIn('status_selection', [
                            INTERVIEW_STATUS_SELECTION_FIFTH_PASS,
                            INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL,
                            INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE
                        ]);
                    }
                if (!$query->first()) {
                    $fail(trans('The selected id is invalid.'));
                }
            }
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute not null'
        ];
    }
}
