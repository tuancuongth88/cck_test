<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\HR;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\Passion;
use App\Models\PassionWork;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WorkTest extends DuskTestCase
{
    use WorkSearch;
    use WithFaker;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->faker = \Faker\Factory::create();
        $this->fakeDataJob();
        $this->browse(function ($browser) {
////            $this->caseListWork($browser);
////            $this->caseListWorkForHR($browser);
////            $this->caseDetailWork($browser);
////            $this->caseEditWork($browser);
////            $this->caseCreateWork($browser);
//            $this->caseWorkSearchForHr($browser);
            $this->caseSearchForCompany($browser);
        });
    }

    public function caseListWork(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('job/list')->pause(2000);
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
            $dataIdList = [];
            foreach ($attributes as $attribute) {
                $dataIdList[] = explode("\n", $attribute->getText())[0];
            }

            $count = count($dataIdList);
            foreach ($dataIdList as $key => $value) {
                $key = $key < $count - 2 ? $key + 1 : $count - 1;
                $nextText = $dataIdList[$key];
                $this->assertTrue($value >= $nextText);
            }
            $this->checkListWithPermission($browser, $type);
            $browser->scrollIntoView('.pagination')
                ->click('[aria-label="Go to next page"]')
                ->pause(2000)
                ->scrollIntoView('.pagination')
                ->assertSeeIn('.pagination .active', '2')
                ->pause(2000)
                ->click('[aria-label="Go to previous page"]')->waitUntilMissing('.loading')
                ->assertSeeIn('.pagination .active', '1')
                ->pause(2000)
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    public function caseListWorkForHR(Browser $browser)
    {
        $types = [\HR, HR_MANAGER];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('/job-search')->pause(2000)
                ->click('.btn-search-vs-conditions')->pause(5000)
                ->assertSee('求人情報検索一覧')
//                ->assertSee('お気に入りに追加済')
                ->assertSee('詳細を見る')
                ->click('.added-to-favorites')->pause(5000)
                ->click('#go-to-detail-job')->pause(5000)
                ->click('.back-to-list-button')->pause(5000)
                ->click('.btn-logout')->pause(5000);
        }
    }

    private function detailWorkWithHr(Browser $browser)
    {
        $browser->assertSee('求人情報')
            ->assertSee('一覧に戻る')
            ->assertSee('求人情報')
            ->scrollIntoView('.btn-move-to-entry')
            ->assertSee('エントリーに進む');

        $browser->scrollIntoView('.back-to-list-button')
            ->click('.back-to-list-button')->pause(2000)
            ->assertSee('求人情報検索一覧')
            ->click('#go-to-detail-job')->pause(5000)
            ->scrollIntoView('.btn-move-to-entry')
            ->click('.btn-move-to-entry')->pause(5000)
            ->assertSee('求人エントリー')->pause(1000)
            ->assertSee('エントリー人材選択')->pause(1000)
            ->assertSee('エントリーに進む')->pause(1000)
            ->click('.btn-back')->pause(3000)->assertSee('求人情報');
    }

    private function detailWorkWithCompany(Browser $browser)
    {
        $browser->assertSee('求人詳細')
            ->assertSee('一覧に戻る')
            ->assertSee('編集')
            ->assertSee('求人情報');

        $browser->click('.back-to-list-button')->pause(2000)
            ->assertSee('求人一覧')
            ->click('.btn-go-detail')->pause(2000)
            ->click('.save-button')->pause(5000)
            ->click('.save-button')->pause(2000)
            ->assertSee('編集が成功しました');
    }

    public function caseDetailWork(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            switch ($type) {
                case HR_MANAGER:
                case \HR:
                    $browser->visit('job-search/list')->pause(5000)
                        ->click('#go-to-detail-job')->pause(5000);
                    $this->detailWorkWithHR($browser);
                    break;
                case SUPER_ADMIN:
                case COMPANY_MANAGER:
                case \COMPANY:
                    $browser->visit('job/list')->pause(5000)
                        ->click('.btn-go-detail')->pause(5000);
                    $this->detailWorkWithCompany($browser);
                    break;
            }

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    public function caseEditWork(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('job/list')->pause(2000)
            ->click('.btn-go-detail')->pause(3000)
            ->click('.save-button')->pause(3000)
            ->assertSee('求人編集')
            ->select('@company_list', 1)->pause(1000)
            ->click('.save-button')->pause(2000)
            ->assertSee('編集が成功しました');
        $browser->scrollIntoView('.btn-logout')
            ->click('.btn-logout');

    }

    public function caseCreateWork(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('job/create')->pause(5000);

        $browser->type('@job_title', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@job_title', 'job title')->pause(1000);

        $browser->select('@company_id', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->select('@company_id', '1')->pause(1000);


        $browser->click('@application_period_start')->pause(1000)
            ->click('div.b-calendar-grid.form-control.h-auto.text-center > div.b-calendar-grid-body > div:nth-child(3) > div:nth-child(5) > span')
            ->click('@application_period_end')->pause(1000)
            ->click('div.b-calendar-grid.form-control.h-auto.text-center > div.b-calendar-grid-body > div:nth-child(4) > div:nth-child(4) > span')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000);

        $browser->select('@major_classification', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->select('@major_classification', '1')->pause(1000);

        $browser->select('@middle_classification', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->select('@middle_classification', '1')->pause(1000);

        $browser->type('@job_description', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@job_description', 'job description')->pause(1000);

        $browser->type('@application_condition', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@application_condition', 'application condition')->pause(1000);

        $browser->type('@application_requirement', '')
            ->click('.save-button')->pause(1000)
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@application_requirement', 'application requirement')->pause(1000);

        $browser->scrollIntoView('@country')
            ->select('@country', '')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@country')
            ->assertSee('入力してください。')->pause(1000)
            ->select('@application_requirement', '1')->pause(1000);

        $browser->scrollIntoView('@language_requirement')->pause(1000)
            ->click('#language_requirement > div:nth-child(1) > label')->pause(1000)
            ->click('#language_requirement > div:nth-child(2) > label')->pause(1000);

        $browser->scrollIntoView('@other_skill')
            ->type('@other_skill', '')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@other_skill')
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@other_skill', 'other skill')->pause(1000);

        $browser->scrollIntoView('@form_of_employment')
            ->click('#form_of_employment > div:nth-child(1) > label')->pause(1000);

        $browser->scrollIntoView('@working_time_from')
            ->select('@working_time_from', '08:00')
            ->select('@working_time_to', '18:00')->pause(1000);

        $browser->scrollIntoView('@vacation')
            ->type('@vacation', '')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@vacation')
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@vacation', 'vacation')->pause(1000);

        $browser->scrollIntoView('@expected_income')
            ->type('@expected_income', '')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@expected_income')
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@expected_income', 400)->pause(1000);


        $browser->scrollIntoView('@working_place')
            ->click('@working_place')->pause(1000)
            ->click('#null-3')->pause(1000);


        $browser->scrollIntoView('@treatment_welfare')
            ->type('@treatment_welfare', '')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@treatment_welfare')
            ->assertSee('入力してください。')->pause(1000)
            ->typeSlowly('@treatment_welfare', 'treatment welfare')->pause(1000);


        $browser->scrollIntoView('@bonus_pay')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@bonus_pay')
            ->assertSee('入力してください。')->pause(1000)
            ->click('#bonus_pay > div:nth-child(1) > label')->pause(1000);

        $browser->scrollIntoView('@overtime')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@overtime')
            ->assertSee('入力してください。')->pause(1000)
            ->click('#overtime > div:nth-child(1) > label')->pause(1000);

        $browser->scrollIntoView('@transfer')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@transfer')
            ->assertSee('入力してください。')->pause(1000)
            ->click('#transfer > div:nth-child(1) > label')->pause(1000);

        $browser->scrollIntoView('@passive_smoking')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@passive_smoking')
            ->assertSee('入力してください。')->pause(1000)
            ->click('#passive_smoking > div:nth-child(1) > label')->pause(1000);

        $browser->scrollIntoView('@interview_follow')
            ->scrollIntoView('.save-button')
            ->click('.save-button')->pause(1000)
            ->scrollIntoView('@interview_follow')
            ->assertSee('入力してください。')->pause(1000)
            ->select('@interview_follow', 1)->pause(1000);

        $browser->scrollIntoView('.save-button')
            ->click('.save-button')->pause(2000)
            ->assertSee('作成が成功しました。');
    }

    private function checkListWithPermission(Browser $browser, $type)
    {
        switch ($type) {
            case SUPER_ADMIN:
            case COMPANY_MANAGER:
            case COMPANY:
                $browser->assertSee('求人一覧')
                    ->assertSee('チェックしたものを一括削除')
                    ->assertSee('新規登録')
                    ->assertSee('この条件で検索する')
                    ->assertSee('ID')
                    ->assertSee('求人名')
                    ->assertSee('企業名')
                    ->assertSee('ステータス')
                    ->assertSee('更新日')
                    ->assertSee('詳細')
                    ->click('.collapse-bar-btn')->pause(1000)
                    ->click('.collapse-bar-btn')->pause(1000);
                break;
            case HR_MANAGER:
                break;
            case \HR:

        }
    }

    public function caseWorkSearchForHr(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            if($type == \HR || $type == HR_MANAGER) {
                $browser->visit('job-search')->pause(2000);
                $majorClass = Work::query()->inRandomOrder()->first()->major_classification_id;
                $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id');
                $beforeSelector = '#dusk_parent_option_job > div:nth-child('.$majorClass.')';
                $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
                $this->searchByCheckWork($browser, $type, '@select_job_btn', $selector, Work::MIDDLE_CLASSIFICATION_ID, $middleClass, $beforeSelector, '職種を選ぶ', '@btn_reflect_job');

                $browser->visit('job-search')->pause(2000);
                $this->searchWithIncome($browser, $type, true, false, false);

                $browser->visit('job-search')->pause(2000);
                $city = rand(1,6);
                $btnSelect = '#dusk_city_list > div > div.multiselect__tags';
                $selector = '#dusk_city_list ul li:nth-child(' . ($city + 1) . ')';
                $this->searchByCheckWork($browser, $type, $btnSelect, $selector, Work::CITY_ID, [$city], null, null, null, false);

                $browser->visit('job-search')->pause(2000);
                $japanList = LanguageRequirement::query()->pluck('id');
                $japan = $this->faker->randomElements($japanList, 2);
                $btnSelect = '#dusk_japan_levels > div div:nth-child('.$japan[0].') > label';
                $selector = '#dusk_japan_levels > div div:nth-child('.$japan[1].') > label';
                $this->searchByCheckWork($browser, $type, $btnSelect, $selector, LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID, $japan);

                $browser->visit('job-search')->pause(2000);
                $work_forms = $this->faker->randomElements(HRS_WORK_FORM, 2);
                $btnSelect = '#dusk_work_forms div > div:nth-child('.$work_forms[0].')';
                $selector = '#dusk_work_forms div > div:nth-child('.$work_forms[1].')';
                $this->searchByCheckWork($browser, $type, $btnSelect, $selector, Work::FORM_OF_EMPLOYMENT, $work_forms);

                $browser->visit('job-search')->pause(2000);
                $passionList = Passion::query()->pluck('id');
                $passion = $this->faker->randomElements($passionList, 2);
                $btnSelect = '#dusk_passion_works div > div:nth-child('.$passion[0].')';
                $selector = '#dusk_passion_works div > div:nth-child('.$passion[1].')';
                $this->searchByCheckWork($browser, $type, $btnSelect, $selector, PassionWork::PASSION_ID, $passion);

                $browser->visit('job-search')->pause(2000);
                $this->searchWithTextWork($browser, $type);
            }

            $browser->visit('job-search/list')->pause(2000);
            $majorClass = Work::query()->inRandomOrder()->first()->major_classification_id;
            $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id');
            $beforeSelector = '#dusk_parent_option_job > div:nth-child('.$majorClass.')';
            $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
            $this->searchByCheckWork($browser, $type, '@select_job_btn', $selector, Work::MIDDLE_CLASSIFICATION_ID, $middleClass, $beforeSelector, '職種を選ぶ', '@btn_reflect_job');

            $this->searchWithIncome($browser, $type, true, false);

            $city = rand(1,6);
            $beforeSelector = '#dusk_city_id  div div > div > div > div.multiselect__tags';
            $selector = '#dusk_city_id ul li:nth-child(' . ($city + 1) . ')';
            $this->searchByCheckWork($browser, $type, '@btn_working_place', $selector, Work::CITY_ID, [$city], $beforeSelector, '都道府県を選ぶ');

            $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
            $this->searchByCheckWork($browser, $type, '@btn_japan_levels', '#dusk_japan_levels div:nth-child('.$japan.') > label', LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID, [$japan]);

            $work_forms = HRS_WORK_FORM[array_rand(HRS_WORK_FORM)];
            $this->searchByCheckWork($browser, $type, '@btn_work_forms', '#dusk_work_forms div > div:nth-child('.$work_forms.') label', Work::FORM_OF_EMPLOYMENT, [$work_forms]);

            $passion = PassionWork::query()->inRandomOrder()->first()->passion_id;
            $this->searchByCheckWork($browser, $type, '@btn_passion_works', '#dusk_passion_works div > div:nth-child('.$passion.') label', PassionWork::PASSION_ID, [$passion]);

            $this->searchWithTextWork($browser, $type);
            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    public function caseSearchForCompany(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('job/list')->pause(2000);

            $majorClass = Work::query()->inRandomOrder()->first()->major_classification_id;
            $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id');
            $beforeSelector = '#dusk_parent_option_job > div:nth-child('.$majorClass.')';
            $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
            $this->searchByCheckWork($browser, $type, '@select_job_btn', $selector, Work::MIDDLE_CLASSIFICATION_ID, $middleClass, $beforeSelector, '職種を選ぶ', '@btn_reflect_job', false, false);

            $this->searchWithIncome($browser, $type, false, false);

            $city = rand(1,6);
            $beforeSelector = '#dusk_city_id  div div > div > div > div.multiselect__tags';
            $selector = '#dusk_city_id ul li:nth-child(' . ($city + 1) . ')';
            $this->searchByCheckWork($browser, $type, '@btn_working_place', $selector, Work::CITY_ID, [$city], $beforeSelector, '都道府県を選ぶ', null, true, false);

            $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
            $this->searchByCheckWork($browser, $type, '@btn_japan_levels', '#dusk_japan_levels div:nth-child('.$japan.') > label', LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID, [$japan], null, null, null, false, false);

            $work_forms = HRS_WORK_FORM[array_rand(HRS_WORK_FORM)];
            $this->searchByCheckWork($browser, $type, '@btn_work_forms', '#dusk_work_forms div > div:nth-child('.$work_forms.') label', Work::FORM_OF_EMPLOYMENT, [$work_forms], null, null, null, false, false);

            $passion = PassionWork::query()->inRandomOrder()->first()->passion_id;
            $this->searchByCheckWork($browser, $type, '@btn_passion_works', '#dusk_passion_works div > div:nth-child('.$passion.') label', PassionWork::PASSION_ID, [$passion], null, null, null, false, false);

            $this->searchWithTextWork($browser, $type, false);
            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }
}
