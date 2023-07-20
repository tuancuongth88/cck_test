<?php

namespace Database\Factories;

use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class HrFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = HR::class;

    public function definition()
    {
        $user = Auth::user();
        if ($user->type == HR) {
            $hrOrg = $user->hrOrganization;
            $userId = $user->id;
        } else {
            $userHr = User::factory()->create([User::TYPE => \HR])->id;
            $hrOrg = HrOrganization::factory()->create([HrOrganization::USER_ID => $userHr]);
            $userId = $userHr;
        }

        $name = $this->faker->name;
        $major_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
        $middle_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major_id)->inRandomOrder()->pluck('id')->first();
        return [
            HR::HR_ORGANIZATION_ID => $hrOrg->id,
            HR::USER_ID => $userId,
            HR::FULL_NAME => $name,
            HR::FULL_NAME_JA => $name,
            HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
            HR::CREATED_BY => $user->id,
            HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-50 years'),
            HR::FINAL_EDUCATION_DATE => $this->faker->dateTimeBetween('-20 years')->format('Y-m'),
            HR::FINAL_EDUCATION_CLASSIFICATION => $this->faker->randomElement(HR_FINAL_EDUCATION),
            HR::FINAL_EDUCATION_DEGREE => $this->faker->randomElement(array_keys(HR_EDUCATION_DEGREES)),
            HR::WORK_FORM => $this->faker->randomElement(HRS_WORK_FORM),
            HR::JAPANESE_LEVEL => LanguageRequirement::query()->inRandomOrder()->pluck('id')->first(),
            HR::MAJOR_CLASSIFICATION_ID => $major_id,
            HR::MIDDLE_CLASSIFICATION_ID => $middle_id,
        ];
    }
}
