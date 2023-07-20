<?php

namespace Database\Factories;

use App\Models\HrOrganization;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrOrganizationFactory extends Factory
{
    protected $model = HrOrganization::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            HrOrganization::CORPORATE_NAME_EN => $this->faker->company,
            HrOrganization::CORPORATE_NAME_JA => $this->faker->company,
            HrOrganization::LICENSE_NO => $this->faker->randomNumber(8),
            HrOrganization::ACCOUNT_CLASSIFICATION => rand(1,4),
            HrOrganization::COUNTRY => $this->faker->randomElement(array_keys(COUNTRY)),
            HrOrganization::REPRESENTATIVE_FULL_NAME => $this->faker->name,
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => $this->faker->name,
            HrOrganization::REPRESENTATIVE_CONTACT => $this->faker->phoneNumber,
            HrOrganization::ASSIGNEE_CONTACT => $this->faker->phoneNumber,
            HrOrganization::POST_CODE => $this->faker->postcode,
            HrOrganization::PREFECTURES => $this->faker->state,
            HrOrganization::MUNICIPALITY => $this->faker->city,
            HrOrganization::NUMBER => $this->faker->address,
            HrOrganization::OTHER => $this->faker->name,
            HrOrganization::MAIL_ADDRESS => $this->faker->email,
            HrOrganization::URL => $this->faker->url,
            HrOrganization::CERTIFICATE_FILE => $this->faker->randomDigitNotNull,
            HrOrganization::STATUS => $this->faker->randomElement([2, 3, 4]),
        ];
    }
}
