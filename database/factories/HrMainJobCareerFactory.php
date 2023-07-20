<?php

namespace Database\Factories;

use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\JobInfo;
use App\Models\JobType;
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
        return [
            HRMainJobCareer::HRS_ID => HR::query()->first()->id,
            HRMainJobCareer::DEPARTMENT_ID => $depart_id,
            HRMainJobCareer::JOB_ID => $job_id,
            HRMainJobCareer::DETAIL => $this->faker->text(20),
        ];
    }
}
