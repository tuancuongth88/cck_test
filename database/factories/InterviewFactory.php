<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\Interview;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\In;

class InterviewFactory extends Factory
{

    protected $model = Interview::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $entry = Entry::factory()->create();
        return [
            Interview::HR_ID => $entry->hr->id,
            Interview::WORK_ID => $entry->work->id,
            Interview::INTERVIEW_CODE => $entry->code,
            Interview::TYPE => INTERVIEW_TYPE_PRIVATE,
            Interview::STATUS_SELECTION => INTERVIEW_STATUS_SELECTION_DOC_PASS,
            Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT,
            Interview::DISPLAY => 'on'
        ];
    }
}
