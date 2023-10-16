<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\PassionWork;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MatchingManagementEntryTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        {
            $this->browse(function ($browser) {
                $this->fakeDataSelectEntry($browser);
//                $this->login();
//                $this->caseListEntry($browser);
//                $this->caseSortEntry($browser);
//                $this->caseSortCode($browser);
//                $this->caseSortDate($browser);
//                $browser->scrollIntoView('.btn-logout')
//                    ->click('.btn-logout');
//                $this->caseShowDetailConfirm($browser);
//                $this->caseShowDetailReject($browser);
//                $this->caseShowDetailDecline($browser);
//                $this->casePaginateEntry($browser);
                $this->caseDeleteEntry($browser);
            });
        }
    }

    private function caseListEntry($browser)
    {
        $browser->pause(3000)
            ->visit('/matching-management')->pause(3000)
            ->assertSee('マッチング管理')
            ->assertSee('エントリー')->pause(1000);
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="entry-table-list"]/tbody/tr'));
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataRow = explode("\n", $attribute->getText());
            $dataCompanyList[] = $dataRow[1];
        }
        foreach ($dataCompanyList as $name) {
            $browser->assertSee($name);
        }
    }

    private function sortData(Browser $browser, $sortBy, $index)
    {
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="entry-table-list"]/tbody/tr'));
        $dataCompanyList = [];
        foreach ($attributes as $attribute) {
            $dataTable = explode("\n", $attribute->getText());
            $dataCompanyList[] = explode(" ", $dataTable[0])[$index];
        }
        foreach ($dataCompanyList as $key => $value) {
            $key = $key < count($dataCompanyList) - 2 ? $key + 1 : count($dataCompanyList) - 1;
            $nextText = $dataCompanyList[$key];
            if ($sortBy == 'asc') {
                if ($index != 1)
                    $this->assertTrue($value <= $nextText);
                else
                    $this->assertTrue(strcmp($value, $nextText) <= 0);
            } else {
                if ($index != 1)
                    $this->assertTrue($value >= $nextText);
                else
                    $this->assertTrue(strcmp($value, $nextText) >= 0);
            }
        }
    }

    private function caseSortEntry($browser)
    {
        $browser->pause(3000)
            ->visit('/matching-management')->pause(3000)
            ->assertSee('マッチング管理')
            ->assertSee('エントリー')->pause(1000);
        $this->sortData($browser, 'desc', 0);
        $browser->click('#entry-table-list thead tr th:nth-child(2)')->pause(3000);
        $this->sortData($browser, 'asc', 0);
    }

    private function caseSortCode($browser)
    {
        $browser->click('#entry-table-list thead tr th:nth-child(3)')->pause(3000);
        $this->sortData($browser, 'asc', 1);
        $browser->click('#entry-table-list thead tr th:nth-child(3)')->pause(3000);
        $this->sortData($browser, 'desc', 1);
    }

    private function caseSortDate($browser)
    {
        $browser->click('#entry-table-list thead tr th:nth-child(4)')->pause(3000);
        $this->sortData($browser, 'asc', 2);
        $browser->click('#entry-table-list thead tr th:nth-child(4)')->pause(3000);
        $this->sortData($browser, 'desc', 2);
    }

    private function caseShowDetailConfirm(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->assertSee('マッチング管理')
                ->assertSee('エントリー')->pause(1000);
            $browser->click('#entry-table-list tbody tr:nth-child(1) #btn-go-detail')->pause(3000)
                ->assertSee('志望動機')
                ->assertSee('このエントリーを承認しますか？')
                ->assertButtonEnabled('@btn_requesting_confirm')
                ->click('@btn_requesting_confirm')->pause(2000)
                ->assertSee('承諾してよろしいですか？')
                ->click('@btn_confirm')->pause(1000)
                ->waitFor('.toast-body')
                ->assertSee('承認しました。')
                ->pause(5000);
            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function caseShowDetailReject(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $i = 1;
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->assertSee('マッチング管理')
                ->assertSee('エントリー')->pause(3000);
            $browser->click('#entry-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                ->pause(3000)
                ->assertSee('志望動機')
                ->assertSee('このエントリーを承認しますか？')
                ->assertButtonEnabled('@btn_requesting_reject')
                ->click('@btn_requesting_reject')
                ->pause(2000)
                ->assertSee('本当にこのエントリーを却下しますか？')
                ->assertSee('理由')
                ->type('@textarea', 'Job has paused hiring')->pause(3000)
                ->click('@btn_handle_reject_entry')->pause(1000)
                ->waitFor('.toast-body')
                ->assertSee('却下しました')
                ->pause(2000);

            $browser->click('#entry-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                ->pause(3000)
                ->assertSee('却下')
                ->assertSee('却下理由')
                ->assertSee('Job has paused hiring')->pause(2000);

            $browser->visit('/matching-management')->pause(2000)
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
            $i++;
        }
    }

    private function caseShowDetailDecline(Browser $browser)
    {
        $types = [HR_MANAGER, \HR];
        $i = 1;
        foreach ($types as $type) {
            $text = $this->faker->text();
            $this->login($type);
            $browser->visit('/matching-management')->pause(3000)
                ->assertSee('マッチング管理')
                ->assertSee('エントリー')->pause(3000);
            $browser->click('#entry-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                ->pause(3000)
                ->assertSee('志望動機')
                ->assertSeeIn('@decline', '辞退')
                ->assertButtonEnabled('@decline')
                ->click('@decline')
                ->pause(2000)
                ->assertSee('本当に辞退しますか？')
                ->click('#dusk_group div > div:nth-child(10) > label')
                ->click('@btn_decline')
                ->assertSee('入力してください')
                ->type('@decline_note', $text)
                ->click('@btn_decline')->pause(2000)
                ->waitFor('.toast-body')
                ->assertSee('キャンセルしました。')
                ->pause(2000)
                ->assertSee('マッチング管理');

            $browser->visit('/matching-management')->pause(2000);
            $browser->click('#entry-table-list tbody tr:nth-child(' . $i . ') #btn-go-detail')
                ->pause(3000)
                ->assertSee('辞退')
                ->assertSee('辞退理由')
                ->assertSee($text)->pause(2000);

            $browser->visit('/matching-management')->pause(2000)
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
            $i++;
        }
    }

    private function casePaginateEntry(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $items = Entry::select('*', "entries.id", "entries.updated_at", "entries.status", "entries.remarks")
                ->join('hrs', 'hrs.id', '=', "entries.hr_id")
                ->join('works', 'works.id', '=', "entries.work_id")
                ->where('hrs.status', '<>', HRS_STATUS_PRIVATE)
                ->where("entries.status", "!=", ENTRY_STATUS_CONFIRM)
                ->whereNull('hrs.deleted_at')
                ->whereNull('works.deleted_at');
            if ($type == HR) {
                $items->where('hrs.user_id', User::where(User::TYPE, $type)->first()->id);
            }
            if ($type == COMPANY) {
                $items->where('works.user_id', User::where(User::TYPE, $type)->first()->id);
            }
            $records = $items->count();

            $browser->visit('/matching-management')->pause(5000)
                ->assertSee('マッチング管理')
                ->assertSee('エントリー')->pause(3000);
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

    private function caseDeleteEntry(Browser $browser)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->fakeDataSelectEntry($browser);
            $this->login($type);
            $browser->visit('/matching-management')->pause(5000);

            $browser->click('@btn_delete_all')
                ->waitFor('.toast-body')
                ->assertSee('削除するデータを選択してください')
                ->pause(2000);

            $browser->click('#entry-table-list thead tr th:nth-child(1)')
                ->click('@btn_delete_all')->pause(3000)
                ->assertSee('本当に削除しますか？')
                ->click('@btn_accept_delete_entry')
                ->waitFor('.toast-body')
                ->assertSee('削除しました');

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function fakeDataSelectEntry($browser)
    {
        $this->faker = \Faker\Factory::create();
        $this->fakeDataHr();
        $this->fakeDataEntry();
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
                Work::APPLICATION_PERIOD_FROM => Carbon::now()->format('Y-m-d'),
                Work::APPLICATION_PERIOD_TO => Carbon::now()->addMonth()->format('Y-m-d'),
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

    private function fakeDataEntry()
    {
        $this->fakeDataJob();
        for ($i = 1; $i <= 25; $i++) {
            $hrId = HR::query()->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;
            $workId = Work::query()->where(Work::STATUS, WORK_STATUS_RECRUITING)->inRandomOrder()->first()->id;
            if ($i >= 16)
                $status = 1;
            else
                $status = rand(1, 4);
            $entry = Entry::create([
                Entry::ENTRY_CODE => 'E' . rand(0000000, 1000000),
                Entry::HR_ID => $hrId,
                Entry::WORK_ID => $workId,
                Entry::REMARKS => $this->faker->text(30),
                Entry::DISPLAY => 'on',
                Entry::STATUS => $status,
                Entry::REQUEST_DATE => \Carbon\Carbon::now()->format('Y-m-d')
            ]);
            UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE, UploadFile::FILE_ITEM_ID => $entry->id]);
        }
    }
}
