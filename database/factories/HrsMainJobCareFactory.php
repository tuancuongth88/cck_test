<?php

namespace Database\Factories;

use App\Models\HRMainJobCareer;
use App\Models\JobType;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrsMainJobCareFactory extends Factory
{

    protected $model = HRMainJobCareer::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $jobType = JobType::query()->where('type', MAJOR_CLASSIFICATION)->first();
        return [
            HRMainJobCareer::MAIN_JOB_CAREER_DATE_FROM  => $this->faker->date,
            HRMainJobCareer::MAIN_JOB_CAREER_DATE_TO    => $this->faker->date,
            HRMainJobCareer::DEPARTMENT_ID  => $jobType->id,
            HRMainJobCareer::JOB_ID => $jobType->jobInfo->id,
            HRMainJobCareer::DETAIL => $this->faker->text,
        ];
    }
}
