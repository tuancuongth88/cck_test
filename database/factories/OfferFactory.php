<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\Offer;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OfferFactory extends Factory
{

    protected $model = Offer::class;
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
        $status = [OFFER_STATUS_REQUESTING, OFFER_STATUS_DECLINE, OFFER_STATUS_CONFIRM];
        $hr_id = HR::factory()->create()->id;
        HRMainJobCareer::factory()->count(2)->create([HRMainJobCareer::HRS_ID => $hr_id]);
        return [
            Offer::HR_ID => $hr_id,
            Offer::WORK_ID => $work->id,
            Offer::STATUS => $this->faker->randomElement($status),
            Offer::REQUEST_DATE => Carbon::now()->subMonth()->format('Y-m-d'),
            Offer::DISPLAY => 'on'
        ];
    }
}
