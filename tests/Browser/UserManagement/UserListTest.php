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
            $this->login();
            $this->viewList($browser);
            $this->searchFeild($browser);
            $this->listUser($browser);
            $this->checkOptionResult($browser);
            $this->checkButtonSignUp($browser);
            $this->checkButtonMultiDelete($browser);
            $this->checkSortTable($browser);
        });
    }

    public function viewList($browser)
    {
        $browser->visit('/#/usermanagement/list')
               // LOGIN and wait for site loading
            ->pause(1000)
            ->assertPresent('.display-user-management-list');
    }

    public function searchFeild($browser)
    {
        $browser->assertPresent('.zone-input-search')
        // fullfill feild
            ->pause(2000)
            ->typeSlowly('#search-user', 'test@gmail.com')
            ->pause(1000)
            ->assertSee('test@gmail.com')
            ->typeSlowly('#search-user', 't')
            ->pause(1000)
            ->clear('#search-user')
            ->pause(7000);

    }

    public function listUser($browser)
    {
        $browser->assertPresent('.display-table')
        ->assertPresent('.name')
        ->assertPresent('.email')
        ->assertPresent('.role')
        ->assertPresent('.result')
        ->assertSee('test@gmail.com');
    }

    public function checkSortTable($browser)
    {
        $browser->assertPresent('.display-table')
        ->pause(1000)
        ->click('.name')
        ->pause(2000)
        ->click('.email')
        ->pause(2000)
        ->click('.role')
        ->pause(2000)
        ->assertPresent('.display-table');
    }
    public function checkOptionResult($browser)
    {
        // button display
        $this->checkButton($browser,'.btn-detail','.display-user-management-detail');
        // button edit
        $this->checkButton($browser,'.btn-edit','.display-user-management-create');
        // button delete
        $browser->assertPresent('.display-table')
        ->pause(1000)
        ->assertPresent('.btn-delete-render')
        ->press('.btn-delete-render')
        ->pause(2000)
        ->assertPresent('.modal-header')
        ->pause(2000);
        if($browser->assertSee('Cancel')){
            $browser->press('Cancel');
        }
        else{
            $browser->press('キャンセル');
        }
    }

    public function checkButtonSignUp($browser)
    {
        $browser->assertPresent('.zone-control')
        ->pause(1000)
        ->assertPresent('.btn-add')
        ->press('.btn-add')
        ->pause(2000)
        ->assertPresent('.display-user-management-create')
        ->pause(2000)
        ->click('.fa-backward')
        ->pause(2000)
        ->assertPresent('.display-table');
    }

    public function checkButtonMultiDelete($browser)
    {
        $browser->assertPresent('.zone-control')
        ->pause(1000)
        ->assertPresent('.btn-delete')
        ->press('.btn-delete')
        ->pause(2000)
        ->assertPresent('.error_content')
        ->pause(2000)
        ->click('.close-tab')
        ->pause(2000)
        ->assertPresent('.display-table')
        ->check('checkbox-1')
        ->press('.btn-delete')
        ->pause(2000)
        ->assertPresent('.modal-header')
        ->pause(2000)
        ->click('.modal-btn-delete')
        ->pause(1000)
        ->assertPresent('.display-table');
    }

    private function checkButton($browser,$name,$see)
    {
        $browser->assertPresent('.display-table')
        ->pause(1000)
        ->assertPresent('.btn-detail')
        ->press($name)
        ->pause(5000)
        ->assertPresent($see)
        ->pause(1000)
        ->click('.fa-backward')
        ->pause(2000)
        ->assertPresent('.display-table');
    }


}
