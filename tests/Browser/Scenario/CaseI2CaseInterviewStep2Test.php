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

class CaseI2CaseInterviewStep2Test extends DuskTestCase
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
            $this->supperAdmin($browser);
            // Company manage will create list interview date. Hr will select one date in list interviews date
//        $this->CompanyManageAndHR($browser);
        });
    }

    private function supperAdmin($browser)
    {
        $this->setupDataInterviewFirstCompleted();
        $this->login(SUPER_ADMIN);
        $this->caseListInterview($browser);
        $this->goToDetailInterview($browser);
        $this->selectCalendar($browser);
        $this->adjusting($browser);
        $this->checkDataAfterSetupDateForSupperAdmin($browser);
        $this->logout();
    }

    private function CompanyManageAndHR($browser)
    {
        $this->setupDataInterviewFirstCompleted();
        $this->login(COMPANY_MANAGER);
        $this->caseListInterview($browser);
        $this->goToDetailInterview($browser);
        $this->selectCalendar($browser);
        $this->logout();
        $this->login(HR);
        $this->caseListInterview($browser);
        $this->adjusting($browser);
        $this->checkDataAfterSetupDateForOtherUser($browser);
        $this->logout();
    }

    private function goToDetailInterview($browser)
    {
        $browser->pause(2000)
            ->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('二次面接')
            ->assertSee('集団面接')
            ->assertSee('個人面接')
            ->click('@radio-type-check-2')
            ->pause(3000);
    }

    private function selectCalendar($browser)
    {
        //1 Select 2023/10/02
        $browser->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(2) button[title="Next month"]')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(2) .b-calendar-grid-body > div > div:nth-child(2)')
//        ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(2) ')->pause(2000)
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(3) select:nth-child(2)', '10:00')
            ->click('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(1) td:nth-child(4) select', '60')
            //2
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(2) button[title="Next month"]')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(2) .b-calendar-grid-body > div > div')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(1) ')
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(3) select:nth-child(2)', '1:00')
            ->click('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(2) td:nth-child(4) select', '60')


            //3
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(2) button[title="Next month"]')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(2) .b-calendar-grid-body > div > div')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(1) ')
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(3) select:nth-child(2)', '1:00')
            ->click('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(3) td:nth-child(4) select', '60')

            //4
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(2) button[title="Next month"]')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(2) .b-calendar-grid-body > div > div')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(1) ')
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(3) select:nth-child(2)', '1:00')
            ->click('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(4) td:nth-child(4) select', '60')

            //5
            ->scrollIntoView('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(2)')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(2) button[title="Next month"]')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(2) .b-calendar-grid-body > div > div')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(1) ')
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(1)', 'AM')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(2) ')
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(3) select:nth-child(2)', '1:00')
            ->click('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(4) select')
            ->select('#table-calendar-temp tbody tr:nth-child(5) td:nth-child(4) select', '60')
            ->click('#before_adjustment_modal .btn-warning')->pause(5000)
            ->click('#before_adjustment_modal .btn-warning')->pause(5000)
            ->assertSee('一次合格')
            ->assertSee('調整中');
    }

    private function adjusting($browser)
    {
        $browser->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('二次面接')
            ->click('#adjusting-modal select')->pause(2000)
            ->select('#adjusting-modal select', 1)
            ->click('#adjusting-modal')->pause(2000)
            ->click('#adjusting-modal .btn-warning')->pause(3000)
            ->assertSee('二次面接')
            ->assertSee('面接候補日')
            ->click('#adjusting-modal .btn-warning')->pause(1000)
            ->pause(4000)
            ->assertSee('一次合格')
            ->assertSee('URL設定中');
    }

    public function checkDataAfterSetupDateForSupperAdmin(Browser $browser)
    {
        $browser->visit('/home')->pause(8000)
            ->assertSee('面接日が決定しました')
            ->assertSee('Zoom URL発行依頼のお知らせ')
            ->click('#new-msg > tbody > tr:nth-child(1) > td.title > div > a')->pause(3000)
            ->scrollIntoView('div:nth-child(5) > table > tbody > tr > td:nth-child(1) > a')
            ->assertSee('2023年10月02日 (月) 10:00~11:00')
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('一次面接')
            ->assertSee('合格')
            ->assertSee('二次面接')
            ->assertSee('個人面接')
            ->assertSee('2023年10月02日 (月) 10:00~11:00')
            ->pause(2000)
            ->clickAtPoint($x = 0, $y = 0);
    }

    public function checkDataAfterSetupDateForOtherUser(Browser $browser)
    {
        $browser->click('#nav-collapse > ul > li:nth-child(1) > a')->pause(5000)
            ->click('#nav-collapse > ul > li:nth-child(4) > a')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->click('#interview-table tbody tr:nth-child(1) .btn-go-detail')->pause(4000)
            ->assertSee('二次面接')
            ->assertSee('個人面接')
            ->assertSee('2023年10月02日 (月) 10:00~11:00')
            ->pause(2000)
            ->clickAtPoint($x = 0, $y = 0);
    }

    private function caseListInterview($browser)
    {
        $browser->pause(3000)
            ->visit('/matching-management')->pause(8000)
            ->click('.nav-fill li:nth-child(3)')->pause(5000)
            ->assertSee('マッチング管理')
            ->assertSee('一次合格')
            ->assertSee('調整前')
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
