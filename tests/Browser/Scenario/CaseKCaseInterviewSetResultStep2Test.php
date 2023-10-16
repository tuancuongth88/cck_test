<?php

namespace Tests\Browser\Scenario;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseKCaseInterviewSetResultStep2Test extends DuskTestCase
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
            // With supper admin(1), Company manage (2), Company (4). All can setting result of interview
            $this->setupResultInterview($browser, SUPER_ADMIN);
            $this->setupResultInterview($browser, COMPANY_MANAGER);
            $this->setupResultInterview($browser, COMPANY);
        });
    }

    private function setupResultInterview($browser, $permission)
    {
        $this->setupDataInterviewSecondSetUrlCompleted();
        $this->login($permission);
        $this->viewListInterview($browser);
        $this->setupResult($browser);
        $this->viewResultTab($browser);
        $this->viewResultDetail($browser);
        $this->viewNotificationAtHome($browser);
        $this->logout();
    }

    private function setupResult($browser)
    {

        $browser
            ->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('面接完了')
            ->assertSee('二次面接')
            ->click('#adjusted-modal .btn-warning')->pause(500)
            ->click('#adjusted-modal select')->pause(500)
            ->select('#adjusted-modal select', 3)->pause(500)// Select 内定
            ->click('#date_offer')->pause(500)
            ->click('#date_offer__dialog_ button[aria-label="Next month"]')
            ->click('#date_offer__dialog_ button[aria-label="Next month"]')->pause(500)
            ->click('.b-calendar-grid-body  div[data-date="2023-11-01"]')->pause(1000)
            ->click('#adjusted-modal .btn-warning')->pause(1000)
            ->click('#adjusted-modal .btn-warning')->pause(1000)
            ->pause(4000);
    }

    private function viewResultTab($browser)
    {
        // ステータスは次の通り：[内定] [20231101]
        // Go to result tab
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000)
            ->click('.collapse-bar-btn')->pause(500)
            ->assertSee('内定')
            ->assertSee('20231101');
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="interview-table"]/tbody/tr'));
        $dataTable = [];
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataRow = explode("\n", $attribute->getText());
            $this->hrI[] = $dataRow[1];
        }
    }

    private function viewResultDetail($browser)
    {
        $browser->click('@btn-go-detail-1')->pause(4000)
            ->assertSee('内定')
            ->assertSee('回答期限 :2023年11月01日')
            ->assertSee('二次面接')
            ->assertSee('ミーティングID')
            ->assertSee('パスコード')
            ->assertSee('合格')
            ->assertSee('一次面接')
            ->assertSee('合格')
            ->clickAtPoint($x = 0, $y = 0);
    }

    public function viewNotificationAtHome(Browser $browser)
    {
        $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
            ->assertSee('面接結果が設定されました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('内定')
            ->assertSee('面接結果が設定されました')
            ->pause(1000);
    }

    private function viewListInterview($browser)
    {
        // Aのステータスは [一次合格] [調整済
        $browser->pause(3000)
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSee('マッチング管理')
            ->assertSee('一次合格')
            ->assertSee('調整済')
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

    //  ==================================================================================================================

}
