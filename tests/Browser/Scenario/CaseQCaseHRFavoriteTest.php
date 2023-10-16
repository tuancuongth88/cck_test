<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriver;
class CaseQCaseHRFavoriteTest extends DuskTestCase
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
            $this->setupFavorite($browser, COMPANY);
        });
    }

    private function setupFavorite($browser, $currentPermission)
    {
        $this->setupJobAnHrList($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
        $this->viewListHR($browser);
        $this->addFavoriteHR($browser);
        $this->checkListFavorite($browser);
        $this->checkReturnEnableAtSearchList($browser);
        $this->logout();
    }

    private function viewListHR($browser)
    {
        $browser->pause(5000)
            ->click('#nav-collapse > ul > li:nth-child(2)')->pause(5000)
            ->click('@btn_search_with_conditions')
            ->pause(8000)
            ->scrollIntoView('@input_search')
            ->typeSlowly('@input_search', $this->targetHrName)
            ->click('@btn_search_with_conditions')->pause(5000)
            ->scrollIntoView('.collapse-bar-btn')
            ->click('.collapse-bar-btn')->pause(1000);
    }

    private function addFavoriteHR($browser)
    {
       $browser ->click('#hr-table-list tbody tr:nth-child(1) i')->pause(5000)
           ->click('.information-favorire-btns')->pause(5000);

    }

    private function  checkListFavorite($browser) {
        $browser->click('.header-container .nav-favorite')->pause(4000)
            ->assertSee($this->targetHrName)
            ->click('#btn-remove-status-favourite-hr')->pause(1000);
    }

    private function checkReturnEnableAtSearchList($browser){
        $browser->pause(5000)
            ->click('#nav-collapse > ul > li:nth-child(2)')->pause(5000)
            ->click('@btn_search_with_conditions')
            ->pause(8000)
            ->scrollIntoView('@input_search')
            ->typeSlowly('@input_search', $this->targetHrName)
            ->click('@btn_search_with_conditions')->pause(5000)
            ->scrollIntoView('.collapse-bar-btn')
            ->click('.collapse-bar-btn')->pause(1000);
        // Click to detail hr to see button favorite
        $browser ->click('#hr-table-list tbody tr:nth-child(1) i')->pause(5000);
    }

}
