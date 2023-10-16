<?php

namespace Tests\Browser\Scenario;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\CustomPage;
use Tests\DuskTestCase;
use function Symfony\Component\String\b;

class CaseF extends DuskTestCase
{
    use RefreshDatabase;
    private $company;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testGeneral()
    {
        $this->seed();
        $this->fakeData();
        $this->browse(function (Browser $browser) {
            $this->caseCreateJob($browser);
            $this->case10_14($browser);
            $this->changeStatusRecruiting($browser);
            $this->searchJobForHR($browser);
        });
    }



    public function caseCreateJob(Browser $browser)
    {
        $types = [COMPANY_MANAGER, SUPER_ADMIN];
        foreach ($types as $type){
            $this->login($type);
            $browser->click('.dusk-company')->click('.dusk-job')->pause(5000)
                ->assertPathIs('/job/list');
            $browser->click('.btn_save--custom')->pause(5000)
                ->assertPathIs('/job/create')->pause(5000)
                ->type('@job_title', 'ITエンジニア')->pause(5000)
                ->select('@company_id', $this->company->id);
            $browser->click('@application_period_start')->pause(1000)
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Next month"]')
                ->click('div.b-calendar-grid.form-control.h-auto.text-center > div.b-calendar-grid-body > div:nth-child(5) > div:nth-child(5) > span')
                ->click('@application_period_end')->pause(1000)
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Next month"]')
                ->click('div.b-calendar-grid.form-control.h-auto.text-center > div.b-calendar-grid-body > div:nth-child(5) > div:nth-child(6) > span')
                ->select('@major_classification', '1')->pause(1000)
                ->select('@middle_classification', '1')->pause(1000)
                ->scrollIntoView('@job_description')
                ->type('@job_description', 'ITエンジニアのFEとしてシステム開発')->pause(1000)
                ->scrollIntoView('@application_condition')
                ->type('@application_condition', '3年以上の経験必須')->pause(1000)
                ->scrollIntoView('@application_requirement')
                ->type('@application_requirement', 'マネージメント経験があるとなお良し')->pause(1000)
                ->scrollIntoView('@country')
                ->select('@country', '1')->pause(1000)
                ->scrollIntoView('@language_requirement')->pause(1000)
                ->click('#language_requirement > div:nth-child(1) > label')->pause(1000)
                ->click('#language_requirement > div:nth-child(2) > label')->pause(1000)
                ->click('#language_requirement > div:nth-child(3) > label')->pause(1000)
                ->scrollIntoView('@other_skill')
                ->typeSlowly('@other_skill', '日本語、ベトナム語')
                ->scrollIntoView('@preferred_skill')
                ->typeSlowly('@preferred_skill', 'ビジネスレベル英語');

            $browser->scrollIntoView('@form_of_employment')
                ->click('#form_of_employment > div:nth-child(1) > label')->pause(1000);

            $browser->select('@working_time_from', '09:00')
                ->select('@working_time_to', '17:00')->pause(1000);

            $browser->type('@vacation', '土日祝日');

            $browser->scrollIntoView('@expected_income')
                ->type('@expected_income', '500');

            $browser->scrollIntoView('@working_place')
                ->click('@working_place')->pause(1000)
                ->click('#null-29')->pause(1000);

            $browser->type('@working_place_detail', '大阪環状線「大阪駅」より徒歩5分')
                ->type('@treatment_welfare', '保険加入、住宅手当・家賃補助有り、通勤手当有り')
                ->type('@company_pr_appeal', 'インターナショナルで活気のある雰囲気です');

            $browser->scrollIntoView('@bonus_pay')
                ->click('#bonus_pay > div:nth-child(1) > label')->pause(1000);

            $browser->click('#overtime > div:nth-child(1) > label')->pause(1000);

            $browser->click('#transfer > div:nth-child(1) > label')->pause(1000);

            $browser->click('#passive_smoking > div:nth-child(1) > label')->pause(1000);

            $browser->scrollIntoView('@interview_follow')
                ->select('@interview_follow', 1)->pause(1000);

            $browser->scrollIntoView('.save-button')
                ->click('.save-button')->pause(5000)
                ->assertSee('募集期間外')
                ->assertSee('Hanoi Company');
            $work = Work::query()->latest('id')->first();
            //step 5 edit job
            $browser->click('.btn-go-detail')->pause(5000)
                ->waitFor('.btn_save--custom')
                ->click('.btn_save--custom')->pause(5000)
                ->assertPathIs('/job/edit/'.$work->id);
            $currentMonth = Carbon::now()->format('m');
            //step 6 update
            $browser->click('@application_period_start')->pause(1000)->click('button:nth-child(5)')
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Previous month"]')
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Previous month"]')
                ->click('.b-form-date-calendar div[data-date="2024-' . $currentMonth . '-01"]');

            $browser->click('@application_period_end')->pause(1000)->click('button:nth-child(5)')
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Previous month"]')
                ->click('.b-form-date-calendar  .b-calendar-nav button[aria-label="Previous month"]')
                ->click('.b-form-date-calendar div[data-date="2024-' . $currentMonth . '-29"]')
                ->click('.btn_save--custom')->pause(5000)
                ->visit('/job/list')
                ->assertSee('募集期間外');
            //case 9 Job Detail
            $browser->click('.btn-logout');
        }
    }

    public function case10_14(Browser $browser){
        $types = [HR, HR_MANAGER];
        foreach ($types as $type) {
            $browser->visit('home')->pause(3000);
            $this->login($type);
            $browser->assertPathIs('/home');
            //case 11,12,13,14 search job
            $browser->visit('/job-search')->pause(5000)
                ->click('@btn_search_with_conditions')->pause(5000)
                ->assertSee('求人情報検索一覧')
                ->assertPathIs('/job-search/list')
                ->assertDontSee('ITエンジニア');
            $this->logout();
        }
    }

    public function changeStatusRecruiting(Browser $browser)
    {
        $browser->visit('/home')->pause(5000);
        $this->login(SUPER_ADMIN);
        $browser->pause(5000)
            ->assertSee('新着メッセージ')
            ->assertPathIs('/home');
        //case 16 edit job
        $browser->visit('/job/list')->pause(5000)
            ->assertPathIs('/job/list');
            //case 17
        if (Work::query()->count() > 0){
            $browser->waitFor('.btn-go-detail')
                ->click('.btn-go-detail')->pause(5000)
                ->assertPathIs('/job/detail/1')
                ->waitFor('.btn_save--custom')
                ->click('.btn_save--custom')->pause(5000)
                ->assertPathIs('/job/edit/1');

            $browser->click('@application_period_start')->pause(1000)
                ->click('.b-calendar-nav > button:nth-child(1)')
                ->click('.b-form-date-calendar div[data-date="'.Carbon::now()->format('Y-m-d').'"]');

            $browser->click('@application_period_end')->pause(1000)
                ->click('.b-calendar-nav > button:nth-child(1)')
                ->click('.b-form-date-calendar div[data-date="'.Carbon::now()->lastOfMonth()->format('Y-m-d').'"]');


            $browser->click('.btn_save--custom')->pause(5000);
            $this->gotoJobList($browser);
            $browser->assertSee('募集中');
        }
        $this->logout();
    }

    public function searchJobForHR(Browser $browser){
       $this->searchJobForUser($browser, HR);
       $this->searchJobForUser($browser, HR_MANAGER);
    }

    private function searchJobForUser($browser, $user){
        $this->login($user);
        $browser->click('.dusk-company')->pause(5000)
            ->assertPathIs('/job-search')
            ->click('@btn_search_with_conditions')->pause(5000)
            ->assertPathIs('/job-search/list')
            ->assertSee('求人情報検索一覧')
            ->click('#go-to-detail-job')->pause(5000)
            ->assertSee('ITエンジニア');
        $this->logout();
    }

    private function gotoJobList(Browser $browser){
        $browser->click('.dusk-company')->click('.dusk-job')->pause(5000);
    }

    public function fakeData(){
        $majorId = JobType::query()
            ->where('type', JOB_TYPE)
            ->inRandomOrder()
            ->value('id');
        $middleId = JobInfo::query()
            ->where('job_type_id', $majorId)
            ->value('id');
        $user = User::factory()->create(['type' => COMPANY, 'mail_address' => 'hanoicompany@gmail.com']);
        $dataTest = [
            Company::COMPANY_NAME => 'Hanoi Company',
            Company::COMPANY_NAME_JP => 'Hanoi Company',
            Company::USER_ID => $user->id,
            Company::MAJOR_CLASSIFICATION => $majorId,
            Company::MIDDLE_CLASSIFICATION => $middleId,
            Company::POST_CODE => '1020093',
            Company::PREFECTURES => '東京都',
            Company::MUNICIPALITY => '千代田区平河町',
            Company::NUMBER => '1-7-10',
            Company::OTHER => '大盛丸平河町ビル2階',
            Company::TELEPHONE_NUMBER => '+84 0312345678',
            Company::MAIL_ADDRESS => "hanoicompany@gmail.com",
            Company::URL => 'https://okuridashi_hanoi.com',
            Company::JOB_TITLE => '代表取締役会長',
            Company::FULL_NAME => '鈴木　太郎',
            Company::FULL_NAME_FURIGANA => 'スズキ　タロウ',
            Company::REPRESENTATIVE_CONTACT => '+84 0312345678',
            Company::ASSIGNEE_CONTACT => '+84 0312345678',
            Company::STATUS => CONFIRM,
        ];
        $this->company = Company::factory()->create($dataTest);
    }
}
