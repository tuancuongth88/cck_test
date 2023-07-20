<?php

namespace Database\Factories;

use App\Models\Company;
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
        $user_company = User::query()->where(User::TYPE, \COMPANY)->first();
        Company::firstOrCreate(
            [Company::USER_ID => $user_company->id],
            Company::factory()->make()->toArray()
        );

        $work = Work::factory()->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => Company::query()->first()->id,
            Work::USER_ID => $user_company->id]);
        $hr_id = HR::factory()->create()->id;
        HRMainJobCareer::factory()->count(2)->create([HRMainJobCareer::HRS_ID => $hr_id]);
        return [
            Interview::HR_ID => $hr_id,
            Interview::WORK_ID => $work->id,
        ];
    }
}
