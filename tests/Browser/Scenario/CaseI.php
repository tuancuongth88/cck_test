<?php

namespace Tests\Browser\Scenario;

use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\InterviewInfo;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CaseI extends DuskTestCase
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
        $this->fakeDataInterview();

        $this->browse(function (Browser $browser) {
            $this->caseSelectInterviewDate($browser);
        });
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
        for ($i = 0; $i <= 3; $i++) {
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

        for ($i = 1; $i <= 5; $i++) {
            if ($i > 2) {
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

    private function fakeDataInterview()
    {
        $this->fakeDataJob();
        $this->fakeDataHr();

        $date = Carbon::now()->addWeek()->format('Y-m-d');
        $data = [
            [
                "date" => $date,
                "start_time" => "8:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => $date,
                "start_time" => "9:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => $date,
                "start_time" => "10:00",
                "start_time_at" => "AM",
                "expected_time" => "60"
            ],
            [
                "date" => $date,
                "start_time" => "2:00",
                "start_time_at" => "PM",
                "expected_time" => "60"
            ],
            [
                "date" => $date,
                "start_time" => "3:00",
                "start_time_at" => "PM",
                "expected_time" => "60"
            ],
        ];

        $work = Work::query()->inRandomOrder()->first();
        $hrs = HR::query()->limit(5)->get();
        $i = 1;
        foreach ($hrs as $key1 => $hr) {
            $interview = Interview::create([
                Interview::HR_ID => $hr->id,
                Interview::WORK_ID => $work->id,
                Interview::INTERVIEW_CODE => 'E' . $i,
                Interview::INTERVIEW_DATE => Carbon::now()->toDateString(),
                Interview::TYPE => INTERVIEW_TYPE_PRIVATE,
                Interview::STATUS_SELECTION => INTERVIEW_STATUS_SELECTION_DOC_PASS,
                Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING,
                Interview::REMARKS => '',
                Interview::DISPLAY => 'on',
                Interview::STEP => INTERVIEW_TABLE_STEP_INTERVIEW
            ]);

            InterviewInfo::create([
                InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE,
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::CALENDAR_INTERVIEW => json_encode($data),
                InterviewInfo::INTERVIEW_NUMBER => 1,
                InterviewInfo::STATUS => INTERVIEW_INFO_NUMBER_FIRST
            ]);
            $i++;
        }
    }

    public function caseSelectInterviewDate(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $i = 1;
        $entry_code = 5;
        foreach ($types as $type) {
            $this->login($type);
            $option = rand(1, 5);
            $browser->pause(5000)
                ->assertPathIs('/home')
                ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                ->assertPathIs('/matching-management')
                ->click('.nav-fill li:nth-child(3)')->pause(5000)
                ->click('.collapse-bar-btn')->pause(5000)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(3)', 'E' . $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(8) > span', '調整中');

            $browser->click('#interview-table tbody tr:nth-child(' . $i . ') .btn-go-detail')->pause(5000)
                ->select('#adjusting-modal select', $option)
                ->click('#adjusting-modal > div > div.content-detail.px-4 > div > button.btn.btn-warning')
                ->click('#adjusting-modal > div > div.content-detail.px-4 > div.d-flex.flex-column.align-items-center.mt-5 > div > button.btn.btn-warning')->pause(7000)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(3)', 'E' . $entry_code)
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(7) > span', '書類通過')
                ->assertSeeIn('#interview-table > tbody > tr:nth-child(' . $i . ') > td:nth-child(8) > span', 'URL設定中');

            $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
                ->assertSee('面接日が決定しました')->pause(4000)
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)
                ->assertSee('面接日が決定しました');

            $browser->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(5000)
                ->assertPathIs('/matching-management')
                ->click('#interview-table tbody tr:nth-child(' . $i . ') .btn-go-detail')->pause(5000)
                ->assertSee('エントリー')
                ->assertSee('一次面接')
                ->assertSee('個人面接')
                ->assertSee(Carbon::now()->addWeek()->format('Y年m月d日'));

            $browser->refresh()->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
            $i++;
            $entry_code--;
        }
    }

}
