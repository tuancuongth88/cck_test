<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFactory extends Factory
{
    protected $model = Work::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        return [
            Work::TITLE => $this->faker->name,
            Work::COMPANY_ID => Company::factory()->create()->id,
            Work::APPLICATION_PERIOD_FROM => Carbon::now()->format('Y-m-d'),
            Work::APPLICATION_PERIOD_TO => Carbon::now()->addMonth()->format('Y-m-d'),
            Work::JOB_DESCRIPTION => $this->faker->text,
            Work::APPLICATION_CONDITION => $this->faker->text,
            Work::APPLICATION_REQUIREMENT => $this->faker->text,
            Work::OTHER_SKILL => $this->faker->text(200),
            Work::FORM_OF_EMPLOYMENT => $this->faker->randomElement([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE]),
            Work::WORKING_TIME_FROM => Carbon::now()->format('H:i'),
            Work::WORKING_TIME_TO => Carbon::now()->addHour(8)->format('H:i'),
            Work::VACATION => $this->faker->text(200),
            Work::EXPECTED_INCOME => rand(100000, 999999),
            Work::CITY_ID => 1,
            Work::TREATMENT_WELFARE => $this->faker->text(100),
            Work::BONUS_PAY_RISE => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
            Work::OVER_TIME => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
            Work::TRANSFER => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
            Work::INTERVIEW_FOLLOW => rand(1, 5),
        ];
    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item){
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }
}
