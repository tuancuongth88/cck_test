<?php

namespace Tests\Browser\Scenario;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\RemindAccountNotification;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Faker\Factory;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterHrOrganizationScenario extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    /**
     * A Dusk test example.
     *
     * @return void
     */
    private $faker;
    private $fakerJa;

    public function testGeneral()
    {
        $this->faker = Factory::create();
        $this->fakerJa = Factory::create('ja_JP');

        $this->browse(function ($browser) {
//            $this->caseRegisterHrOrg($browser); //case A
//            $this->caseRejectAccount($browser); //case B
            $this->caseConfirmAccount($browser); //case B'
        });
    }

    public function caseRegisterHrOrg(Browser $browser)
    {
        for ($i = 1; $i <= 4; $i++) {
            $dataTest = [
                'corporate_name_en' => $this->faker->name . '人材団体',
                'corporate_name_ja' => $this->fakerJa->name,
                'license_no' => $this->faker->randomNumber(8),
                'account_classification' => ACC_CLASS_OWN_PLATFORM,
                'country_option' => array_search('ベトナム', COUNTRY),
                'representative_full_name' => $this->faker->name,
                'representative_full_name_furigana' => $this->fakerJa->name,
                'assignee_contact' => rand(1111111111, 9999999999),
                'representative_contact' => rand(1111111111, 9999999999),
                'post_code' => rand(1000000, 1111111),
                'prefectures' => $this->faker->state,
                'municipality' => $this->faker->city,
                'number' => rand(1, 100),
                'mail_address' => $this->faker->email,
                'url' => 'http://www.efg-hr.com'
            ];
            $this->registerHrOrg($browser, $dataTest, 2 * $i);
            $this->confirmMessage($browser, $dataTest);
        }
    }

    public function registerHrOrg(Browser $browser, $dataTest, $count)
    {
        $file = 'tests\FileTest\HR_list.csv';
        $browser->visit('/login')
            ->click('@btn-create-hr')->pause(2000)
            ->assertPathIs('/human-resourse-register')
            ->assertSee('人材団体 利用者登録')
            ->assertSee('基本情報の登録')
            ->assertSee('入力内容の確認')
            ->assertSee('申請完了')
            ->type('@corporate_name_en', $dataTest['corporate_name_en'])->pause(1000)
            ->type('@corporate_name_ja', $dataTest['corporate_name_ja'])->pause(1000)
            ->type('@license_no', $dataTest['license_no'])->pause(1000)
            ->select('@account_classification_option', $dataTest['account_classification'])->pause(1000)
            ->scrollIntoView('@country_option')
            ->select('@country_option', $dataTest['country_option'])->pause(1000)
            ->type('@representative_full_name', $dataTest['corporate_name_en'])->pause(1000)
            ->type('@representative_full_name_furigana', $dataTest['representative_full_name_furigana'])->pause(1000)
            ->scrollIntoView('@representative_contact_option')
            ->click('@representative_contact_option')
            ->click('@representative_contact_vn')
            ->type('@representative_contact', $dataTest['representative_contact'])->pause(1000)
            ->scrollIntoView('@assignee_contact_option')
            ->click('@assignee_contact_option')
            ->click('@assignee_contact_vn')
            ->type('@assignee_contact', $dataTest['assignee_contact'])->pause(1000)
            ->type('@post_code', $dataTest['post_code'])->pause(1000)
            ->type('@prefectures', $dataTest['prefectures'])->pause(1000)
            ->type('@municipality', $dataTest['municipality'])->pause(1000)
            ->type('@number', $dataTest['number'])->pause(1000)
            ->type('@mail_address', $dataTest['mail_address'])->pause(1000)
            ->type('@url', $dataTest['url'])->pause(1000)
            ->attach('@upload-certificateFile', $file)->pause(5000)
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(5000);

        $browser->assertSee($dataTest['corporate_name_en'])
            ->assertSee($dataTest['corporate_name_ja'])
            ->assertSee($dataTest['license_no'])
            ->assertSee('自社PF')
            ->assertSee($dataTest['country_option'])
            ->assertSee($dataTest['corporate_name_en'])
            ->assertSee($dataTest['representative_full_name_furigana'])
            ->assertSee('+84 ' . $dataTest['representative_contact'])
            ->assertSee('+84 ' . $dataTest['assignee_contact'])
            ->assertSee($dataTest['post_code'])
            ->assertSee($dataTest['prefectures'])
            ->assertSee($dataTest['municipality'])
            ->assertSee($dataTest['number'])
            ->assertSee($dataTest['mail_address'])
            ->assertSee($dataTest['url'])
            ->assertSee('HR_list.csv')
            ->scrollIntoView('#myDIV')
            ->script("document.getElementById('myDIV').scrollTop = document.getElementById('myDIV').scrollHeight");

        $browser->pause(5000)
            ->click('#app div.agree-with-terms-conditions > div')
            ->click('@btn-register')
            ->pause(4000)
            ->assertSeeIn('@go_to_login', 'ログイン画面へ')
            ->click('@go_to_login')->pause(2000)
            ->assertPathIs('/login');

        $userId = User::query()->whereIn(User::TYPE, [HR_MANAGER, SUPER_ADMIN])->pluck('id');
        $notifies = Notification::query()
            ->where(Notification::TYPE, RemindAccountNotification::class)
            ->whereIn(Notification::NOTIFIABLE_ID, $userId)
            ->count();
        $this->assertEquals($notifies, $count);
    }

    public function confirmMessage(Browser $browser, $dataTest)
    {
        $types = [HR_MANAGER, SUPER_ADMIN];
        foreach ($types as $type) {
            $this->login($type);
            $browser->assertPathIs('/home')
                ->pause(5000)
                ->assertSee('人材団体新規アカウント申請のお知らせ')->pause(2000)
                ->click('#new-msg > tbody > tr > td.title > div > a')
                ->pause(5000)->assertSee('人材団体新規アカウント申請のお知らせ')->pause(5000);

            $browser->mouseover('#nav-collapse > ul > li:nth-child(2) > a')
                ->click('#nav-collapse > ul > li:nth-child(2) > a > ul > li:nth-child(1) > a')
                ->pause(3000)
                ->assertSee('人材団体一覧')
                ->assertSeeIn('#table-lib-tablesthr-org > tbody > tr:nth-child(1) > td.organization-name', $dataTest['corporate_name_en'])
                ->assertSeeIn('#table-lib-tablesthr-org > tbody > tr:nth-child(1) > td.organization-name', $dataTest['corporate_name_ja'])
                ->assertSeeIn('#table-lib-tablesthr-org > tbody > tr:nth-child(1) > td.classification', '自社PF')
                ->assertSeeIn('#table-lib-tablesthr-org > tbody > tr:nth-child(1) > td.updated_at', Carbon::now()->format('Ymd'))
                ->assertSeeIn('#table-lib-tablesthr-org > tbody > tr:nth-child(1) > td.status.text-center', '審査待ち');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout')
                ->assertPathIs('/login');
        }
    }

    public function caseRejectAccount(Browser $browser)
    {
        $types = [HR_MANAGER, SUPER_ADMIN];
        $i = 1;
        foreach ($types as $type) {
            $this->login($type);
            $browser->assertPathIs('/home')
                ->pause(5000)
                ->mouseover('#nav-collapse > ul > li:nth-child(2) > a')
                ->click('#nav-collapse > ul > li:nth-child(2) > a > ul > li:nth-child(1) > a')
                ->pause(6000)
                ->assertPathIs('/hr-organization/list')
                ->click('#table-lib-tablesthr-org > tbody > tr:nth-child(' . $i . ') > td.detail.text-center .btn-go-detail')->pause(4000)
                ->assertSee('人材団体詳細')
                ->click('@btn-edit')->pause(5000)
                ->assertSee('必須')
                ->select('@change_status', REJECT)
                ->click('@btn-save')->pause(2000)
                ->assertSee('ステータスを却下に変更してよろしいですか？')
                ->click('@btn_accept')->pause(10000)
                ->assertSee('人材団体詳細')
                ->assertSee('却下');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout')
                ->assertPathIs('/login');
            $i++;
        }
    }

    public function caseConfirmAccount(Browser $browser)
    {
        $types = [HR_MANAGER, SUPER_ADMIN];
        $i = 3;
        foreach ($types as $type) {
            $this->login($type);
            $browser->assertPathIs('/home')
                ->pause(5000)
                ->mouseover('#nav-collapse > ul > li:nth-child(2) > a')
                ->click('#nav-collapse > ul > li:nth-child(2) > a > ul > li:nth-child(1) > a')
                ->pause(6000)
                ->assertPathIs('/hr-organization/list')
                ->click('#table-lib-tablesthr-org > tbody > tr:nth-child(' . $i . ') > td.detail.text-center .btn-go-detail')->pause(4000)
                ->assertSee('人材団体詳細')
                ->click('@btn-edit')->pause(5000)
                ->assertSee('必須')
                ->select('@change_status', CONFIRM)
                ->click('@btn-save')->pause(4000)
                ->assertSee('ステータスを承認に変更してよろしいですか？')
                ->click('@btn_accept')->pause(10000)
                ->assertSee('人材団体詳細')
                ->assertSee('承認');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout')
                ->assertPathIs('/login');
            $i++;
        }
    }
}
