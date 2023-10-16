<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriver;
class CasePCaseJobFavoriteTest extends DuskTestCase
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
            $this->setupFavorite($browser, HR);
        });
    }

    private function setupFavorite($browser, $currentPermission)
    {

        $this->setupJobAnHrList($this->targetJobName, $this->targetHrName);
        $this->login($currentPermission);
        $this->viewListJob($browser);
        $this->addFavorite($browser);
        $this->checkListFavorite($browser);
        $this->checkReturnEnableAtSearchList($browser);
        $this->logout();
    }

    private function viewListJob($browser)
    {
        $browser->pause(5000)
            ->click('#nav-collapse > ul > li:nth-child(3)')->pause(5000)
            ->click('@btn_search_with_conditions')
            ->pause(8000);
    }

    private function addFavorite($browser)
    {
       $browser->assertEnabled('#dusk_job_list  .job-item:nth-child(1) .btn-add-favorite-job')
             ->click('#dusk_job_list  .job-item:nth-child(1) .btn-add-favorite-job')
           ->pause(5000);
    }

    private function  checkListFavorite($browser) {
        $browser->click('.header-container .nav-favorite')->pause(4000)
            ->assertSee($this->targetJobName)
            ->click('#btn-remove-status-favourite-job');
    }

    private function checkReturnEnableAtSearchList($browser){
        $browser->pause(5000)
            ->click('#nav-collapse > ul > li:nth-child(3)')->pause(5000)
            ->click('@btn_search_with_conditions')
            ->pause(8000)
            ->assertEnabled('#dusk_job_list  .job-item:nth-child(1) .btn-add-favorite-job');
    }

}
