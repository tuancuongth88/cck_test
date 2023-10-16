<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;

class CaseO1CaseCreateInterviewForOfferTest extends DuskTestCase
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
            $this->setupSendOffer($browser, SUPER_ADMIN);
            $this->setupSendOffer($browser, HR_MANAGER);
            $this->setupSendOffer($browser, HR);
            // Set up interview for offer
            $this->setupInterviewForOffer($browser, SUPER_ADMIN);
            $this->setupInterviewForOffer($browser, COMPANY_MANAGER);
            $this->setupInterviewForOffer($browser, COMPANY);
        });
    }

    private function setupSendOffer($browser, $currentPermission)
    {

        $this->setupMatchingOffer($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
        $this->viewListOffer($browser);
        $this->acceptOffer($browser);
        $this->checkNotification($browser);
        $this->logout();
    }

    private function setupInterviewForOffer($browser, $currentPermission)
    {

        $this->setupInterviewToOffer($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
//        $this->checkNotificationAtHome($browser);
        $this->viewListInterview($browser);
        $this->setupInterviewSchedule($browser);
        if ($currentPermission != SUPER_ADMIN) {
            $this->viewSchedule($browser);
            $this->logout();
            $this->login(\HR);
            $this->viewListInterview($browser);
        }
        $this->selectInterviewDt($browser);
        $this->checkNotificationSelectInterviewDt($browser, $currentPermission);
        $this->logout();
    }

    private function checkNotificationSelectInterviewDt($browser, $currentPermission)
    {
        $browser->pause(5000)->click('#nav-collapse > ul > li:nth-child(1)')->pause(5000);
        if ($currentPermission == SUPER_ADMIN) {
            $browser->assertSee('Zoom URL発行依頼のお知らせ');
        }
        $browser->assertSee('面接日が決定しました')
            ->pause(1000);
    }

    private function viewListInterview($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(4)')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSee($this->targetHrName)
            ->assertSee($this->targetJobName);
    }

    private function viewSchedule($browser)
    {
        $browser->click('#interview-table tbody tr:nth-child(1) i')->pause(4000)
            ->assertSee('個人面接')
            ->assertSee('午前10時~午前11時')
            ->assertSee('午前11時~午後12時')
            ->assertSee('午後1時30分~午後2時30分')
            ->clickAtPoint($x = 0, $y = 0);
    }

    private function selectInterviewDt($browser)
    {
        $browser->click('#interview-table tbody tr:nth-child(1) i')->pause(4000)
            ->click('div[modal-key="adjusting-modal"] select')->pause(500)
            ->select('div[modal-key="adjusting-modal"] select', '1')
            ->click('div[modal-key="adjusting-modal"] .btn-warning')->pause(1000)
            ->click('div[modal-key="adjusting-modal"] .btn-warning')
            ->pause(5000)
            ->assertSee('オファー承諾')
            ->assertSee('URL設定中');

    }

    private function setupInterviewSchedule($browser)
    {
        $nextMonthDay_1 = Carbon::now()->addMonths(1)->format('Y-m-02');
        $nextMonthDay_2 = Carbon::now()->addMonths(1)->format('Y-m-03');
        // #table-calendar-temp tbody div[data-date="2023-10-08"]
        $elementDay_1 = "#table-calendar-temp tbody div[data-date=\"{$nextMonthDay_1}\"]";
        $elementDay_2 = "#table-calendar-temp tbody div[data-date=\"{$nextMonthDay_2}\"]";
        // Select 1 2023/10/02, 午前, 10:00, 60分
        $browser->click('#interview-table tbody tr:nth-child(1) i')->pause(4000)
            ->click('@radio-type-check-2')
            ->click('#table-calendar-temp tbody tr:nth-child(1) div[dusk="timeTemp"]')->pause(500)
            ->click('#table-calendar-temp tbody button[aria-label="Next month"]')
            ->click($elementDay_1)->pause(500)
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(2)', '10:00')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(4) select', '60');
        // Select 2 2023/10/02, 午前, 11:00, 60分
        $browser
            ->click('#table-calendar-temp tbody tr:nth-child(2) div[dusk="timeTemp"]')->pause(500)
            ->click('#table-calendar-temp tbody button[aria-label="Next month"]')
            ->click($elementDay_1)->pause(500)
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(2)', '11:00')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(4) select', '60');
        // Select 3 2023/10/02, 午後, 1:30, 60分
        $browser
            ->click('#table-calendar-temp tbody tr:nth-child(3) div[dusk="timeTemp"]')->pause(500)
            ->click('#table-calendar-temp tbody button[aria-label="Next month"]')
            ->click($elementDay_1)->pause(500)
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(1)', 'PM')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(2)', '1:30')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(4) select', '60');
        // Select 4 2023/10/03, 午前, 10:00, 60分
        $browser
            ->click('#table-calendar-temp tbody tr:nth-child(4) div[dusk="timeTemp"]')->pause(500)
            ->click('#table-calendar-temp tbody button[aria-label="Next month"]')
            ->click($elementDay_2)->pause(500)
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(2)', '10:00')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(4) select', '60');
        // Select 5 2023/10/03, 午前, 11:00, 60分
        $browser
            ->click('#table-calendar-temp tbody tr:nth-child(5) div[dusk="timeTemp"]')->pause(500)
            ->click('#table-calendar-temp tbody button[aria-label="Next month"]')
            ->click($elementDay_2)->pause(500)
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(2)', '10:00')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(4) select', '60');

        $browser->click('div[modal-key="before-adjustment-modal"] .btn-warning')->pause(1000)
            ->click('div[modal-key="before-adjustment-modal"] .btn-warning')->pause(5000);
    }

    private function checkNotificationAtHome($browser)
    {
        $browser->pause(8000)
            // Matching list
            ->assertSee('オファー承認可否が設定されました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('オファー承認可否が設定されました')
            ->assertSee('承認')
            ->pause(1000);
    }

    private function checkNotification($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(1)')->pause(5000)
            ->assertSee('オファー承認可否が設定されました')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->assertSee('オファー承認可否が設定されました')
            ->assertSee('承認')
            ->pause(1000);
    }

    private function viewListOffer($browser)
    {
        $browser->pause(5000)
            // Matching list
            ->click('#nav-collapse > ul > li:nth-child(4)')->pause(8000)
            ->click('.nav-fill li:nth-child(2)')->pause(5000);
    }

    private function acceptOffer($browser)
    {
        #offer-table-list tbody tr:nth-child(1) .btn-go-detail
        $browser->click('#offer-table-list tbody tr:nth-child(1) .btn-go-detail')->pause(5000)
            ->assertSee($this->targetHrName)
            ->assertSee($this->targetJobName)
            ->click('@btn_confirm_offer')->pause(1000)
            ->click('div[modal-key="offer-modal-requesting-confirm"] button[dusk="btn_confirm"]')->pause(4000)
            ->assertDontSee($this->targetHrName)
            ->assertDontSee($this->targetJobName);
    }
}
