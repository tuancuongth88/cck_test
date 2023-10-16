<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SelectEntryTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        {
            $this->browse(function ($browser) {
                $this->fakeDataSelectEntry($browser);
                $this->login();
                $this->caseListCompany($browser);
            });
        }
    }

    private function caseListCompany($browser)
    {
        $fileMovition1 = 'fileMovition1.xlsx';
        $fileOther1 = 'fileOther1.xlsx';
        $fileMovition2 = 'fileMovition2.xlsx';
        $fileOther2 = 'fileOther2.xlsx';
        $browser->pause(3000)
            ->mouseover('.display-menu > li:nth-child(3)')->pause(2000)
            ->assertSee('求人一覧(人材用)')
            ->visit('job-search/list')->pause(3000)
            ->assertSee('求人情報検索一覧')
            ->assertSee('詳細を見る')->pause(1000)
            ->click('div.job-list-hr-job-list__list  div  div:nth-child(1) .job-detail')->pause(5000);
//            ->assertSee('求人情報詳細')->pause(1000)

        $browser->assertSee('求人情報')->pause(1000)
            ->scrollTo('div.display-user-management-list div.card-deck:nth-child(1) .list-group-body div:nth-child(12)')->pause(3000)
            ->scrollTo('div.display-user-management-list div.card-deck:nth-child(2) .list-group-body div:nth-child(1)')->pause(3000)
            ->assertSee('エントリーに進む')->pause(1000)
            ->click('.btn-move-to-entry')->pause(3000)
            ->assertSee('求人エントリー')->pause(1000)
            ->assertSee('エントリー人材選択')->pause(1000)
            ->assertSee('氏名')->pause(1000)
            ->assertSee('人材を選ぶ')->pause(1000)
            ->click('.entry-hr-select-btn')->pause(3000)
            ->assertSee('人材を選ぶ')->pause(3000);

        $browser->scrollTo('.modal-select-hr-search .item-search-vs-condition:nth-child(8)')->pause(2000)
            ->scrollTo('.modal-select-hr-search .item-search-vs-condition:nth-child(12) .btn-search-vs-conditions')->pause(4000)
            ->scrollTo('.modal-select-hr-search .item-search-vs-condition:nth-child(8)')->pause(2000)
            ->check('.modal-select-hr-list .select-hr-item:nth-child(1) .custom-control-label')->pause(2000)
            ->check('.modal-select-hr-list .select-hr-item:nth-child(2) .custom-control-label')->pause(2000)
            ->scrollTo('.modal-select-hr-search .item-search-vs-condition:nth-child(8)')->pause(2000)
            ->click('.btn-confirm')->pause(5000)
            ->click('.btn-move-to-entry')->pause(5000)
            ->assertSee('求人情報 エントリー');

        $browser->attach('.form-page-area .form-item-row:nth-child(1) .flex-column .file-upload-area:nth-child(2) input', storage_path('app/public/dusk/' . $fileMovition1))->pause(10000)
            ->attach('.form-page-area .form-item-row:nth-child(1) .flex-column .file-upload-area:nth-child(5) input', storage_path('app/public/dusk/' . $fileOther1))->pause(10000)
            ->scrollTo('.form-page-area .form-item-row:nth-child(2) .flex-column .file-upload-area:nth-child(2)')->pause(3000)
            ->attach('.form-page-area .form-item-row:nth-child(2) .flex-column .file-upload-area:nth-child(2) input', storage_path('app/public/dusk/' . $fileMovition2))->pause(10000)
            ->attach('.form-page-area .form-item-row:nth-child(2) .flex-column .file-upload-area:nth-child(5) input', storage_path('app/public/dusk/' . $fileOther2))->pause(10000)
            ->click('.job-detail-for-hr-form-btn .btn-move-to-entry:nth-child(2)')->pause(3000)
            ->click('.job-detail-for-hr-form-btn .btn-move-to-entry:nth-child(2)')->pause(50000)
            ->assertSee('求人情報 エントリー')
            ->assertSee('エントリーを送信しました。')
            ->assertSee('企業からの連絡をお待ちください。')
            ->pause(2000);

        $browser->visit('/matching-management')->pause(3000000);
    }
    private function fakeDataSelectEntry($browser){
        $this->faker = \Faker\Factory::create();
        $this->fakeDataJob();
        $this->fakeDataHr();
    }
    private function fakeDataJob()
    {
        $user = User::query()->where('type', \COMPANY)->first();
        $company = Company::query()->where('user_id', $user->id)->first();
        if (!$user){
            $user = User::factory()->create([User::TYPE => COMPANY_MANAGER]);
        }
        if (!$company){
            $company = Company::factory()->create(['user_id' => $user->id]);
        }
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        for($i =0; $i <=20; $i++) {
            $dataTest = [
                Work::TITLE => $this->faker->name,
                Work::USER_ID => $user->id,
                Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
                Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
                Work::COMPANY_ID => $company->id,
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
            $work = Work::factory()->create($dataTest);
            $this->addRelationHasMany(LanguageRequirementWork::class, [1,2,3],
                $work->id,'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, [1,2,3], $work->id, 'passion_id');
        }
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
    protected function fakeDataHr()
    {
        $this->faker = \Faker\Factory::create();
        $company = User::query()->where(User::TYPE, \COMPANY)->first();
        Company::factory()->create([
            Company::USER_ID => $company->id,
            Company::MAIL_ADDRESS => $company->mail_address,
            Company::STATUS => CONFIRM
        ]);

        $companyManager = User::query()->where(User::TYPE, COMPANY_MANAGER)->first();
        Company::factory()->create([
            Company::USER_ID => $companyManager->id,
            Company::MAIL_ADDRESS => $companyManager->mail_address,
            Company::STATUS => CONFIRM
        ]);

        $hrManager = User::query()->where(User::TYPE, \HR_MANAGER)->first();
        HrOrganization::factory()->create([
            HrOrganization::USER_ID => $hrManager->id,
            HrOrganization::MAIL_ADDRESS => $hrManager->mail_address,
        ]);

        for ($i = 1; $i <= 25; $i++) {
            if ($i <= 5) {
                $userHr = User::query()->where(User::TYPE, \HR)->first()->id;
            } else
                $userHr = User::factory()->create([User::TYPE => \HR])->id;

            $hrOrg = HrOrganization::firstOrCreate(
                [HrOrganization::USER_ID => $userHr],
                HrOrganization::factory()->make()->toArray()
            );
            $name = $this->faker->name;
            $major_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
            $middle_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major_id)->inRandomOrder()->pluck('id')->first();

            $status = HRS_STATUS_PUBLIC;
            if ($i == 22) {
                $userHr = SUPER_ADMIN;
            }
            if ($i == 23) {
                $status = HRS_STATUS_PRIVATE;
            }
            if ($i == 24) {
                $status = HRS_STATUS_CONFIRM;
            }
            $work_form = $this->faker->randomElement(HRS_WORK_FORM);
            $hr_id = HR::create([
                HR::HR_ORGANIZATION_ID => $hrOrg->id,
                HR::USER_ID => $userHr,
                HR::FULL_NAME => $name,
                HR::FULL_NAME_JA => $name,
                HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
                HR::CREATED_BY => $userHr,
                HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-50 years', '-18 years'),
                HR::FINAL_EDUCATION_DATE => $this->faker->dateTimeBetween('-20 years')->format('Y-m'),
                HR::FINAL_EDUCATION_CLASSIFICATION => $this->faker->randomElement(HR_FINAL_EDUCATION),
                HR::FINAL_EDUCATION_DEGREE => $this->faker->randomElement(array_keys(HR_EDUCATION_DEGREES)),
                HR::WORK_FORM => $work_form,
                HR::PREFERRED_WORKING_HOURS => $work_form == HRS_FULL_TIME_EMPLOYEE ? null : rand(25, 60),
                HR::JAPANESE_LEVEL => LanguageRequirement::query()->inRandomOrder()->pluck('id')->first(),
                HR::MAJOR_CLASSIFICATION_ID => $major_id,
                HR::MIDDLE_CLASSIFICATION_ID => $middle_id,
                HR::MAIL_ADDRESS => $this->faker->email,
                HR::STATUS => $status
            ]);

            HRMainJobCareer::factory()->create([HRMainJobCareer::HRS_ID => $hr_id]);
        }
        $this->hrId = User::query()->where(User::TYPE, \HR)->first()->id;
    }
}
