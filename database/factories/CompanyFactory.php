<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            Company::COMPANY_NAME => $this->faker->name,
            Company::COMPANY_NAME_JP => $this->faker->name,
            Company::MAJOR_CLASSIFICATION => 1,
            Company::MIDDLE_CLASSIFICATION => rand(1,10),
            Company::POST_CODE => $this->faker->postcode,
            Company::PREFECTURES => $this->faker->text,
            Company::MUNICIPALITY => $this->faker->text,
            Company::NUMBER => $this->faker->text,
            Company::OTHER => $this->faker->text,
            Company::TELEPHONE_NUMBER => $this->faker->phoneNumber,
            Company::MAIL_ADDRESS => $this->faker->email,
            Company::URL => $this->faker->url,
            Company::JOB_TITLE => $this->faker->text,
            Company::FULL_NAME => $this->faker->text,
            Company::FULL_NAME_FURIGANA => $this->faker->text,
            Company::REPRESENTATIVE_CONTACT => $this->faker->phoneNumber,
            Company::ASSIGNEE_CONTACT => $this->faker->phoneNumber,
        ];
    }
}
