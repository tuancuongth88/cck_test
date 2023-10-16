<?php

namespace Tests\Browser\Scenario;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Faker\Factory;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CaseH_1_1 extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    private $faker;

    public function testExample()
    {
        $this->faker = Factory::create();
        $this->fakeDataEntry();

        $this->browse(function (Browser $browser) {
            $this->caseConfirmEntryGroup($browser);
            $this->caseSetCandidateInterviewGroup($browser);
        });
    }

    private function fakeDataEntry()
    {
        $this->fakeDataHr();
        $this->fakeDataJob();
        $workId = Work::query()->where(Work::STATUS, WORK_STATUS_RECRUITING)->inRandomOrder()->first()->id;
        for ($i = 1; $i <= 10; $i++) {
            if ($i == 5 || $i == 6)
                $hrId = HR::query()
                    ->where(HR::STATUS, HRS_STATUS_PUBLIC)
                    ->where(HR::USER_ID, \HR)
                    ->inRandomOrder()->first()->id;
            else
                $hrId = HR::query()->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;

            $entry_code = ($i >= 9) ? 'E3' : (($i >= 7) ? 'E2' : 'E1');
            $entry = Entry::create([
                Entry::ENTRY_CODE => $entry_code,
                Entry::HR_ID => $hrId,
                Entry::WORK_ID => $workId,
                Entry::REMARKS => $this->faker->text(30),
                Entry::DISPLAY => 'on',
                Entry::STATUS => ENTRY_STATUS_REQUESTING,
                Entry::REQUEST_DATE => \Carbon\Carbon::now()->format('Y-m-d')
            ]);
            UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE, UploadFile::FILE_ITEM_ID => $entry->id]);
        }
    }

    private function fakeDataJob()
    {
        $user = User::query()->where('type', \COMPANY)->first();
        $company = Company::query()->where('user_id', $user->id)->first();
        if (!$user) {
            $user = User::factory()->create([User::TYPE => COMPANY_MANAGER]);
        }
        if (!$company) {
            $company = Company::factory()->create(['user_id' => $user->id]);
        }
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        for ($i = 0; $i <= 10; $i++) {
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
            $this->addRelationHasMany(LanguageRequirementWork::class, [1, 2, 3],
                $work->id, 'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, [1, 2, 3], $work->id, 'passion_id');
        }
    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item) {
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }

    protected function fakeDataHr()
    {
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

        for ($i = 1; $i <= 10; $i++) {
            if ($i <= 6) {
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

    public function caseConfirmEntryGroup(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $i = 1;

        foreach ($types as $type) {
            $this->login($type);
            $entry_code = ($i == 1) ? 'E3' : (($i == 2) ? 'E2' : 'E1');

            $browser->pause(5000)
                ->assertPathIs('/home')
                ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                ->assertPathIs('/matching-management')
                ->assertSee('エントリー')
                ->click('.collapse-bar-btn')->pause(5000)
                ->assertSeeIn('#entry-table-list > tbody > tr:nth-child(1) >  td .btn-status', '申請中')
                ->assertSeeIn('#entry-table-list > tbody > tr:nth-child(2) >  td .btn-status', '申請中');

            for ($j = 1; $j <= 2; $j++) {
                $browser->click('#entry-table-list > tbody > tr:nth-child(1) td > .btn-go-detail > i')
                    ->pause(5000)
                    ->click('@btn_requesting_confirm')
                    ->click('@btn_confirm')->pause(6000);
            }

            $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
                ->assertSee('エントリー承認可否が設定されました')->pause(4000)
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)
                ->assertSee('エントリー承認可否が設定されました')->pause(2000);

            $browser->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                ->click('.nav-fill li:nth-child(3)')->pause(5000)
                ->click('.collapse-bar-btn')->pause(2000)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(3) > span', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(8) > span', '調整前')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(3) > span', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(8) > span', '調整前');

            $browser->pause(2000)->scrollIntoView('.btn-logout')
                ->click('.btn-logout');

            $typeHr = ($i == 3) ? [HR_MANAGER, \HR] : [HR_MANAGER];
            foreach ($typeHr as $hr) {
                $this->login($hr);
                $browser->pause(2000)
                    ->assertPathIs('/home')
                    ->assertSee('エントリー承認可否が設定されました')->pause(5000)
                    ->click('#new-msg > tbody > tr > td.title > div > a')
                    ->pause(5000)
                    ->assertSee('エントリー承認可否が設定されました')->pause(2000);

                $browser->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                    ->click('.nav-fill li:nth-child(3)')->pause(5000)
                    ->click('.collapse-bar-btn')->pause(2000)
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(3)', $entry_code)
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(7) > span', '書類通過')
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(1) > td:nth-child(8) > span', '調整前')
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(3)', $entry_code)
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(7) > span', '書類通過')
                    ->assertSeeIn('#interview-table > tbody > tr:nth-child(2) > td:nth-child(8) > span', '調整前')
                    ->pause(2000)
                    ->scrollIntoView('.btn-logout')
                    ->click('.btn-logout');
            }
            $i++;
        }
    }

    private function selectCalendar(Browser $browser)
    {
        $date = Carbon::now()->addWeek();
        $now = Carbon::now();
        $title = '';
        if ($date->year > $now->year) {
            $title = "Next year";
        }
        if ($date->month > $now->month) {
            $title = "Next month";
        }
        $date = $date->format('Y-m-d');
        $time = ['8:00', '9:00', '10:00', '2:00', '3:00'];
        for ($i = 1; $i <= 5; $i++) {
            $tmp = ($i > 3) ? 'PM' : 'AM';

            $browser->scrollIntoView('#before_adjustment_modal .btn-warning')
                ->click('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(2)')->pause(2000);
            if ($title)
                $browser->click('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(2) button[title=\'' . $title . '\']')->pause(500);
            $browser->click('#table-calendar-temp .b-calendar-grid .b-calendar-grid-body div[data-date=\'' . $date . '\'')->pause(500)
                ->click('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(3) select:nth-child(1)')->pause(500)
                ->select('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(3) select:nth-child(1)', $tmp)->pause(500)
                ->click('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(3) select:nth-child(2)')->pause(500)
                ->select('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(3) select:nth-child(2)', $time[$i - 1])->pause(500)
                ->click('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(4) select')->pause(500)
                ->select('#table-calendar-temp tbody tr:nth-child(' . $i . ') td:nth-child(4) select', '60')->pause(500);
        }

        $browser->click('#before_adjustment_modal .btn-warning')->pause(3000)
            ->click('#before_adjustment_modal .btn-warning')->pause(5000);
    }

    public function caseSetCandidateInterviewGroup(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $i = 1;
        $j = 1;
        foreach ($types as $type) {
            $entry_code = ($i == 1) ? 'E1' : (($i == 2) ? 'E2' : 'E3');
            $this->login($type);
            $browser->pause(2000)
                ->assertPathIs('/home')
                ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                ->assertPathIs('/matching-management')
                ->click('.nav-fill li:nth-child(3)')->pause(5000)
                ->click('.collapse-bar-btn')->pause(2000)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(3)', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(8) > span', '調整前')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(3)', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(8) > span', '調整前')
                ->pause(2000);

            $browser->pause(4000)
                ->click('#interview-table tbody tr:nth-child('.$j.') .btn-go-detail')->pause(4000)
                ->assertSee('一次面接')
                ->assertSee('集団面接')
                ->assertSee('個人面接')
                ->click('@radio-type-check-1')
                ->pause(3000);
            $this->selectCalendar($browser);

            $browser->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(3)', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $j . ') > td:nth-child(8) > span', '調整中')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(3)', $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . ($j + 1) . ') > td:nth-child(8) > span', '調整中')
                ->pause(2000);

            $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
                ->assertSee('面接候補日が設定されました')->pause(3000)
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)
                ->assertSee('面接候補日が設定されました')->pause(2000)
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
            $i++;
            $j = $j + 2;
        }
    }
}
