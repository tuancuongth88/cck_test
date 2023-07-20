<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->browse(function ($browser) {
            $this->caseFail($browser);
            $this->caseSuccess($browser);
        });
    }

    public function caseFail($browser)
    {
        // $browser->maximize();
        $browser->visit('/#/login')
               // LOGIN and wait for site loading
            ->pause(1000)
            ->assertPresent('.login')
               // test validate feild
            ->type('username', 'Good morning!')->type('password', '123456789')->releaseMouse()->press('.btn_submit')->pause(2000)
               // check validate success or not
            ->assertPresent('.login-message')
               // check hack DDOS
            ->type('username', '<script>alert("this is bad script")</script>')->type('password', '123456789')->releaseMouse()->press('.btn_submit')->pause(2000)
               // check validate success or not
            ->assertPresent('.login-message')
               // check right gmail, wrong password
            ->type('username', '123r21')->type('password', '1iLoveu23456789')->releaseMouse()->press('.btn_submit')->pause(2000)
               // check validate success or not
            ->assertPresent('.login-message')
               // check wrong email right password
            ->type('username', 'wrong_account@gmail.com')->type('password', '123456789')->releaseMouse()->press('.btn_submit')->pause(2000)
               // check validate success or not
            ->assertPresent('.login-message')
            ->assertPresent('.login');
    }

    public function caseSuccess($browser)
    {
        $browser->assertPresent('.login')
        // fullfill feild
            ->type('username', 'test@gmail.com')
            ->type('password', '123456789')->releaseMouse()
            ->press('.btn_submit')
            ->pause(1000)
            ->waitFor('.navbar-app', 10)
            // check correct login
            ->assertPresent('.logo-navbar')->pause(2000);
    }
}
