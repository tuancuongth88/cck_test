<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseMCaseSelectedHireDateTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    private $selectedHireDt;

    public function testExample()
    {
        $this->selectedHireDt = Carbon::parse('2023-12-01')->format('Y-m-d');
        $this->browse(function (Browser $browser) {
            // With supper admin(1), Company manage All can setting hire date
            $this->setupHireDate($browser, SUPER_ADMIN);
             $this->setupHireDate($browser,COMPANY_MANAGER );
        });
    }

    private function setupHireDate($browser, $currentPermission)
    {
        $this->setupDataResultWithTimeToHireDate();
        $this->login($currentPermission);
        $this->viewListInterview($browser);
        $this->viewResultTab($browser);
        $this->inputHireDate($browser);
        $this->viewNotificationAtHome($browser);
        // Back to result to check
        $this->viewListInterview($browser);
        $this->editHireDate($browser);
        $this->viewNotificationAtHome($browser);
        $this->logout();
    }

    private function checkDetailAtResult($browser)
    {
       // 2023年12月01日
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000);
        $browser->click('@btn-go-detail-1')->pause(4000)
            ->assertSee('内定承諾')
            ->assertSee('入社日');
        $selectedDateFormat = Carbon::parse($this->selectedHireDt)->format('Y年m月d日');
        $browser->assertSee($selectedDateFormat)->pause(1000);
//            ->clickAtPoint($x = 0, $y = 0);
    }

    private function viewResultTab($browser)
    {
        // Expected: 内定承諾 and -
        // Go to result tab
        $browser->click('.nav-fill li:nth-child(4)')->pause(5000)
            ->assertSee('内定承諾')
            ->assertSee('-');
    }


    private function inputHireDate($browser)
    {
        //3. 入社日を「2023/12/01」で入力する
        $browser->click('@btn-go-detail-1')->pause(1000)
                ->assertSee('内定承諾')
                ->assertSee('入社日')
            ->click('div[modal-key="result-modal-offical-offer-confirm-status"] button[aria-haspopup="dialog"]')
            ->pause(1000);
        // Select date with element div[modal-key="result-modal-offical-offer-confirm-status"] button[aria-label="Next month"]
        $differenceMonth = Carbon::now()->diffInMonths($this->selectedHireDt, false);
        if ($differenceMonth > 0) {
            for ($i = 0; $i <= $differenceMonth; $i++) {
                $browser->click('div[modal-key="result-modal-offical-offer-confirm-status"] button[aria-label="Next month"]')->pause(300);
            }
        } else {
            // If is pass will select the nextmonth with day is 15 of next month
            $this->selectedHireDt = Carbon::now()->addMonth()->format('Y-m-15');
        }
        $targetElementDate = "div[modal-key=\"result-modal-offical-offer-confirm-status\"] .b-calendar-grid-body div[data-date=\"{$this->selectedHireDt}\"]";
        // Click select one date
        $browser->click($targetElementDate)->pause(1000);
        // Click save
        $browser->click('div[modal-key="result-modal-offical-offer-confirm-status"] .btn-warning')->pause(3000);
        $browser->clickAtPoint($x = 0, $y = 0)->pause(8000);
    }

    public function viewNotificationAtHome(Browser $browser)
    {
        // Title 入社日設定メッセージ
        $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
            ->assertSee('内定承諾可否が設定されました')
            ->pause(1000);
    }

    private function viewListInterview($browser)
    {
        $browser->pause(3000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->pause(1000);
    }
    private function editHireDate($browser)
    {
        //Edit to other date
        $browser->click('@btn-go-detail-1')->pause(1000)
            ->assertSee('内定承諾')
            ->assertSee('-')
            ->click('div[modal-key="result-modal-offical-offer-confirm-status"] .btn-warning')
            ->pause(1000);
        // Click change date
        $browser->click('div[modal-key="result-modal-offical-offer-confirm-status"] button[aria-haspopup="dialog"]');
        $changeDtTargetDt = Carbon::parse($this->selectedHireDt)->add('1 day')->format('Y-m-d');
        $targetElementDate = "div[modal-key=\"result-modal-offical-offer-confirm-status\"] .b-calendar-grid-body div[data-date=\"{$changeDtTargetDt}\"]";

        $browser->click($targetElementDate)->pause(1000);
        // Click save
        $browser->click('div[modal-key="result-modal-offical-offer-confirm-status"] .btn-warning')->pause(3000);
        $browser->clickAtPoint($x = 0, $y = 0)->pause(5000);
        // Check at change date when click at details
        $browser->click('@btn-go-detail-1')->pause(4000)
            ->assertSee('内定承諾')
            ->assertSee('入社日');
        $selectedDateFormat = Carbon::parse($changeDtTargetDt)->format('Y年m月d日');
        $browser->assertSee($selectedDateFormat)
            ->pause(1000)
            ->clickAtPoint($x = 0, $y = 0)->pause(5000);
    }
}
