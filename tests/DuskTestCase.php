<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreateDuskTestApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080'
        ])->all());
//        $options = (new ChromeOptions)->addArguments(collect([
//            '--window-size=1920,1080',
//        ])->unless($this->hasHeadlessDisabled(), function ($items) {
//            return $items->merge([
//                '--disable-gpu',
//                '--headless',
//            ]);
//        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Deter mine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }
    public function login($type = SUPER_ADMIN){
        $account['email'] = '1okuridashi_hanoi@gmail.vn';
        $account['password'] = '123456789CCk';
        switch ($type){
            case SUPER_ADMIN:
                $account['email'] = '1okuridashi_hanoi@gmail.vn';
                break;
            case COMPANY_MANAGER:
                $account['email'] = '2okuridashi_hanoi@gmail.vn';
                break;
            case HR_MANAGER:
                $account['email'] = '3okuridashi_hanoi@gmail.vn';
                break;
            case COMPANY:
                $account['email'] = '4okuridashi_hanoi@gmail.vn';
                break;
            case HR:
                $account['email'] = '5okuridashi_hanoi@gmail.vn';
                break;

        }
        $this->browse(function (Browser $browser) use ($account) {
            $browser->visit('/login')
                ->type('@email', $account['email'])
                ->type('@password', $account['password'])->releaseMouse()
                ->press('.login-submit')->pause(2000);
        });
        sleep(2);
    }

     public function  logout(){
         $this->browse(function (Browser $browser)  {
             $browser->scrollIntoView('.btn-logout')
                 ->click('.btn-logout');
         });
     }
}
