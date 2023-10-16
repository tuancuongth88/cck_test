<?php

namespace Tests\Browser;

use App\Models\HR;
use App\Models\User;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->browse(function ($browser) {
//            $this->caseLoginFail($browser);
//            $this->caseLoginSuccess($browser);
//            $this->caseLogoutSuccess($browser);
//            $this->caseRegisterCompany($browser);
//            $this->caseRegisterHROrganization($browser);
            $this->caseForgotPassword($browser);
        });
    }

    public function caseLoginFail($browser)
    {
        // $browser->maximize();
        $browser->visit('/login')
               // LOGIN and wait for site loading
            ->pause(1000)
            ->keys('@email', ...array_fill(0, 50, '{backspace}'))
            ->keys('@password', ...array_fill(0, 50, '{backspace}'))
            ->assertPresent('.login-submit')
               // test validate feild
            ->type('@email', 'abc@gmail.com')->type('@password', '123456789CCk')->releaseMouse()->press('.login-submit')->pause(2000)
            ->assertSee('ログインIDが正しくありません。.')->pause(2000);
    }

    public function caseLoginSuccess($browser)
    {
        $browser->keys('@email', ...array_fill(0, 50, '{backspace}'))
        ->keys('@password', ...array_fill(0, 50, '{backspace}'));
        $browser->assertPresent('.login-submit')
        // fullfill feild
            ->type('@email', '1okuridashi_hanoi@gmail.vn')
            ->type('@password', '123456789CCk')->releaseMouse()
            ->press('.login-submit')
            ->pause(2000)
            ->assertPresent('.btn-logout');
    }

    public function caseLogoutSuccess($browser){
        $browser->press('.btn-logout')->pause(2000)
            ->assertSee('海外人材マッチングシステム');
    }


    public function caseRegisterCompany(Browser $browser)
    {
        $browser->visit('/login')
            ->click('@btn-create-company')->pause(2000)
            ->pause(2000);
        // Validate name not null
        $browser->pause(5000);
        $browser->type('@company_name', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@company_name')
            ->type('@company_name', 'test Name');

        $browser->type('@company_name_jp', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@company_name_jp')
            ->type('@company_name_jp', 'test Name jp');

        $browser->select('@major_classification', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@major_classification')
            ->select('@major_classification', 1);

        $browser->select('@middle_classification', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@middle_classification')
            ->select('@middle_classification', 1);

        $browser->type('@post_code', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@post_code');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@post_code')
            ->type('@post_code', 100000);

        $browser->type('@prefectures', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@prefectures');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@prefectures')
            ->type('@prefectures', 'prefectures name');

        $browser->type('@municipality', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@municipality');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@municipality')
            ->type('@municipality', 'municipality name');

        $browser->type('@number', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@number');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@number')
            ->type('@number', 123546);

        $browser->type('@other', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@other');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@other')
            ->type('@other', 'other text');


        $browser->scrollIntoView('@telephone_number')
            ->click('@telephone_number_option')
            ->click('@telephone_number_type_vn')
            ->pause(3000)
            ->type('@telephone_number', '0988758522');

        $browser->type('@mail_address', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@mail_address');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@mail_address')
            ->type('@mail_address', 'abc'.time().'@gmail.com');

        $browser->type('@url', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@url');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@url')
            ->type('@url', 'http://abcccss.com.vn');

        $browser->type('@job_title', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@job_title');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@job_title')
            ->type('@job_title', 'Java develop');

        $browser->type('@full_name', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@full_name');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@full_name')
            ->type('@full_name', 'full name');

        $browser->type('@full_name_furigana', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@full_name_furigana');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@full_name_furigana')
            ->type('@full_name_furigana', 'full name furigana');


        $browser->scrollIntoView('@representative_contact_option')
            ->click('@representative_contact_option')
            ->click('@representative_contact_vn')
            ->pause(3000)
            ->type('@representative_contact', '0988758522');

        $browser->scrollIntoView('@assignee_contact_option')
            ->click('@assignee_contact_option')
            ->click('@assignee_contact_ja')
            ->pause(3000)
            ->type('@assignee_contact', '0988758522');

        $browser->click('@btn-next')->pause(3000);
        $browser->scrollIntoView('@btn-register')->pause(1000);

        $checkbox = $browser->driver->findElement(WebDriverBy::id('checkbox-1'));
        $actions = new WebDriverActions($browser->driver);
        $actions->moveToElement($checkbox)->click()->perform();
        $browser->scrollIntoView('@btn-register')
            ->click('@btn-register')->pause( 5000)
            ->assertSee('運営管理者の審査結果をお待ちください。');

    }

    public function caseRegisterHROrganization(Browser $browser)
    {
        $browser->visit('/login')
            ->click('@btn-create-hr')->pause(2000)
            ->pause(2000);

        // Validate name not null
        $browser->pause(5000);
        $browser->type('@corporate_name_en', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@corporate_name_en')
            ->type('@corporate_name_en', 'Company Name');

        $browser->type('@corporate_name_ja', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@corporate_name_ja')
            ->type('@corporate_name_ja', 'Company name jp');

        $browser->type('@license_no', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@license_no')
            ->type('@license_no', 1000423)->pause(3000);

        $browser->select('@account_classification_option', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@account_classification_option')
            ->select('@account_classification_option', 1);

        $browser->select('@country_option', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->script("window.scrollTo(0, 0);");
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@country_option')
            ->select('@country_option', 1);


        $browser->type('@representative_full_name', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@representative_full_name');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@representative_full_name')
            ->type('@representative_full_name', 'Nguyen Thi Nhi');

        $browser->type('@representative_full_name_furigana', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@representative_full_name_furigana');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@representative_full_name_furigana')
            ->type('@representative_full_name_furigana', 'グエンティニ');


        $browser->scrollIntoView('@btn-next')
            ->click('@representative_contact_option')
            ->click('@representative_contact_vn')
            ->pause(3000)
            ->type('@representative_contact', '0988758522');

        $browser->scrollIntoView('@btn-next')
            ->click('@assignee_contact_option')
            ->click('@assignee_contact_vn')
            ->pause(3000)
            ->type('@assignee_contact', '0988758522');

        $browser->scrollIntoView('@btn-next')
            ->type('@post_code', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@post_code');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@post_code')
            ->type('@post_code', '100000');

        $browser->scrollIntoView('@btn-next')
            ->type('@prefectures', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@prefectures');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@prefectures')
            ->type('@prefectures', 'Ha Noi');

        $browser->scrollIntoView('@btn-next')
            ->type('@municipality', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@municipality');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@municipality')
            ->type('@municipality', 'Ha Noi');

        $browser->scrollIntoView('@btn-next')
            ->type('@number', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@number');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@number')
            ->type('@number', '476');


        $browser->scrollIntoView('@btn-next')
            ->type('@other', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@other');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@other')
            ->type('@other', 'abc aaaaa');

        $browser->scrollIntoView('@btn-next')
            ->type('@mail_address', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@mail_address');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@mail_address')
            ->type('@mail_address', 'abc'.time().'@gmail.com');

        $browser->scrollIntoView('@btn-next')
            ->type('@url', '')
            ->scrollIntoView('@btn-next')
            ->click('@btn-next')->pause(3000)
            ->scrollIntoView('@url');
        $browser->assertSee('入力してください。')
            ->scrollIntoView('@url')
            ->type('@url', 'https://google.com');

        $file = "tests\FileTest\HR_list.csv";
//
        $browser->scrollIntoView('@btn-next')
            ->attach('#upload-certificateFile', $file)->pause(5000);


        $browser->scrollIntoView('@btn-next')->click('@btn-next')->pause(3000);
        $browser->scrollIntoView('@btn-register')->pause(1000);

        $checkbox = $browser->driver->findElement(WebDriverBy::id('checkbox-1'));
        $actions = new WebDriverActions($browser->driver);
        $actions->moveToElement($checkbox)->click()->perform();
        $browser->scrollIntoView('@btn-register')
            ->click('@btn-register')->pause( 5000)
            ->assertSee('運営管理者の審査結果をお待ちください。');

    }

    public function caseForgotPassword(Browser $browser)
    {
        $user = User::query()->where('type', \HR)->first();
        if (!$user){
            $user = User::factory()->create(['type' => \HR])->count(1);
        }
        $browser->visit('/forget-password')->pause(2000);

        //test case email not in system
        $browser->typeSlowly('@mail_address', time().'@gmail.com')
            ->click('@btn-send-email')->pause(2000)
            ->assertSee('このメールアドレスは存在しません');

        $browser->typeSlowly('@mail_address', $user->mail_address)
            ->click('@btn-send-email')->pause(7000)
            ->assertSee('メールアドレスにパスワード再設定URLを送信しました。');

        //test click link on email

    }
}
