<?php

namespace Tests\Browser;

use App\Models\HrOrganization;
use App\Models\User;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HrOrganizationTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */

    public function testExample()
    {
        $this->fakeHrOrg();
        $this->browse(function (Browser $browser) {
            $this->login(SUPER_ADMIN);
            $this->caseListHrOrg($browser);
            $this->caseSortByUpdateDate($browser);
            $this->caseTestDetail($browser);
            $this->caseEditHrOrganization($browser);
            $this->caseUpdateStatus($browser);
        });
    }

    private function fakeHrOrg()
    {
        $faker = \Faker\Factory::create('ja_JP');
        for ($i = 1; $i <= 25; $i++) {
            HrOrganization::factory()->create([
                HrOrganization::CORPORATE_NAME_JA => $faker->company,
                HrOrganization::UPDATED_AT => $faker->dateTimeBetween('-1 years')
            ]);
        }
    }

    public function caseListHrOrg(Browser $browser)
    {
        $browser->visit('hr-organization/list')->pause(2000);
        $browser->assertSee('人材団体一覧');
        $browser->assertSee('団体名');

        $nameList = HrOrganization::query()
            ->orderByDesc('id')->limit(15)
            ->pluck(HrOrganization::CORPORATE_NAME_JA);
        foreach ($nameList as $name) {
            $browser->assertSee($name);
        }

        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        $dataIdList = [];
        foreach ($attributes as $attribute) {
            $dataIdList[] = explode("\n", $attribute->getText())[0];
        }

        $count = count($dataIdList);
        foreach ($dataIdList as $key => $value) {
            $key = $key < $count - 2 ? $key + 1 : $count - 1;
            $nextText = $dataIdList[$key];
            $this->assertTrue($value >= $nextText);
        }

        $browser->scrollIntoView('.pagination')
            ->click('[aria-label="Go to next page"]')->waitUntilMissing('.loading')
            ->pause(2000)
            ->scrollIntoView('.pagination')
            ->assertSeeIn('.pagination .active', '2')
            ->pause(2000)
            ->click('[aria-label="Go to previous page"]')->waitUntilMissing('.loading')
            ->assertSeeIn('.pagination .active', '1')
            ->pause(2000);
    }

    public function caseSortByUpdateDate(Browser $browser)
    {
        $browser->visit('hr-organization/list')
            ->pause(2000)
            ->click('[aria-colindex="4"]')
            ->pause(2000);

        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        $dataIdList = [];

        foreach ($attributes as $attribute) {
            if ($attribute->getText())
                $dataIdList[] = explode("\n", $attribute->getText())[4];
        }

        $count = count($dataIdList);
        foreach ($dataIdList as $key => $value) {
            $key = $key < $count - 2 ? $key + 1 : $count - 1;
            $nextText = $dataIdList[$key];
            $this->assertTrue($value <= $nextText);
        }
    }

    public function caseTestDetail(Browser $browser)
    {
        $browser->visit('hr-organization/list')->pause(2000)
            ->click('.btn-go-detail')
            ->pause(2000)
            ->assertSee('人材団体詳細')
            ->assertSee('一覧に戻る')
            ->assertSee('編集')
            ->click('@btn-back')
            ->assertSee('人材団体一覧')->pause(3000)
            ->click('.btn-go-detail')->pause(2000)
            ->click('@btn-edit')->pause(3000)
            ->assertSee('必須')
            ->click('@btn-cancel')->pause(2000)
            ->assertSee('人材団体詳細');
    }

    private function inputForm(Browser $browser, $column, $value)
    {
        $browser->keys('@' . $column, ...array_fill(0, 50, '{backspace}'))
                ->assertSee('入力してください。')
                ->type('@' . $column, $value)
                ->assertDontSee('入力してください。')->pause(1000);
    }

    private function validateEmailExisted(Browser $browser)
    {
        $hrOrg = HrOrganization::find(10);
        $browser->scrollIntoView('@assignee_contact')
            ->type('@mail_address', $hrOrg->mail_address)
            ->scrollIntoView('@btn-save')
            ->click('@btn-save')->pause(1000)
            ->assertSee(trans('messages.email_existed'));
    }

    private function validateInput(Browser $browser, $column, $value)
    {
        $browser->scrollIntoView('@assignee_contact')
            ->type('@' . $column, '1234567871')
            ->scrollIntoView('@btn-save')
            ->click('@btn-save')->pause(1000)
            ->assertDontSee('更新の成功');

        if($column == '')
            $this->validateEmailExisted($browser);

        $browser->scrollIntoView('@assignee_contact')
            ->type('@' . $column, $value);
    }

    public function caseEditHrOrganization(Browser $browser)
    {
        $faker = \Faker\Factory::create();
        $browser->visit('hr-organization/list')->pause(2000)
            ->click('.btn-go-detail')->pause(2000)
            ->click('@btn-edit')->pause(2000);

        $arrayList = [
            'corporate_name_en' => $faker->name,
            'corporate_name_ja' => $faker->name,
            'license_no' => $faker->randomNumber(8),
            'representative_full_name' => $faker->name,
            'representative_full_name_furigana' => $faker->name,
            'assignee_contact' => '0123456789',
            'post_code' => $faker->postcode,
            'prefectures' => $faker->state,
            'municipality' => $faker->city,
            'number' => rand(1, 100)
        ];

        $i = 1;
        foreach ($arrayList as $key => $value) {
            if ($i >= 6)
                $browser->scrollIntoView('@assignee_contact');

            $this->inputForm($browser, $key, $value);
            if ($key == 'license_no') {
                $browser->select('@account_classification', rand(1, 5));
                $browser->select('@country', rand(1, 7));
            }
            $i++;
        }
        $this->validateInput($browser, 'mail_address', $faker->email);
        $this->validateInput($browser, 'url', $faker->url);

        $file = 'tests\FileTest\HR_list.csv';
        $browser->scrollIntoView('@assignee_contact')
            ->attach('#upload-certificateFile', $file)->pause(5000)
            ->scrollIntoView('@btn-save')
            ->click('@btn-save')->pause(2000)
            ->assertSee('更新の成功');
    }

    private function updateStatus(Browser $browser, $status, $arrayMiss, $selectOption, $msg, $textStatus)
    {
        HrOrganization::find(25)->update([HrOrganization::STATUS => $status]);
        $browser->visit('hr-organization/edit/25')->pause(2000)
            ->click('@change_status')->pause(2000)
            ->assertSelectMissingOptions('@change_status', $arrayMiss)
            ->select('@change_status', $selectOption)->pause(2000)
            ->click('@btn-save')->pause(2000)
            ->assertSee($msg)
            ->click('@btn_accept')->pause(3000)
            ->assertSee($textStatus);
    }

    public function caseUpdateStatus(Browser $browser)
    {
        $this->updateStatus($browser, EXAMINATION_PENDING, ['利用停止'], CONFIRM, 'ステータスを承認に変更してよろしいですか？', '承認');
        $this->updateStatus($browser, EXAMINATION_PENDING, ['利用停止'], REJECT, 'ステータスを却下に変更してよろしいですか？', '却下');
        $this->updateStatus($browser, CONFIRM, ['却下', '審査待ち'], STOP_USING, 'ステータスを利用停止に変更してよろしいですか？', '利用停止');
    }
}
