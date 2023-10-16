<?php

namespace Database\Factories;

use App\Models\HrOrganization;
use App\Models\UploadFile;
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
        $code = $this->faker->randomElements(['+84 0', '+81 0'])[0];
        return [
            HrOrganization::CORPORATE_NAME_EN => $this->faker->name,
            HrOrganization::CORPORATE_NAME_JA => $this->faker->name,
            HrOrganization::LICENSE_NO => $this->faker->randomNumber(8),
            HrOrganization::ACCOUNT_CLASSIFICATION => rand(1,4),
            HrOrganization::COUNTRY => $this->faker->randomElement(array_keys(COUNTRY)),
            HrOrganization::REPRESENTATIVE_FULL_NAME => $this->faker->name,
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => $this->faker->name,
            HrOrganization::REPRESENTATIVE_CONTACT => $code . rand(0000000000, 9999999999),
            HrOrganization::ASSIGNEE_CONTACT => $code . rand(0000000000, 9999999999),
            HrOrganization::POST_CODE => $this->faker->postcode,
            HrOrganization::PREFECTURES => $this->faker->state,
            HrOrganization::MUNICIPALITY => $this->faker->city,
            HrOrganization::NUMBER => rand(1, 100),
            HrOrganization::OTHER => $this->faker->name,
            HrOrganization::MAIL_ADDRESS => $this->faker->email,
            HrOrganization::URL => $this->faker->url,
            HrOrganization::CERTIFICATE_FILE => UploadFile::factory()->create()->id,
            HrOrganization::STATUS => CONFIRM,
        ];
    }
}
