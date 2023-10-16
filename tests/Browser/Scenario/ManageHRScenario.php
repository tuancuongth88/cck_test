<?php

namespace Tests\Browser\Scenario;

use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ManageHRScenario extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $this->caseWithSuperAdmin($browser);
            $this->caseWithHrManager($browser);
            $this->caseWithHrOrg($browser);
        });
    }

    public function dataTest($type)
    {
        $faker = Factory::create();
        $fakerJa = Factory::create('ja_JP');
        $userHr = User::query()->where(User::TYPE, HR)->first()->id;
        for ($i = 1; $i <= 5; $i++) {
            HrOrganization::factory()->create([
                HrOrganization::CORPORATE_NAME_EN => $faker->name,
                HrOrganization::CORPORATE_NAME_JA => $fakerJa->name,
                HrOrganization::USER_ID => 1
            ]);
        }

        $hrOrg = ($type == 5) ? HrOrganization::query()->where(HrOrganization::USER_ID, $userHr)->first() : HrOrganization::query()->inRandomOrder()->first();
        $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->first()->id;
        $dateTime = $faker->dateTimeBetween('-5 years', '-2 years');
        $department = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->first()->id;

        return [
            'organization_name' => $hrOrg,
            'full_name' => $faker->name,
            'full_name_furigana' => $fakerJa->name,
            'gender' => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
            'date_of_birth' => $faker->dateTimeBetween('-50 years', '-18 years'),
            'work_form' => rand(3, 5),
            'japanese_level' => LanguageRequirement::query()->inRandomOrder()->first()->id,
            'final_education_time' => $dateTime,
            'final_education_class' => rand(1, 3),
            'final_education_degree' => rand(1, 6),
            'major_classification' => $jobType,
            'middle_classification' => JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType)->inRandomOrder()->first()->id,
            'main_job_from' => $dateTime->modify('+1 year'),
            'main_job_to' => $dateTime->modify('+2 year'),
            'main_job_department' => $department,
            'main_job_title' => JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $department)->inRandomOrder()->first()->id,
            'personal_pr_special' => $fakerJa->text(20),
            'remarks' => $fakerJa->text(30),
            'telephone' => rand(1111111111, 9999999999),
            'mobile_phone' => rand(1111111111, 9999999999),
            'mail_address' => $faker->email,
            'city' => $faker->city,
            'district' => $faker->citySuffix,
            'number' => $faker->streetAddress
        ];
    }

    public function registerHr(Browser $browser, $type)
    {
        for ($i = 1; $i <= 2; $i++) {
            $dataTest = $this->dataTest($type);
            $browser->pause(2000)
                ->click('#dropdown-1')->pause(2000)
                ->click('@btn_to_register_hr')->pause(5000)
                ->assertPathIs('/hr/create');

            if ($type == SUPER_ADMIN || $type == HR_MANAGER) {
                $browser->select('@organization_name', $dataTest['organization_name']['id'])->pause(2000);
            }

            $browser->type('@full_name', $dataTest['full_name'])
                ->type('@full_name_furigana', $dataTest['full_name_furigana'])
                ->click('#dusk_gender select > option:nth-child(' . ($dataTest['gender'] + 1) . ')')
                ->select('@date_of_birth_year', $dataTest['date_of_birth']->format('Y'))
                ->select('@date_of_birth_month', $dataTest['date_of_birth']->format('m'))
                ->scrollIntoView('@date_of_birth_day')
                ->select('@date_of_birth_day', $dataTest['date_of_birth']->format('d'))
                ->click('#dusk_work_form select > option:nth-child(' . $dataTest['work_form'] . ')')
                ->click('#dusk_japanese_level select > option:nth-child(' . $dataTest['japanese_level'] . ')')
                ->select('@final_education_timing_year', $dataTest['final_education_time']->format('Y'))
                ->select('@final_education_timing_month', $dataTest['final_education_time']->format('m'))
                ->click('#dusk_final_education_class select > option:nth-child(' . $dataTest['final_education_class'] . ')')
                ->click('#dusk_final_education_degree select > option:nth-child(' . $dataTest['final_education_degree'] . ')')
                ->scrollIntoView('@major_classification')
                ->select('@major_classification', $dataTest['major_classification'])
                ->select('@middle_classification', $dataTest['middle_classification'])
                ->select('@main_job_career_1_year_from', $dataTest['main_job_from']->format('Y'))
                ->select('@main_job_career_1_month_from', $dataTest['main_job_from']->format('m'))
                ->select('@main_job_career_1_year_to', $dataTest['main_job_to']->format('Y'))
                ->select('@main_job_career_1_month_to', $dataTest['main_job_to']->format('m'))->pause(2000)
                ->select('@main_job_career_1_department', $dataTest['main_job_department'])
                ->select('@main_job_career_1_job_title', $dataTest['main_job_title'])
                ->scrollIntoView('@telephone_phone_option')
                ->click('@telephone_phone_option')
                ->click('@telephone_phone_vn')
                ->type('@telephone_phone', $dataTest['telephone'])
                ->scrollIntoView('@mobile_phone_option')
                ->click('@mobile_phone_option')
                ->click('@mobile_phone_vn')
                ->type('@mobile_phone', $dataTest['mobile_phone'])
                ->type('@mail_address', $dataTest['mail_address'])
                ->type('@address_city', $dataTest['city'])
                ->type('@address_district', $dataTest['district'])
                ->type('@address_number', $dataTest['number'])
                ->type('@hometown_address_city', $dataTest['city'])
                ->type('@hometown_address_district', $dataTest['district'])
                ->type('@hometown_address_number', $dataTest['number'])
                ->click('@hr_create_save')
                ->waitFor('.toast-body')
                ->assertSee('人事情報の登録が完了しました')->pause(3000);

            $browser->assertPathIs('/hr/list')
                ->click('.collapse-bar-btn')
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.fullName', $dataTest['full_name'])
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.fullName', $dataTest['full_name_furigana'])
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.organization_name', $dataTest['organization_name']['corporate_name_en'])
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.organization_name', $dataTest['organization_name']['corporate_name_ja'])
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.age', Carbon::parse($dataTest['date_of_birth'])->age)
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.japanese_level', HR_JAPANESE_LEVEL[$dataTest['japanese_level']])
                ->assertSeeIn('#hr-table-list > tbody > tr:nth-child(1) > td.status', '非公開');
        }
    }

    public function editHr(Browser $browser)
    {
        $fileCV = 'tests\FileTest\CV.png';
        $fileJob = 'tests\FileTest\Job.txt';

        for ($i = 1; $i <= 2; $i++) {
            $browser->pause(4000)
                ->click('#hr-table-list > tbody > tr:nth-child(' . $i . ') >  td.detail > div > i')
                ->pause(5000)->assertSee('人材情報詳細')
                ->click('@btn_to_edit_hr')->pause(8000)
                ->scrollIntoView('@hr_public')
                ->click('@hr_public')
                ->scrollIntoView('@file_cv')
                ->attach('@file_cv', $fileCV)->pause(2000)
                ->assertSee('CV.png')
                ->attach('@file_JD', $fileJob)->pause(2000)
                ->assertSee('Job.txt')
                ->scrollIntoView('@btn_save')->pause(2000)
                ->click('@btn_save')
                ->waitFor('.toast-body')
                ->assertSee('データの保存に成功しました');

            $browser->pause(2000)->assertSee('人材情報詳細')
                ->assertSeeIn('.btn.public-active', '公開')
                ->click('@btn_back_to_list_hr');
        }

    }

    public function caseWithSuperAdmin(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->pause(3000)
            ->assertPathIs('/home')
            ->mouseover('#nav-collapse > ul > li:nth-child(2) > a')->pause(3000)
            ->click('#nav-collapse > ul > li:nth-child(2) > a > ul > li:nth-child(2) > a')
            ->pause(6000)
            ->assertPathIs('/hr/list');

        $this->registerHr($browser, SUPER_ADMIN);
        $this->editHr($browser, SUPER_ADMIN);

        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function caseWithHrManager(Browser $browser)
    {
        $this->login(HR_MANAGER);
        $browser->assertPathIs('/home')
            ->mouseover('#nav-collapse > ul > li:nth-child(2) > a')->pause(3000)
            ->click('#nav-collapse > ul > li:nth-child(2) > a > ul > li:nth-child(2) > a')
            ->pause(6000)
            ->assertPathIs('/hr/list');

        $this->registerHr($browser, HR_MANAGER);
        $this->editHr($browser, SUPER_ADMIN);

        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function caseWithHrOrg(Browser $browser)
    {
        $this->login(HR);
        $browser->assertPathIs('/home')
            ->click('#nav-collapse > ul > li:nth-child(2) > a')->pause(3000)
            ->pause(6000)
            ->assertPathIs('/hr/list');

        $this->registerHr($browser, HR);
        $this->editHr($browser, SUPER_ADMIN);

        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }
}
