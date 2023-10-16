<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseO2CaseRejectOfferTest extends DuskTestCase
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
            // Change offer to interview
            $this->setRejectOffer($browser, SUPER_ADMIN);
            $this->setRejectOffer($browser, HR_MANAGER);
            $this->setRejectOffer($browser, HR);
            $this->viewRejectOffer($browser, COMPANY);
            $this->viewRejectOffer($browser, COMPANY_MANAGER);
            $this->viewRejectOffer($browser, COMPANY_MANAGER);

        });
    }

    private function setRejectOffer($browser, $currentPermission)
    {

        $this->setupMatchingOffer($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
        $this->viewListOffer($browser);
        $this->rejectOffer($browser);
        $this->checkRejectOfferDetail($browser);
        $this->checkNotification($browser);
        $this->logout();
    }

    private function viewRejectOffer($browser, $currentPermission)
    {

        $this->login($currentPermission);
        $this->viewListOffer($browser);
        $this->checkRejectOfferDetail($browser);
        $this->checkNotification($browser);
        $this->logout();
    }

    private function checkNotification($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(1)')->pause(5000)
            ->assertSee('オファー承認可否が設定されました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('オファー承認可否が設定されました')
            ->assertSee('辞退')
            ->pause(1000);
    }

    private function viewListOffer($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(4)')->pause(8000)
            ->click('.nav-fill li:nth-child(2)')->pause(8000);
    }

    private function rejectOffer($browser)
    {
        #offer-table-list tbody tr:nth-child(1) .btn-go-detail
        $browser->click('#offer-table-list tbody tr:nth-child(1) .btn-go-detail')->pause(5000)
            ->assertSee($this->targetHrName)
            ->assertSee($this->targetJobName)
            ->click('@btn_decline_offer')->pause(1000)
            ->click('div[modal-key="offer-modal-requesting-decline"] div:nth-child(10) label')->pause(500)
            ->type('@decline_note_offer', 'その他')
            ->click('#btn-handle-decline-offer')
            ->pause(5000)
            ->assertSee('辞退');
    }

    private function checkRejectOfferDetail($browser)
    {
        #offer-table-list tbody tr:nth-child(1) .btn-go-detail
        $browser->click('#offer-table-list tbody tr:nth-child(1) .btn-go-detail')->pause(5000)
            ->assertSee('辞退')
            ->assertSee('辞退理由')
            ->assertSee('その他')
            ->pause(1000)
            ->clickAtPoint($x = 0, $y = 0);
    }
}
