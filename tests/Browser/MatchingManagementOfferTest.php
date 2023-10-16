<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\Offer;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MatchingManagementOfferTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    use WithFaker;

    public function testExample()
    {
        {
            $this->browse(function ($browser) {
//                $this->fakeDataSelectOffer($browser);
//                $this->caseListOffer($browser);
//                $this->caseSortOffer($browser);
//                $this->caseShowDetailConfirm($browser);
//                $this->caseShowDetailDecline($browser);
//                $this->casePaginateOffer($browser);
                $this->caseDeleteOffer($browser);
            });
        }
    }

    private function caseListOffer(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->assertSee('マッチング管理')
                ->assertSee('オファー')
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(5000);
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="offer-table-list"]/tbody/tr'));
            $dataCompanyList = [];
            foreach ($attributes as $attribute) {
                $dataRow = explode("\n", $attribute->getText());
                $dataCompanyList[] = $dataRow[1];
            }
            foreach ($dataCompanyList as $name) {
                $browser->assertSee($name);
            }
            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function sortData(Browser $browser, $sortBy, $index)
    {
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="offer-table-list"]/tbody/tr'));
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataTable = explode("\n", $attribute->getText());
            if ($index == 0 || $index == 1) {
                $dataCompanyList[] = explode(" ", $dataTable[0])[$index];
            } else {
                $dataCompanyList[] = $dataTable[$index];
            }
        }
        foreach ($dataCompanyList as $key => $value) {
            $key = $key < count($dataCompanyList) - 2 ? $key + 1 : count($dataCompanyList) - 1;
            $nextText = $dataCompanyList[$key];
            if ($sortBy == 'asc') {
                if ($index == 0)
                    $this->assertTrue($value <= $nextText);
                else
                    $this->assertTrue(strcmp($value, $nextText) <= 0);
            } else {
                if ($index == 0)
                    $this->assertTrue($value >= $nextText);
                else
                    $this->assertTrue(strcmp($value, $nextText) >= 0);
            }
        }
    }

    private function caseSortOffer(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->pause(3000)
                ->visit('/matching-management')->pause(3000)
                ->assertSee('マッチング管理')
                ->assertSee('オファー')->pause(1000)
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(6000);

            $this->sortData($browser, 'desc', 0);
            $browser->click('#offer-table-list thead tr th:nth-child(2)')->pause(3000);
            $this->sortData($browser, 'asc', 0);

            $browser->click('#offer-table-list thead tr th:nth-child(3)')->pause(5000);
            $this->sortData($browser, 'asc', 1);
            $browser->click('#offer-table-list thead tr th:nth-child(3)')->pause(5000);
            $this->sortData($browser, 'desc', 1);

            $browser->click('#offer-table-list thead tr th:nth-child(4)')->pause(5000);
            $this->sortData($browser, 'asc', 2);
            $browser->click('#offer-table-list thead tr th:nth-child(4)')->pause(5000);
            $this->sortData($browser, 'desc', 2);

            $browser->click('#offer-table-list thead tr th:nth-child(5)')->pause(5000);
            $this->sortData($browser, 'asc', 3);
            $browser->click('#offer-table-list thead tr th:nth-child(5)')->pause(5000);
            $this->sortData($browser, 'desc', 3);

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function caseShowDetailConfirm(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER, \COMPANY, \HR];
        $id = 25;
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(6000);

            $browser->click('#offer-table-list tbody tr:nth-child(1) #btn-go-detail')->pause(3000)
                ->assertSee('オファー');

            if (in_array($type, [SUPER_ADMIN, HR_MANAGER, \HR])) {
                $browser->assertSee('このオファーを承諾しますか？')
                    ->assertButtonEnabled('@btn_confirm_offer')
                    ->click('@btn_confirm_offer')->pause(2000)
                    ->assertSee('承諾してよろしいですか？')
                    ->click('#offer-modal-requesting-confirm button.btn.mx-1.btn-primary')->pause(1000)
                    ->waitFor('.toast-body')
                    ->assertSee('承認しました。')
                    ->pause(5000);

                $offer = Offer::select('offers.*', "hrs.full_name", "works.title")
                    ->join('hrs', 'hrs.id', '=', "offers.hr_id")
                    ->join('works', 'works.id', '=', "offers.work_id")
                    ->where('hrs.status', '<>', HRS_STATUS_PRIVATE)
                    ->where("offers.id", $id)->first();
                $id--;

                $browser->scrollIntoView('#dusk_matching ul li:nth-child(3)')
                    ->click('#dusk_matching ul li:nth-child(3)')
                    ->pause(6000)
                    ->assertSeeIn('#interview-table', $offer['full_name'])
                    ->assertSeeIn('#interview-table', $offer['title']);
            } else {
                $browser->assertDontSee('このオファーを承諾しますか？')
                    ->assertMissing('@btn_confirm_offer')
                    ->visit('/matching-management')->pause(2000);
            }

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function caseShowDetailDecline(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR, COMPANY_MANAGER, \COMPANY];
        $i = 1;
        foreach ($types as $type) {
            $text = $this->faker->text();
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(6000);

            if (in_array($type, [SUPER_ADMIN, HR_MANAGER, \HR])) {
                $browser->click('#offer-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                    ->pause(3000)
                    ->assertSee('オファー')
                    ->assertSee('このオファーを承諾しますか？')
                    ->assertButtonEnabled('@btn_decline_offer')
                    ->click('@btn_decline_offer')
                    ->pause(2000)
                    ->assertSee('本当に辞退しますか？')
                    ->click('#dusk_reason_offer div > div:nth-child(10) > label')
                    ->click('@btn_decline_offer')
                    ->assertSee('入力してください')
                    ->type('@decline_note_offer', $text)
                    ->click('@btn_decline_offer')->pause(2000)
                    ->waitFor('.toast-body')
                    ->assertSee('辞退しました。')
                    ->pause(2000)
                    ->assertSee('マッチング管理');

                $browser->click('#offer-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                    ->pause(3000)
                    ->assertSee('辞退')
                    ->assertSee('辞退理由')
                    ->assertSee($text)->pause(2000);
                $i++;
            } else {
                $browser->click('#offer-table-list tbody tr:nth-child(1) #btn-go-detail')
                    ->pause(3000)
                    ->assertSee('オファー')
                    ->assertDontSee('このオファーを承諾しますか？')
                    ->assertMissing('@btn_decline_offer')
                    ->pause(3000)
                    ->assertSee('辞退')
                    ->assertSee('辞退理由')
                    ->visit('/matching-management')->pause(2000);
            }
            $browser->visit('/matching-management')->pause(2000)
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function casePaginateOffer(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $items = Offer::select("*", "offers.id", 'hrs.full_name', "offers.status")
                ->join('hrs', 'hrs.id', '=', "offers.hr_id")
                ->join('works', 'works.id', '=', "offers.work_id")
                ->whereNull('hrs.deleted_at')
                ->whereNull('works.deleted_at')
                ->where("offers.status", "!=", OFFER_STATUS_CONFIRM)
                ->whereIn('offers.display', ['on', 'stop']);
            if ($type == HR) {
                $items->where('hrs.user_id', User::where(User::TYPE, $type)->first()->id);
            }
            if ($type == COMPANY) {
                $items->where('works.user_id', User::where(User::TYPE, $type)->first()->id);
            }
            $records = $items->count();

            $browser->visit('/matching-management')->pause(5000)
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(5000);
            if ($records > 20) {
                $browser->scrollIntoView('.pagination')->pause(5000)
                    ->assertSee($records . '件の1から20を表示')
                    ->assertSelected('@selected_record', 20)
                    ->assertSeeIn('.pagination', '2');
                $browser->select('@selected_record', 10)->pause(5000);

                $browser->scrollIntoView('.pagination')
                    ->assertSee($records . '件の1から10を表示')
                    ->assertSelected('@selected_record', 10)
                    ->assertSeeIn('.pagination', '2');
            } else {
                $browser->scrollIntoView('.pagination')->pause(5000)
                    ->assertSee($records . '件の1から' . $records . 'を表示')
                    ->assertSelected('@selected_record', 20)
                    ->assertDontSeeIn('.pagination', '2');
                $browser->select('@selected_record', 10)->pause(5000)
                    ->assertSelected('@selected_record', 10)
                    ->pause(5000);

                if ($records >= 10) {
                    $browser->scrollIntoView('.pagination')
                        ->assertSee($records . '件の1から10を表示')
                        ->assertSeeIn('.pagination', '2');
                } else {
                    $browser->scrollIntoView('.pagination')
                        ->assertSee($records . '件の1から' . $records . 'を表示')
                        ->assertDontSeeIn('.pagination', '2');
                }
            }
            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function caseDeleteOffer(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->fakeDataSelectOffer($browser);
            $this->login($type);
            $browser->visit('/matching-management')->pause(5000)
                ->scrollIntoView('#dusk_matching ul li:nth-child(2)')
                ->click('#dusk_matching ul li:nth-child(2)')
                ->pause(5000);

            $browser->click('@btn_delete_all')
                ->waitFor('.toast-body')
                ->assertSee('削除するデータを選択してください')
                ->pause(2000);

            $browser->click('#offer-table-list thead tr th:nth-child(1)')
                ->click('@btn_delete_all')->pause(2000)
                ->assertSee('本当に削除しますか？')
                ->click('@btn_accept_delete')
                ->waitFor('.toast-body')
                ->assertSee('削除しました');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function fakeDataSelectOffer($browser)
    {
        $this->faker = \Faker\Factory::create();
        $this->fakeDataHr();
        $this->fakeDataOffer();
    }

    private function fakeDataJob()
    {
        $user = User::query()->where('type', \COMPANY)->first();
        $company = Company::query()->where('user_id', $user->id)->first();
        if (!$user) {
            $user = User::factory()->create([User::TYPE => COMPANY_MANAGER]);
        }
        if (!$company) {
            $company = Company::factory()->create(['user_id' => $user->id]);
        }
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        for ($i = 0; $i <= 20; $i++) {
            $dataTest = [
                Work::TITLE => $this->faker->name,
                Work::USER_ID => $user->id,
                Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
                Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
                Work::COMPANY_ID => $company->id,
                Work::APPLICATION_PERIOD_FROM => $this->faker->dateTimeBetween('-3 months', 'today')->format('Y-m-d'),
                Work::APPLICATION_PERIOD_TO => $this->faker->dateTimeBetween('today', '+3 months')->format('Y-m-d'),
                Work::JOB_DESCRIPTION => $this->faker->text,
                Work::APPLICATION_CONDITION => $this->faker->text,
                Work::APPLICATION_REQUIREMENT => $this->faker->text,
                Work::OTHER_SKILL => $this->faker->text(200),
                Work::FORM_OF_EMPLOYMENT => $this->faker->randomElement([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE]),
                Work::WORKING_TIME_FROM => Carbon::now()->format('H:i'),
                Work::WORKING_TIME_TO => Carbon::now()->addHour(8)->format('H:i'),
                Work::VACATION => $this->faker->text(200),
                Work::EXPECTED_INCOME => rand(100000, 999999),
                Work::CITY_ID => 1,
                Work::TREATMENT_WELFARE => $this->faker->text(100),
                Work::BONUS_PAY_RISE => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::OVER_TIME => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::TRANSFER => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::INTERVIEW_FOLLOW => rand(1, 5),
            ];
            $work = Work::factory()->create($dataTest);
            $this->addRelationHasMany(LanguageRequirementWork::class, [1, 2, 3],
                $work->id, 'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, [1, 2, 3], $work->id, 'passion_id');
        }
    }

    private function addRelationHasMany($model, $items, $id, $column)
    {
        $model::where('work_id', $id)->delete();
        $array = array();
        foreach ($items as $k => $item) {
            $array[$k]['work_id'] = $id;
            $array[$k][$column] = $item;
        }
        $model::insert($array);
    }

    protected function fakeDataHr()
    {
        $this->faker = \Faker\Factory::create();
        $company = User::query()->where(User::TYPE, \COMPANY)->first();
        Company::factory()->create([
            Company::USER_ID => $company->id,
            Company::MAIL_ADDRESS => $company->mail_address,
            Company::STATUS => CONFIRM
        ]);

        $companyManager = User::query()->where(User::TYPE, COMPANY_MANAGER)->first();
        Company::factory()->create([
            Company::USER_ID => $companyManager->id,
            Company::MAIL_ADDRESS => $companyManager->mail_address,
            Company::STATUS => CONFIRM
        ]);

        $hrManager = User::query()->where(User::TYPE, \HR_MANAGER)->first();
        HrOrganization::factory()->create([
            HrOrganization::USER_ID => $hrManager->id,
            HrOrganization::MAIL_ADDRESS => $hrManager->mail_address,
        ]);

        for ($i = 1; $i <= 25; $i++) {
            if ($i <= 6) {
                $userHr = User::query()->where(User::TYPE, \HR)->first()->id;
            } else
                $userHr = User::factory()->create([User::TYPE => \HR])->id;

            $hrOrg = HrOrganization::firstOrCreate(
                [HrOrganization::USER_ID => $userHr],
                HrOrganization::factory()->make()->toArray()
            );
            $name = $this->faker->name;
            $major_id = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
            $middle_id = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major_id)->inRandomOrder()->pluck('id')->first();

            $status = HRS_STATUS_PUBLIC;
            if ($i == 22) {
                $userHr = SUPER_ADMIN;
            }
            if ($i == 23) {
                $status = HRS_STATUS_PRIVATE;
            }
            if ($i == 24) {
                $status = HRS_STATUS_CONFIRM;
            }
            $work_form = $this->faker->randomElement(HRS_WORK_FORM);
            $hr_id = HR::create([
                HR::HR_ORGANIZATION_ID => $hrOrg->id,
                HR::USER_ID => $userHr,
                HR::FULL_NAME => $name,
                HR::FULL_NAME_JA => $name,
                HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
                HR::CREATED_BY => $userHr,
                HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-50 years', '-18 years'),
                HR::FINAL_EDUCATION_DATE => $this->faker->dateTimeBetween('-20 years')->format('Y-m'),
                HR::FINAL_EDUCATION_CLASSIFICATION => $this->faker->randomElement(HR_FINAL_EDUCATION),
                HR::FINAL_EDUCATION_DEGREE => $this->faker->randomElement(array_keys(HR_EDUCATION_DEGREES)),
                HR::WORK_FORM => $work_form,
                HR::PREFERRED_WORKING_HOURS => $work_form == HRS_FULL_TIME_EMPLOYEE ? null : rand(25, 60),
                HR::JAPANESE_LEVEL => LanguageRequirement::query()->inRandomOrder()->pluck('id')->first(),
                HR::MAJOR_CLASSIFICATION_ID => $major_id,
                HR::MIDDLE_CLASSIFICATION_ID => $middle_id,
                HR::MAIL_ADDRESS => $this->faker->email,
                HR::STATUS => $status
            ]);

            HRMainJobCareer::factory()->create([HRMainJobCareer::HRS_ID => $hr_id]);
        }
        $this->hrId = User::query()->where(User::TYPE, \HR)->first()->id;
    }

    private function fakeDataOffer()
    {
        $this->fakeDataJob();
        for ($i = 1; $i <= 25; $i++) {
            if ($i >= 20) {
                $hrId = HR::query()->where(HR::USER_ID, User::where(User::TYPE, \HR)->first()->id)
                    ->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;
                $status = 1;
            } else {
                $hrId = HR::query()->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;
                $status = 2;
            }
            $workId = Work::query()->where(Work::STATUS, WORK_STATUS_RECRUITING)->inRandomOrder()->first()->id;
            $text = ($status == OFFER_STATUS_DECLINE) ? $this->faker->text() : '';

            Offer::factory()->create([
                Offer::HR_ID => $hrId,
                Offer::WORK_ID => $workId,
                Offer::REMARKS => $this->faker->text(30),
                Offer::DISPLAY => 'on',
                Offer::STATUS => $status,
                Offer::REQUEST_DATE => $this->faker->dateTimeBetween('-1 months', 'today')->format('Y-m-d'),
                Offer::NOTE => $text
            ]);
        }
    }
}
