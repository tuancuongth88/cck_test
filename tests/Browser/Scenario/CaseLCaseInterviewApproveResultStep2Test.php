<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseLCaseInterviewApproveResultStep2Test extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    private $targetHr;

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            // With supper admin(1), HR_MANAGER (2), HR Organization (5). All can setting result of interview
            $this->setupResultInterview($browser, SUPER_ADMIN);
             $this->setupResultInterview($browser,HR_MANAGER );
            $this->setupResultInterview($browser,HR );
        });
    }

    private function setupResultInterview($browser, $currentPermission)
    {
        $this->setupDataInterviewSecondPassToResult();
        $this->login($currentPermission);
        $this->viewListInterview($browser);
        $this->viewResultTab($browser);
        $this->checkStatusBefore($browser);
        $this->setupApprove($browser);
        $this->checkStatusAfter($browser);
        $this->viewSetupResultAfter($browser);
        $this->viewNotificationAtHome($browser);
        $this->logout();
        $listCheckPermission =[SUPER_ADMIN, COMPANY_MANAGER,COMPANY, HR, HR_MANAGER];
        foreach ($listCheckPermission as $permission ){
            if($permission != $currentPermission){
                $this->login($permission);
                $this->viewNotificationAtHome($browser);
                $this->logout();
            }
        }
    }

    private function viewSetupResultAfter($browser)
    {
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000);
        $browser->click('@btn-go-detail-1')->pause(4000)
            ->assertSee('内定承諾')
            ->assertSee('入社日')
            ->clickAtPoint($x = 0, $y = 0);
    }

    private function viewResultTab($browser)
    {
        // ステータスは次の通り：[内定] [20231101]
        // Go to result tab
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000)
            ->click('.collapse-bar-btn')->pause(500)
            ->assertSee('内定')
            ->assertSee('20231101');
    }

    private function checkStatusAfter($browser)
    {
        $elementTableEntry = '#entry-table-list > tbody > tr';
        $elementTableOffer = '#offer-table-list > tbody > tr';
        $elementTableInterview = '#interview-table > tbody > tr';
        $offTxt = '辞退';
        $offInterviewTxt = '面接辞退';
        // Entry After 辞退
        $browser->click('.nav-fill li:nth-child(1)')->pause(5000)
            ->assertSeeIn($elementTableEntry, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableEntry, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableEntry, $offTxt)
            ->click('#entry-table-list tbody tr:nth-child(1) #btn-go-detail')->pause(1000)
            ->assertSee('辞退')
            ->assertSee('辞退理由')
            ->assertSee('他社内定')
            ->pause(1000)
            ->clickAtPoint($x = 0, $y = 0);

        // Offer After 辞退
        $browser->click('.nav-fill li:nth-child(2)')->pause(5000)
            ->assertSeeIn($elementTableOffer, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableOffer, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableOffer, $offTxt)
            ->click('#offer-table-list tbody tr:nth-child(1) #btn-go-detail')->pause(1000)
            ->assertSee('辞退')
            ->assertSee('辞退理由')
            ->assertSee('他社内定')
            ->pause(1000)
            ->clickAtPoint($x = 0, $y = 0);
        // Interview After 書類通過
        $browser->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSeeIn($elementTableInterview, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableInterview, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableInterview, $offInterviewTxt);
    }

    private function checkStatusBefore($browser)
    {
        $elementTableEntry = '#entry-table-list > tbody > tr';
        $elementTableOffer = '#offer-table-list > tbody > tr';
        $elementTableInterview = '#interview-table > tbody > tr';
        $openTxt = '申請中';
        $openInterviewTxt = '書類通過';
        // Entry Before 申請中
        $browser->click('.nav-fill li:nth-child(1)')->pause(5000)
            ->assertSeeIn($elementTableEntry, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableEntry, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableEntry, $openTxt);
        // Offer Before 申請中
        $browser->click('.nav-fill li:nth-child(2)')->pause(5000)
            ->assertSeeIn($elementTableOffer, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableOffer, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableOffer, $openTxt);
        // Interview Before 書類通過
        $browser->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSeeIn($elementTableInterview, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableInterview, $this->targetHr['hr_name'])
            ->assertSeeIn($elementTableInterview, $openInterviewTxt);
    }

    private function setupApprove($browser)
    {
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000);
        $browser->click('@btn-go-detail-1')->pause(4000)
            ->assertSee('内定')
            ->assertSee('回答期限 :2023年11月01日')
            ->click('@btn-confirm')->pause(2000)
            ->assertSee('承諾してよろしいですか？');
        $btnConfirms = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="id-modal-confirm___BV_modal_body_"]/div/div[2]/button[2]'));
        foreach ($btnConfirms as $attribute) {
            if ($attribute->getText() == '承諾する') {
                $attribute->click();
            }
        }
        $browser->pause(3000);
    }

    public function viewNotificationAtHome(Browser $browser)
    {
        // With 4 message notification
        $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
            ->assertSee('内定承諾可否が設定されました')
            ->assertSee('オファー承認可否が設定されました')
            ->assertSee('エントリー辞退が設定されました')
            ->assertSee('面接辞退が設定されました')
            ->pause(1000);
    }

    private function viewListInterview($browser)
    {
        $browser->pause(3000)
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->click('.nav-fill li:nth-child(4)')->pause(5000)
            ->assertSee('マッチング管理')
            ->pause(1000);
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="result-table-list"]/tbody/tr'));
        foreach ($attributes as $key => $attribute) {
            $dataRow = explode("\n", $attribute->getText());
            if ($key == 0) {
                $this->targetHr = [
                    'hr_name' => $dataRow[1],
                    'hr_name_ja' => $dataRow[2],
                ];
            }
        }
    }

}
