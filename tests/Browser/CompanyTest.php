<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobType;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CompanyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->fakeDataCompany();
        $this->browse(function ($browser) {
            $this->login(SUPER_ADMIN);
            $this->caseListCompany($browser);
            $this->caseCanSortDesByDriverName($browser);
            $this->caseTestDetail($browser);
            $this->caseEditCompany($browser);
        });
    }

    public function caseListCompany(Browser $browser)
    {
        $browser->visit('company/list')->pause(2000);
        $browser->assertSee('企業一覧');
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        $dataTable = [];
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataTable [] = explode("\n", $attribute->getText());
            $dataCompanyList[] = explode("\n", $attribute->getText())[2];
        }
        foreach ($dataCompanyList as $name) {
            $browser->assertSee($name);
        }

        //test sort
    }

    public function caseCanSortDesByDriverName(Browser $browser)
    {
        $browser->click('.class-dusk-field')->pause(3000);
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        $dataTable = [];
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataTable [] = explode("\n", $attribute->getText());
            $dataCompanyList[] = explode("\n", $attribute->getText())[3];
        }
        foreach ($dataCompanyList as $key => $value){
            $key = $key < count($dataCompanyList) -2 ? $key + 1 : count($dataCompanyList) -1;
            $nextText = $dataCompanyList[$key];
            $this->assertTrue($value <= $nextText);
        }
    }

    public function caseTestDetail(Browser $browser)
    {
        $browser->click('.btn-go-detail')
            ->pause(2000)
            ->assertSee('企業詳細')
            ->assertSee('一覧に戻る')
            ->assertSee('編集')
            ->click('@btn-back')
            ->assertSee('企業一覧')->pause(3000)
            ->click('.btn-go-detail')->pause(2000)
            ->click('@btn-edit')->pause(3000)
            ->assertSee('必須')
            ->click('@btn-cancel');
    }

    public function caseEditCompany(Browser $browser){
        $browser->visit('company/list')->pause(2000);
        $browser->click('.btn-go-detail')->pause(2000)
            ->click('@btn-edit')->pause(3000);
        //check validate
        $browser->keys('@company_name', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@company_name', $this->generateRandomString(20))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@company_name_jp', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@company_name_jp', $this->generateRandomString(20))
            ->assertDontSee('入力してください。');

        $browser->select('@major_classification_options', JOB_TYPE)->pause(1000)
            ->select('@middle_classification_id', 1)->pause(1000);

        $browser->keys('@post_code', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@post_code', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@prefectures', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@prefectures', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@municipality', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@municipality', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@number', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@number', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@other', ...array_fill(0, 50, '{backspace}'))
            ->type('@other', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@telephone_number', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@telephone_number', '1234567871')
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->scrollIntoView('@assignee_contact')
            ->keys('@mail_address', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@mail_address', '1234567871')
            ->assertDontSee('入力してください。')->pause(1000)
            ->scrollIntoView('@btn-save')
            ->click('@btn-save')->pause(1000)
            ->type('@mail_address', 'abcssss@gmail.com');

        $browser->scrollIntoView('@assignee_contact')
            ->keys('@url', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@url', '1234567871')
            ->scrollIntoView('@btn-save')->click('@btn-save')->pause(1000)
            ->type('@url', 'http://google.com');;

        $browser->scrollIntoView('@assignee_contact')
            ->keys('@job_title', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@job_title', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->scrollIntoView('@assignee_contact')
            ->keys('@full_name_furigana', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@full_name_furigana', $this->generateRandomString(5))
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->scrollIntoView('@assignee_contact')
            ->keys('@assignee_contact', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@assignee_contact', 123456789)
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->scrollIntoView('@btn-save')->click('@btn-save')
            ->pause(2000)
            ->assertSee('更新の成功');
    }

    private function fakeDataCompany(){
        $majorId = JobType::query()
            ->where('type', JOB_TYPE)
            ->inRandomOrder()
            ->value('id');
        $middleId = JobInfo::query()
            ->where('job_type_id', $majorId)
            ->value('id');
        for($i =0; $i <=20; $i++){
            $dataTest = [
                Company::COMPANY_NAME => 'City Computer Co., Ltd.',
                Company::COMPANY_NAME_JP => 'シティコンピュータ株式会社',
                Company::MAJOR_CLASSIFICATION => $majorId,
                Company::MIDDLE_CLASSIFICATION => $middleId,
                Company::POST_CODE => '1020093',
                Company::PREFECTURES => '東京都',
                Company::MUNICIPALITY => '千代田区平河町',
                Company::NUMBER => '1-7-10',
                Company::OTHER => '大盛丸平河町ビル2階',
                Company::TELEPHONE_NUMBER => '+84 0312345678',
                Company::MAIL_ADDRESS => "tuancuong$i@gmail.com",
                Company::URL => 'https://okuridashi_hanoi.com',
                Company::JOB_TITLE => '代表取締役会長',
                Company::FULL_NAME => '鈴木　太郎',
                Company::FULL_NAME_FURIGANA => 'スズキ　タロウ',
                Company::REPRESENTATIVE_CONTACT => '+84 0312345678',
                Company::ASSIGNEE_CONTACT => '+84 0312345678',
                Company::STATUS => '1',
            ];
            Company::factory()->create($dataTest);
        }
    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}
