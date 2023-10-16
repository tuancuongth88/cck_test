<?php

namespace Database\Factories;

use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\JobInfo;
use App\Models\JobType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrMainJobCareerFactory extends Factory
{
    protected $model = HRMainJobCareer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $depart_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->pluck('id')->first();
        $job_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $depart_id)->inRandomOrder()->pluck('id')->first();
        $date = $this->faker->dateTimeBetween('-10 years', '-5 years');
        return [
            HRMainJobCareer::MAIN_JOB_CAREER_DATE_FROM => Carbon::parse($date)->format('Y-m-d'),
            HRMainJobCareer::MAIN_JOB_CAREER_DATE_TO => Carbon::parse($date)->addYear()->format('Y-m-d'),
            HRMainJobCareer::HRS_ID => HR::query()->first()->id,
            HRMainJobCareer::DEPARTMENT_ID => $depart_id,
            HRMainJobCareer::JOB_ID => $job_id,
            HRMainJobCareer::DETAIL => $this->faker->text(20),
        ];
    }
}
