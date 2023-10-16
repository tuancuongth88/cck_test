<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseNCaseCreateOfferTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    protected $targetJobName;
    protected $targetHrName;

    public function testExample()
    {
        $this->targetJobName = 'ITエンジニア';
        $this->targetHrName = 'A テスト';
        $this->browse(function (Browser $browser) {
            // With supper admin(1), Company manage All can setting hire date
            $this->setupSendOffer($browser, SUPER_ADMIN);
            $this->setupSendOffer($browser, COMPANY_MANAGER);
            $this->setupSendOffer($browser, COMPANY);
        });
    }

    private function setupSendOffer($browser, $currentPermission)
    {

        $this->setupDataHRListToCanSendOffer($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
        $this->visitHrSearchWithCondition($browser, $currentPermission);
        $this->selectOfferAtDetailHr($browser);
        if($currentPermission == SUPER_ADMIN){
            $this->checkNotification($browser);
        }
        $this->viewListOffer($browser);
        $this->checkDetailOffer($browser, $currentPermission);
        $this->logout();
    }

    private function visitHrSearchWithCondition($browser, $currentPermission)
    {
        if ($currentPermission == SUPER_ADMIN) {
            $browser->visit('/hr/list')
                ->pause(2000);
        } else {
            $browser->visit('/hr-search')
                ->pause(2000)
                ->click('@btn_search_with_conditions')->pause(8000);

        }
        $browser->scrollIntoView('@input_search')
            ->type('@input_search', $this->targetHrName)
            ->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(5000)
            ->assertSee($this->targetHrName)
            ->scrollIntoView('.collapse-bar-btn')
//            ->click('.collapse-bar-btn')->pause(2000)->click('.hr-list-table-list-wrap tbody tr:nth-child(1) i[dusk="btn_detail_hr"]')
            ->click('.collapse-bar-btn')->pause(2000)->click('#hr-table-list tbody tr:nth-child(1) i[dusk="btn_detail_hr"]')
            ->pause(8000);
    }

    private function selectOfferAtDetailHr($browser)
    {
        $browser->click('.information-detail-btns')->pause(8000)
            ->click('#dusk_job_to_offer tbody  tr:nth-child(1)')->pause(1000)
            ->click('.btn-confirm-offer')->pause(1000)
            ->click('div[modal-key="select-jobs-to-offer-confirm"] .btn-ok')->pause(3000);
    }
    private function checkNotification($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(1)')->pause(5000)
            ->assertSee('オファーが実行されました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('オファーが実行されました')
            ->pause(1000);
    }
    private function viewListOffer($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(4)')->pause(8000)
            ->click('.nav-fill li:nth-child(2)')->pause(5000)
            ->assertSee('申請中')
            ->assertSee($this->targetHrName)
            ->assertSee($this->targetJobName);
    }

    private function checkDetailOffer($browser, $currentPermission)
    {
        #offer-table-list tbody tr:nth-child(1) .btn-go-detail
        $browser->click('#offer-table-list tbody tr:nth-child(1) .btn-go-detail')->pause(5000)
            ->assertSee($this->targetHrName)
            ->assertSee($this->targetJobName);
        if ($currentPermission == SUPER_ADMIN) {
            $browser->assertButtonEnabled('@btn_confirm_offer')
                ->assertButtonEnabled('@btn_decline_offer')
                ->assertSeeIn('@btn_confirm_offer', '承諾')
                ->assertSeeIn('@btn_decline_offer', '辞退');
        } else {
            $browser->assertSee('申請中');
        }
        $browser->clickAtPoint($x = 0, $y = 0);
    }
}
