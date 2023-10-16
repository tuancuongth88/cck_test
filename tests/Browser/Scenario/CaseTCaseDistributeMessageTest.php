<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriver;

class CaseTCaseDistributeMessageTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    private $distributeTitle = 'test';
    private $distributeContent = 'Scenario test';

    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            // Test for supperAdmin
            $this->setupDistribute($browser, SUPER_ADMIN);
            // Check distribute for other permission
            $this->viewDistribute($browser, COMPANY);
            $this->viewDistribute($browser, COMPANY_MANAGER);
            $this->viewDistribute($browser, HR);
            $this->viewDistribute($browser, HR_MANAGER);
        });
    }

    private function setupDistribute($browser, $currentPermission)
    {
        $this->setupUserOnly();
        $this->login($currentPermission);
        $this->createDistribute($browser);
        $this->logout();
    }

    private function viewDistribute($browser, $currentPermission)
    {
        $this->login($currentPermission);
        $browser->pause(5000)
            ->click('.nav-tabs li:nth-child(2)')->pause(5000)
            // See distribute that setup from supper admin
            ->assertSeeIn('#new-msg-other', $this->distributeTitle)
            // Click view detail distribution msg
            ->click('#new-msg-other tbody tr:nth-child(1) .title a')->pause(5000)
            ->assertSee( $this->distributeTitle)
            ->assertSee( $this->distributeContent);
//        $browser->assertSourceHas('path/to/image.jpg');
//        $browser->assertSeeIn('.distribute-msg-frame img', 'url("path/to/image.jpg")', ['style' => 'background-image']);
        $this->logout();
    }

    private function createDistribute($browser)
    {
        $browser->pause(5000)
            ->click('@create_msg')->pause(1000)
            ->typeSlowly('#distribute-msg-input-title', $this->distributeTitle)
            ->typeSlowly('#distribute-msg-input-text', $this->distributeContent);
        // Upload file
        $file = 'tests\FileTest\CV.png';
        $browser->scrollIntoView('.custom-file-upload')->pause(5000)
            ->attach('@file-input', $file)->pause(5000)
            ->scrollIntoView('@create-msg')->pause(1000)
            ->click('@create-msg')->pause(8000);
        // Check at list
        $browser->assertSeeIn('#distribution-msg-table', $this->distributeTitle);
    }

}
