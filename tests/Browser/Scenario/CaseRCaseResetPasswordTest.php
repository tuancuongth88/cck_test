<?php

namespace Tests\Browser\Scenario;

use App\Models\HR;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Scenario\SetupDataInterview;
use Tests\DuskTestCase;
use Facebook\WebDriver\WebDriver;

class CaseRCaseResetPasswordTest extends DuskTestCase
{
    /**
     * A Dusk test example.$browser
     *
     * @return void
     */
    use SetupDataInterview;
    protected $myEmail;
    protected $newPass;
    protected $fakeToken;

    public function testExample()
    {
        $this->targetJobName = 'ITエンジニア';
        $this->targetHrName = 'A テスト';
        $this->myEmail = '5okuridashi_hanoi@gmail.vn';
        $this->newPass = 'Abcd12121212';
        $this->fakeToken = 'afteadrwerwertwerwertest';
        $this->browse(function (Browser $browser) {
            $this->caseForgotPassword($browser);
        });
    }

    private function caseForgotPassword($browser)
    {
        $this->setupHrWithMyEmail($this->myEmail, $this->fakeToken);

        $this->visitToForgotPassword($browser);
        $this->setupNewPassAndLoginAgain($browser);
    }

    private function visitToForgotPassword($browser)
    {
        $browser->visit("/login")
            ->pause(1000)
            ->click('@btn-forget-pass')->pause(1000)
            ->typeSlowly('@mail_address', $this->myEmail)->pause(1000);
    }

    private function setupNewPassAndLoginAgain($browser)
    {
        $browser->visit("/api/auth/password-reset?email={$this->myEmail}&token={$this->fakeToken}")
            ->pause(5000)
            ->typeSlowly('#new_password', $this->newPass)
            ->typeSlowly('#new_password_comfirm', $this->newPass)
            ->click('#btn-set-newpass')->pause(5000)
            ->click('.login-submit')->pause(1000)
            ->typeSlowly('@email', $this->myEmail)
            ->typeSlowly('@password', $this->newPass)
            ->click('.login-submit')->pause(5000)
            ->assertSee('ホーム');
    }
}
