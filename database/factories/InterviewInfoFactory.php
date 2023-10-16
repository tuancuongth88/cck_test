<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\InterviewInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\In;

class InterviewInfoFactory extends Factory
{
    protected $model = InterviewInfo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $time = [
            [
                "date" => Carbon::now()->addDays(1)->format('Y-m-d'),
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => Carbon::now()->addDays(2)->format('Y-m-d'),
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => Carbon::now()->addDays(3)->format('Y-m-d'),
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => Carbon::now()->addDays(4)->format('Y-m-d'),
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => Carbon::now()->addDays(5)->format('Y-m-d'),
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
        ];
        return [
            InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE,
            InterviewInfo::INTERVIEW_ID => Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT])->id,
            InterviewInfo::CALENDAR_INTERVIEW => json_encode($time),
            InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_TEMPORARY,
            InterviewInfo::INTERVIEW_NUMBER => INTERVIEW_INFO_NUMBER_FIRST
        ];
    }
}
