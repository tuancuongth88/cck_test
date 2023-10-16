<?php

namespace Tests\Browser\Scenario;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\RemindAccountNotification;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CaseUNotificationCompanyRegisterTest extends DuskTestCase
{
    private $fakerData;
    private $companyEmail ='veho.vietnam@gmail.com';
    private $fakerJa;
    use SetupDataInterview;

    public function testGeneral()
    {
        $this->browse(function ($browser) {
            $this->setupRemindCreateCompany($this->companyEmail);
            $this->caseRegisterCompany($browser);
            // Check after 5 days will have remind message for supper admin and company manage
            $browser->pause(8000); // To run command
            $this->confirmRemindNewCompany($browser);
        });
    }

    public function caseRegisterCompany(Browser $browser)
    {
        $this->fakerData = Factory::create();
        $this->fakerJa = Factory::create('ja_JP');


        $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType['id'])->inRandomOrder()->first();

        $dataTest = [
            'company_name' => 'Daisei VEHO Works',
            'company_name_jp' => 'ダイセイ　ヴェーホー　ワークス',
            'major_classification' => $jobType,
            'middle_classification' => $jobInfo,
            'post_code' => '1000000',
            'prefectures' => 'ハノイ',
            'municipality' => 'ハノイ',
            'number' => '266 Doi can',
            'telephone_number' => rand(1111111111, 9999999999),
            'mail_address' =>  $this->companyEmail,
            'url' => 'http://daisei-veho-works.com',
            'job_title' => 'CEO',
            'full_name' => $this->fakerData->name,
            'full_name_furigana' => $this->fakerJa->name,
            'representative_contact' => '0123456789',
            'assignee_contact' => '0198765432',
        ];
        $this->registerCompany($browser, $dataTest);
        $browser->pause(5000);
        $this->confirmMessage($browser, $dataTest);
       // Update date for company to test remind
        $company = Company::query()->where(Company::MAIL_ADDRESS, '=', $this->companyEmail)->first();
        $company->updated_at = Carbon::now()->subDays(5);
        $company->created_at = Carbon::now()->subDays(5);
        $company->save();
    }

    public function registerCompany(Browser $browser, $dataTest)
    {
        $browser->visit('/login')
            ->click('@btn-create-company')->pause(2000)
            ->assertPathIs('/company-register')
            ->assertSee('企業利用者登録')
            ->assertSee('基本情報の登録')
            ->assertSee('入力内容の確認')
            ->assertSee('申請完了')
            ->type('@company_name', $dataTest['company_name'])->pause(1000)
            ->type('@company_name_jp', $dataTest['company_name_jp'])->pause(1000)
            ->select('@major_classification', $dataTest['major_classification']['id'])->pause(1000)
            ->select('@middle_classification', $dataTest['middle_classification']['id'])->pause(1000)
            ->type('@post_code', $dataTest['post_code'])->pause(1000)
            ->type('@prefectures', $dataTest['prefectures'])->pause(1000)
            ->type('@municipality', $dataTest['municipality'])->pause(1000)
            ->type('@number', $dataTest['number'])->pause(1000)
            ->scrollIntoView('@telephone_number_option')
            ->click('@telephone_number_option')
            ->click('@telephone_number_type_vn')
            ->type('@telephone_number', $dataTest['telephone_number'])->pause(1000)
            ->type('@mail_address', $dataTest['mail_address'])->pause(1000)
            ->type('@url', $dataTest['url'])->pause(1000)
            ->type('@job_title', $dataTest['job_title'])->pause(1000)
            ->type('@full_name', $dataTest['full_name'])->pause(1000)
            ->type('@full_name_furigana', $dataTest['full_name_furigana'])->pause(1000)
            ->scrollIntoView('@representative_contact_option')
            ->click('@representative_contact_option')
            ->click('@representative_contact_vn')
            ->type('@representative_contact', $dataTest['representative_contact'])->pause(1000)
            ->scrollIntoView('@assignee_contact_option')
            ->click('@assignee_contact_option')
            ->click('@assignee_contact_vn')
            ->type('@assignee_contact', $dataTest['assignee_contact'])->pause(1000)
            ->click('@btn-next')->pause(5000);

        $browser->assertSee($dataTest['company_name'])
            ->assertSee($dataTest['company_name_jp'])
            ->assertSee($dataTest['major_classification']['name_ja'])
            ->assertSee($dataTest['middle_classification']['name_ja'])
            ->assertSee($dataTest['post_code'])
            ->assertSee($dataTest['prefectures'])
            ->assertSee($dataTest['municipality'])
            ->assertSee($dataTest['number'])
            ->assertSee('+84 ' . $dataTest['telephone_number'])
            ->assertSee($dataTest['mail_address'])
            ->assertSee($dataTest['url'])
            ->assertSee($dataTest['job_title'])
            ->assertSee($dataTest['full_name'])
            ->assertSee($dataTest['full_name_furigana'])
            ->assertSee('+84 ' . $dataTest['representative_contact'])
            ->assertSee('+84 ' . $dataTest['assignee_contact'])
            ->scrollIntoView('#myDIV')
            ->script("document.getElementById('myDIV').scrollTop = document.getElementById('myDIV').scrollHeight");

        $browser->pause(5000)
            ->click('#app div.agree-with-terms-conditions > div')
            ->click('@btn-register')
            ->pause(4000)
            ->assertSeeIn('@go_to_login', 'ログイン画面へ')
            ->click('@go_to_login')->pause(2000)
            ->assertPathIs('/login');

//        $userId = User::query()->whereIn(User::TYPE, [COMPANY_MANAGER, SUPER_ADMIN])->pluck('id');
//        $notifies = Notification::query()
//            ->where(Notification::TYPE, RemindAccountNotification::class)
//            ->whereIn(Notification::NOTIFIABLE_ID, $userId)
//            ->count();
//        $this->assertEquals($notifies, $count);
    }
    public function confirmRemindNewCompany(Browser $browser)
    {
        $types = [COMPANY_MANAGER, SUPER_ADMIN];
        foreach ($types as $type) {
            $this->login($type);
            $browser->assertPathIs('/home')
                ->pause(5000)
                ->assertSee('リマインドのお知らせ')
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)
                ->assertSee('企業新規アカウント申請のお知らせ')->pause(2000);
            $this->logout();
        }
    }

    public function confirmMessage(Browser $browser, $dataTest)
    {
        $types = [COMPANY_MANAGER, SUPER_ADMIN];
        foreach ($types as $type) {
            $this->login($type);
            $browser->assertPathIs('/home')
                ->pause(5000)
                ->assertSee('企業新規アカウント申請のお知らせ')->pause(5000)
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)
                ->assertSee('企業新規アカウント申請のお知らせ')->pause(2000);

            $browser->mouseover('#nav-collapse > ul > li:nth-child(3) > a')
                ->click('#nav-collapse > ul > li:nth-child(3) > a > ul > li:nth-child(1) > a')
                ->pause(5000)
                ->assertSee('企業一覧')
                ->assertSeeIn('#company-list-table > tbody > tr:nth-child(1) > td.company_name', $dataTest['company_name'])
                ->assertSeeIn('#company-list-table > tbody > tr:nth-child(1) > td.company_name', $dataTest['company_name_jp'])
                ->assertSeeIn('#company-list-table > tbody > tr:nth-child(1) > td.field', $dataTest['major_classification']['name_ja'])
                ->assertSeeIn('#company-list-table > tbody > tr:nth-child(1) > td.updated_at', Carbon::now()->format('Ymd'))
                ->assertSeeIn('#company-list-table > tbody > tr:nth-child(1) > td.status.text-center', '審査待ち');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout')
                ->assertPathIs('/login');
        }
    }
}
