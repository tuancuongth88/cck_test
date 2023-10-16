<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{

    protected $model = Entry::class;
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

       $work_id = Work::factory()->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => Company::query()->first()->id,
            Work::USER_ID => $user_company->id
        ])->id;
        $hr_id = HR::factory()->create()->id;
        HRMainJobCareer::factory()->create([HRMainJobCareer::HRS_ID =>$hr_id]);
        return [
            Entry::ENTRY_CODE => rand(10000000, 99999999),
            Entry::HR_ID => $hr_id,
            Entry::WORK_ID => $work_id,
            Entry::REMARKS => $this->faker->text(30),
            Entry::DISPLAY => 'on',
            Entry::STATUS => ENTRY_STATUS_REQUESTING,
            Entry::REQUEST_DATE => Carbon::now()->format('Y-m-d')
        ];
    }
}
