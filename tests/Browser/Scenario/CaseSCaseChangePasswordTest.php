<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriver;

class CaseSCaseChangePasswordTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    protected $myEmail;
    protected $newPass;
    protected $oldPass;

    public function testExample()
    {
        $this->myEmail = '4okuridashi_hanoi@gmail.vn';
        $this->newPass = 'Abcd98989898';
        $this->oldPass = '123456789CCk';
        $this->browse(function (Browser $browser) {
            // Test for company
            $this->caseChangePassword($browser, COMPANY);
        });
    }

    private function caseChangePassword($browser, $currentPermission)
    {
        $this->setupCompanyWithMyEmail();
        $this->login($currentPermission);
        $this->changePassword($browser);
    }

    private function changePassword($browser)
    {
        $browser->pause(5000)
            ->click('@user_setting')->pause(500)
            ->click('@change_password')->pause(1000)
            // Confirm current pass
            ->typeSlowly('#current-password', $this->oldPass)
            ->typeSlowly('#current-password-confirm', $this->oldPass)
            ->click('#btn-confirm-current-pass')
            ->pause(2000)
            // Submit new pass
            ->typeSlowly('#current-password', $this->newPass)
            ->typeSlowly('#new-password-confirm', $this->newPass)
            ->click('#btn-confirm-current-pass')->pause(3000)
            ->assertSee('パスワードを変更しました。')
            // Back to login page
            ->click('.login-submit')->pause(1000)
            // Login with new pass
            ->typeSlowly('@email', $this->myEmail)
            ->typeSlowly('@password', $this->newPass)
            ->click('.login-submit')->pause(5000)
            ->assertSee('ホーム');
    }

}
