<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Requests;

use App\Models\HR;
use App\Models\Interview;
use App\Rules\InterviewRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                'ids' => ['required','array',new InterviewRule()],
                'ids.*' => 'required',
            ];
        }
        if ($action == 'confirmedInterviewHrDecline') {
            return [
                'note' => 'required|string|max:2000'
            ];
        }
        if ($action == 'confirmedInterviewCompanyCancel') {
            return [
                'note' => 'nullable|string|max:2000'
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
                'age_from' => 'nullable|numeric',
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
                'main_job_ids.*' => 'nullable|exists:hrs_main_job_career,id,deleted_at,NULL',
                'field' => 'nullable|in:id,hr_id,code,interview_date,full_name,job_name,status_selection,status_interview_adjustment',
                'sort_by' => 'required_unless:field,null|string|in:asc,desc'
            ];
        }

        if ($action == 'setupCalendar') {
            return [
                'interview_type' => 'required','in:'.INTERVIEW_TYPE_GROUP.','.INTERVIEW_TYPE_PRIVATE,
                'times' => 'required|array|min:1|max:5',
                'times.*' => 'required|array',
                'times.*.date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
                'times.*.start_time' => 'required|string|date_format:H:i|after:7:59|before:22:01',
//                'times.*.start_time' => 'required|string|date_format:g:i',
//                'times.*.start_time_at' => 'required|in:AM,PM',
                'times.*.expected_time' => 'required|in:30,60,90'
            ];
        }

        if ($action == 'confirmedCalendar') {
            $statusAdjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING;
            return [
                'time' => ['required',Rule::in(CALENDAR_TIMELINE)],
            ];
        }

        if ($action == 'setupZoom') {
            return [
                'url_zoom' => 'required|string|max:50',
                'id_zoom' => 'required|string|max:50',
                'password_zoom' => 'required|string|max:50'
            ];
        }

        if ($action == 'review') {
            $timeDateOfferReview = Carbon::now()->addDay(6)->format('Y-m-d');
            return [
                'status' =>['required',Rule::in(INTERVIEW_STATUS_REVIEW)],
                'date_offer' => 'nullable|date|date_format:Y-m-d|after:'.$timeDateOfferReview,
                'action' => ['nullable',Rule::in(OPTION_PASS_OPERATION)],
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

    public function messages()
    {
        return [
            'interview_type.*' => trans('api.interview.create.calendar.type'),
            'times.required' => trans('api.interview.create.calendar.time.required'),
            'times.size' => trans('api.interview.create.calendar.time.size'),
            'times.*.date.*' => trans('api.interview.create.calendar.time.date'),
            'times.*.start_time.*' => trans('api.interview.create.calendar.time.start_time'),
//            'times.*.start_time_at.*' => trans('api.interview.create.calendar.time.start_time_at'),
            'times.*.expected_time.*' => trans('api.interview.create.calendar.time.expected_time'),
            'time.*' => trans('api.interview.confrim.calendar.time'),
            'url_zoom.*' => trans('api.interview.hr.url_zoom'),
            'id_zoom.*' => trans('api.interview.hr.id_zoom'),
            'password_zoom.*' => trans('api.interview.hr.password_zoom'),
            'date_offer.*' => trans('api.interview.review.date'),
            'status.*' => trans('api.interview.review.status'),
            'action.*' => trans('api.interview.review.action'),
            'hr_org_id.*' => trans('api.interview.hrs.invalid'),
            'gender.*' => trans('api.interview.gender.invalid'),
            'age_from.numeric' => trans('api.interview.age.from.numeric'),
            'age_from.min' => trans('api.interview.age.from.numeric.min'),
            'age_from.max' => trans('api.interview.age.from.numeric.max'),
            'age_to.numeric' => trans('api.interview.age.to.numeric'),
            'age_to.min' => trans('api.interview.age.to.numeric.min'),
            'age_to.max' => trans('api.interview.age.to.numeric.max'),
            'edu_date_from.date_format' => trans('api.interview.edu.date_from.format'),
            'edu_date_to.date_format' => trans('api.interview.edu.date_to.format'),
            'edu_date_to.greater_than' => trans('api.interview.edu.date_to.greater_than'),
            'edu_class.*' => trans('api.interview.edu.edu_class.array'),
            'edu_degree.*' => trans('api.interview.edu.edu_degree.array'),
            'work_forms.*' => trans('api.interview.work.forms.array'),
            'work_hour.*' => trans('api.interview.work.hour.array'),
            'japan_levels.*' => trans('api.interview.japan.levels'),
            'middle_class.*' => trans('api.interview.middle.class.array'),
            'main_job_ids.*' => trans('api.interview.main_job_ids.invalid'),
        ];
    }
}
