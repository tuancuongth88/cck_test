<?php

namespace Tests\Browser\Scenario;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CaseG extends DuskTestCase
{
    use RefreshDatabase, WithFaker, FakeDataST;
    private $company;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->seed();
        $this->fakeDataCaseG();
        $this->browse(function (Browser $browser) {
            $this->testCaseG($browser);
        });
    }

    public function testCaseG(Browser $browser){
        $types = [HR, HR_MANAGER, SUPER_ADMIN];
        foreach ($types as $type) {
            $this->login($type);
            $this->testEntrySingle($browser);
            $this->logout();
        }
    }
    private function testEntrySingle(Browser $browser){
        $fileMovition = 'tests/FileTest/fileMovition1.png';
        $fileOther = 'tests/FileTest/fileOther1.png';

        $browser->visit('/job-search')->pause(5000)
            ->click('#dusk_city_list')->pause(1000)
            ->click('#null-29')->pause(1000)
            ->click('#dusk_passion_works > .bv-no-focus-ring > div:nth-child(5)')->pause(5000)
            ->click('@btn_search_with_conditions')->pause(5000)
            ->assertSee('ITエンジニア')
            ->assertSee('ITエンジニアのFEとしてシステム開発')
            ->assertSee('500')
            ->assertSee('大阪府')
            ->click('#go-to-detail-job')->pause(5000)
            ->assertPathIs('/job-search/detail/10')
            ->scrollIntoView('.btn-move-to-entry')
            ->click('.btn-move-to-entry')->pause(1000)
            ->assertPathIs('/job-search/select-entry-hr')
            ->click('.entry-hr-select-btn')->pause(5000)
            ->click('div.modal-select-hr-list > div.modal-select-hr-wrap > div.select-hr-list > div > div:nth-child(1)')
            ->scrollIntoView('.btn-confirm')->pause(1000)
            ->click('.btn-confirm')->pause(2000);
        $browser->click('.btn-move-to-entry')->pause(5000);
        $browser->attach('.form-page-area .form-item-row:nth-child(1) .flex-column .file-upload-area:nth-child(2) input',  $fileMovition)->pause(5000)
            ->attach('.form-page-area .form-item-row:nth-child(1) .flex-column .file-upload-area:nth-child(5) input',  $fileOther)->pause(10000)
            ->scrollIntoView('.job-detail-for-hr-form-btn .btn-move-to-entry:nth-child(2)')->pause(1000)
            ->click('.job-detail-for-hr-form-btn .btn-move-to-entry:nth-child(2)')->pause(5000)
            ->assertSee('求人情報 エントリー')
            ->click('div.job-detail-for-hr-form-btn > div > div:nth-child(2)')->pause(5000)
            ->assertSee('エントリーを送信しました。');
        $browser->visit('/home')->pause(5000)
            ->assertSee('エントリーが実行されました')
            ->click('#new-msg > tbody > tr > td.title > div > a')->pause(5000)
            ->visit('/matching-management')->pause(5000)
            ->click('.collapse-bar-btn')
            ->assertSee('申請中')->pause(5000)
            ->click('#btn-go-detail')->pause(5000)
            ->assertSee('ITエンジニア')
            ->assertSee('エントリー')
            ->click('.modal-header > button');
    }



}
