<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    protected $model = Result::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $interview = Interview::query()->inRandomOrder()->first();
        if(!$interview)
            $interview = Interview::factory()->create();
        return [
            Result::INTERVIEW_ID => $interview->id,
            Result::HR_ID => $interview->hr_id,
            Result::WORK_ID => $interview->work_id,
            Result::CODE => $interview->code,
            Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_OFFER
        ];
    }
}
