<?php

namespace Tests\Browser\Scenario;

use App\Models\Company;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Faker\Factory;
use Helper\Common;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseJ2CaseInterviewSetUrlStep2Test extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $this->hrManagerOrSupperAdmin($browser, HR_MANAGER);
            $this->hrOrg($browser);
        });
    }

    private function hrManagerOrSupperAdmin($browser, $permission)
    {
        $this->setupDataInterviewSecondSelectedDateCompleted();
        $this->login($permission);
        $this->viewListInterview($browser);
        $this->URLSetting($browser);
        $this->canChangeStatusInterviewAtList($browser);
        $this->viewNotificationAtHome($browser);
        $this->logout();
    }

    private function hrOrg($browser)
    {
        $this->login(\HR);
        // Check list In
        $this->viewListInterview($browser);
        $this->canChangeStatusInterviewAtList($browser);
        $this->canSeeDetailURLInterview2($browser);
        $this->viewNotificationAtHome($browser);
        $this->logout();
    }

    private function canChangeStatusInterviewAtList($browser)
    {
        $browser->pause(3000)
            ->assertSee('一次合格')
            ->assertSee('調整済')
            ->pause(1000);
    }

    private function canSeeDetailURLInterview2($browser)
    {
        $browser->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('一次面接')
            ->assertSee('合格')
            ->assertSee('二次面接')
            ->assertSee('個人面接')
            ->assertSee('https://us06web.zoom.us/j/12345678?pwd=abcdefg')
            ->assertSee('12345678')
            ->assertSee('abcdefg')
            ->clickAtPoint($x = 0, $y = 0)
            ->pause(3000);
    }

    private function URLSetting($browser)
    {
        $browser->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('一次面接')
            ->assertSee('合格')
            ->assertSee('二次面接')
            ->assertSee('個人面接')
            ->assertSee('ミーティングID')
            ->assertSee('パスコード')
            ->type('@url_zoom_text', 'https://us06web.zoom.us/j/12345678?pwd=abcdefg')->pause(200)
            ->type('@id_zoom_text', '12345678')
            ->type('@password_zoom_text', 'abcdefg')
            ->click('#URL-setting-modal .btn-warning')->pause(1000)
            ->pause(3000);
    }

    public function viewNotificationAtHome(Browser $browser)
    {
        $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
            ->assertSee('Zoom URLの発行が完了しました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('Zoom URLの発行が完了しました')
            ->pause(1000);
    }

    private function viewListInterview($browser)
    {
        $browser->pause(3000)
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSee('マッチング管理')
            ->assertSee('一次合格')
            ->assertSee('URL設定中')
            ->pause(1000);
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="interview-table"]/tbody/tr'));
        $dataTable = [];
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataRow = explode("\n", $attribute->getText());
            $dataTable [] = $dataRow;
            $dataCompanyList[] = $dataRow[1];
        }
        foreach ($dataCompanyList as $name) {
            $browser->assertSee($name);
        }
    }
}
