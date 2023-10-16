<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\LanguageRequirementWork;
use App\Models\Offer;
use App\Models\PassionWork;
use App\Models\Result;
use App\Models\User;
use App\Models\Work;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HrTest extends DuskTestCase
{
    use WithFaker;
    use HrSearch;
    use WorkSearch;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->faker = \Faker\Factory::create();
        $this->fakeDataHr();
        $this->browse(function (Browser $browser) {
            $this->caseListHr($browser);
            $this->caseSortListHrWithAge($browser);
            $this->caseDeleteHr($browser);
            $this->caseDetailHr($browser);
            $this->caseEditHr($browser);
            $this->caseImportFileHr($browser);
            $this->caseRegisterHr($browser);
            $this->caseHrSearch($browser);
            $this->caseHrSearchForCompany($browser);
            $this->caseSearchJobToOffer($browser);
            $this->caseSearchMatching($browser);
        });
    }

    private function checkListWithPermission(Browser $browser, $type)
    {
        switch ($type) {
            case SUPER_ADMIN:
            case HR_MANAGER:
                $browser->assertSee('人材一覧')
                    ->assertSee('公開')
                    ->assertSee('内定承諾')
                    ->assertSee('非公開')
                    ->assertButtonEnabled('@btn_delete_hr')
                    ->assertButtonEnabled('@btn_export_csv_hr')
                    ->click('#dropdown-1')->pause(2000)
                    ->assertSeeIn('@btn_to_register_hr', '一人追加')
                    ->assertSeeIn('@btn_import_csv_hr', '一括追加')
                    ->click('.collapse-bar-btn')
                    ->assertMissing('@hr_search')
                    ->click('.collapse-bar-btn')
                    ->assertVisible('@hr_search');
                break;
            case COMPANY_MANAGER:
            case \COMPANY:
                $browser->assertSee('人材一覧')
                    ->assertSee('公開')
                    ->assertDontSee('内定承諾')
                    ->assertDontSee('非公開')
                    ->assertMissing('@btn_delete_hr')
                    ->assertMissing('@btn_export_csv_hr')
                    ->assertMissing('#dropdown-1')
                    ->click('.collapse-bar-btn')
                    ->assertMissing('@hr_search')
                    ->click('.collapse-bar-btn')
                    ->assertVisible('@hr_search');
                break;
            case \HR:
                $hr = HR::find(22);
                $browser->assertDontSee($hr->full_name);
        }
    }

    public function caseListHr(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr/list')->pause(2000);
            $this->checkListWithPermission($browser, $type);

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

            if ($type != \HR)
                $browser->scrollIntoView('.pagination')->pause(2000)
                    ->click('[aria-label="Go to next page"]')->pause(2000)
                    ->scrollIntoView('.pagination')
                    ->assertSeeIn('.pagination .active', '2')
                    ->pause(2000)
                    ->click('[aria-label="Go to previous page"]')->waitUntilMissing('.loading')
                    ->assertSeeIn('.pagination .active', '1')
                    ->pause(2000);

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function sortListHrWithAge(Browser $browser, $sortType)
    {
        $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        $dataIdList = [];

        foreach ($attributes as $attribute) {
            $dataIdList[] = explode("\n", $attribute->getText())[5] ?? '';
        }

        $count = count($dataIdList);
        foreach ($dataIdList as $key => $value) {
            $key = $key < $count - 2 ? $key + 1 : $count - 1;
            $nextText = $dataIdList[$key];
            if (!$nextText) continue;
            if ($sortType == 'asc')
                $this->assertTrue($value <= $nextText);
            else
                $this->assertTrue($value >= $nextText);
        }
    }

    public function caseSortListHrWithAge(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('hr/list')
            ->pause(2000)
            ->click('[aria-colindex="5"]')
            ->pause(3000);

        $this->sortListHrWithAge($browser, 'asc');

        $browser->click('[aria-colindex="5"]')
            ->pause(3000);
        $this->sortListHrWithAge($browser, 'desc');
        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function caseDeleteHr(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('hr/list')
            ->pause(2000)
            ->click('@btn_delete_hr')->pause(1000)
            ->assertSee('削除するデータを選択してください.')->pause(2000)
            ->check('tbody [aria-colindex="1"]')
            ->click('@btn_delete_hr')->pause(1000)
            ->assertSee('本当に削除しますか？')
            ->click('@btn_accept')->pause(2000)
            ->assertSee('成功')->pause(2000);

        $browser->scrollIntoView('.pagination')
            ->click('[aria-label="Go to next page"]')
            ->pause(2000)
            ->scrollIntoView('.pagination')
            ->assertSeeIn('.pagination .active', '2')
            ->check('thead [aria-colindex="1"]')
            ->click('@btn_delete_hr')->pause(1000)
            ->assertSee('本当に削除しますか？')
            ->click('@btn_accept')->pause(2000)
            ->assertSee('成功')->pause(3000)
            ->scrollIntoView('.pagination')
            ->assertSeeIn('.pagination .active', '1')
            ->assertDontSeeIn('.pagination', '2');

        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    private function detailHrWithAdmin(Browser $browser)
    {
        $browser->assertSee('人材情報詳細')
            ->assertSee('基本情報')
            ->assertSee('学歴・職歴')
            ->assertSee('自己PR・備考')
            ->assertSee('連絡先')
            ->assertSee('マッチング状況');

        $browser->click('@btn_back_to_list_hr')->pause(2000)
            ->assertSee('人材一覧')
            ->click('@btn_detail_hr')->pause(2000)
            ->scrollIntoView('#offer')->pause(2000)
            ->click('#offer')->pause(2000)
            ->assertSee('オファーする求人を選択する')
            ->click('.close')->pause(1000)
            ->scrollIntoView('#favorite')
            ->assertSeeIn('#favorite > div > div', 'お気に入りに追加')
            ->click('#favorite')->pause(1000)
            ->assertSeeIn('#favorite > div > div > span.added-to-favorites', 'お気に入りに追加済')
            ->assertSeeIn('#favorite > div > div > span.removed-to-favorites', 'お気に入りにから外す')
            ->click('#favorite')->pause(1000)
            ->assertMissing('#favorite > div > div > span.removed-to-favorites')
            ->scrollIntoView('@btn_to_edit_hr')
            ->click('@btn_to_edit_hr')->pause(2000)
            ->assertSee('必須');
    }

    private function detailHrWithHr(Browser $browser)
    {
        $browser->assertSee('人材情報詳細')
            ->assertSee('基本情報')
            ->assertSee('学歴・職歴')
            ->assertSee('自己PR・備考')
            ->assertSee('連絡先')
            ->assertSee('マッチング状況');

        $browser->click('@btn_back_to_list_hr')->pause(2000)
            ->assertSee('人材一覧')
            ->click('@btn_detail_hr')->pause(2000)
            ->assertMissing('#offer')
//            ->assertMissing('#favorite')->pause(1000)
            ->click('@btn_to_edit_hr')->pause(2000)
            ->assertSee('必須');
    }

    private function detailHrWithCompany(Browser $browser)
    {
        $browser->assertSee('人材情報詳細')
            ->assertSee('基本情報')
            ->assertSee('学歴・職歴')
            ->assertSee('自己PR・備考')
            ->assertDontSee('連絡先')
            ->assertDontSee('マッチング状況');

        $browser->click('@btn_back_to_list_hr')->pause(2000)
            ->assertSee('人材一覧')
            ->click('@btn_detail_hr')->pause(2000)
            ->click('#offer')->pause(2000)
            ->assertSee('オファーする求人を選択する')
            ->click('.close')->pause(1000)
//            ->click('#favorite')->pause(1000)
//            ->assertSeeIn('', 'お気に入りに追加済')
//            ->click('#favorite')->pause(1000)
//            ->assertSeeIn('', 'お気に入りにから外す')
            ->assertMissing('@btn_to_edit_hr');
    }

    public function caseDetailHr(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr/list')->pause(2000)
                ->click('@btn_detail_hr')->pause(3000)
                ->scrollIntoView('.information-general-btns')
                ->assertSee('公開')
                ->assertSee('非公開')
                ->assertSee('内定承諾');

            $browser->script('document.documentElement.scrollTop = 0');

            switch ($type) {
                case SUPER_ADMIN:
                    $this->detailHrWithAdmin($browser);
                    break;
                case COMPANY_MANAGER:
                case \COMPANY:
                    $this->detailHrWithCompany($browser);
                    break;
                case HR_MANAGER:
                case \HR:
                    $this->detailHrWithHr($browser);
                    break;
            }

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function inputFieldList(Browser $browser)
    {
        $browser->scrollIntoView('.nav-item:nth-child(1) a')->pause(2000)
            ->keys('@full_name', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@full_name', $this->faker->name)
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->keys('@full_name_furigana', ...array_fill(0, 50, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@full_name_furigana', $this->faker->name)
            ->assertDontSee('入力してください。')->pause(1000);

        $date_of_birth = $this->faker->dateTimeBetween('-50 years', '-18 years');
        $browser->select('@date_of_birth_year', $date_of_birth->format('Y'))
            ->select('@date_of_birth_month', $date_of_birth->format('m'))
            ->select('@date_of_birth_day', $date_of_birth->format('d'));


        foreach (HRS_WORK_FORM as $value) {
            if ($value != HRS_FULL_TIME_EMPLOYEE) {
                $browser->select('@work_form', $value)->pause(1000)
                    ->assertEnabled('@work_form_part_time');
            } else
                $browser->select('@work_form', $value)->pause(1000)
                    ->assertDisabled('@work_form_part_time');
        }

        $language = LanguageRequirement::query()->inRandomOrder()->pluck('id')->first();
        $browser->scrollIntoView('@japanese_level')
            ->select('@japanese_level', $language);

        $dateTime = $this->faker->dateTimeBetween('-5 years', '-2 years');
        $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType)->inRandomOrder()->pluck('id')->first();

        $browser->click('.nav-item:nth-child(2) a')
            ->select('@final_education_timing_year', $dateTime->format('Y'))
            ->select('@final_education_timing_month', $dateTime->format('m'))
            ->select('@final_education_classification', $this->faker->randomElements(HR_FINAL_EDUCATION)[0])
            ->select('@final_education_degree', $this->faker->randomElements(array_keys(HR_EDUCATION_DEGREES))[0])
            ->select('@major_classification', $jobType)
            ->select('@middle_classification', $jobInfo);

        $dateMainJob = $dateTime->modify('+1 year');
        $browser->scrollTo('@main_job_career_1')
            ->select('@main_job_career_1_year_from', $dateMainJob->format('Y'))
            ->select('@main_job_career_1_month_from', $dateMainJob->format('m'))->pause(2000)
            ->script("document.getElementById('main-job-career-1').click();");
        $browser->assertDisabled('@main_job_career_1_year_to')
            ->assertDisabled('@main_job_career_1_month_to');

        $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->pluck('id')->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType)->inRandomOrder()->pluck('id')->first();
        $browser->select('@main_job_career_1_department', $jobType)
            ->select('@main_job_career_1_job_title', $jobInfo)
            ->assertInputValueIsNot('@main_job_career_1_detail', '')
            ->keys('@main_job_career_1_detail', ...array_fill(0, 30, '{backspace}'))
            ->assertSee('入力してください。')
            ->type('@main_job_career_1_detail', $this->faker->text(20))->pause(2000)
            ->assertDontSeeIn('@main_job_career_detail', '入力してください。');

        $browser->scrollIntoView('.nav-item:nth-child(2) a')
            ->click('.nav-item:nth-child(3) a')->pause(1000)
            ->assertSee('自己PR・特記事項')
            ->assertSee('備考');

        $browser->click('.nav-item:nth-child(4) a')
            ->keys('@mail_address', ...array_fill(0, 70, '{backspace}'))
//                ->assertSee('入力してください。')
            ->type('@mail_address', $this->faker->email)
            ->assertDontSee('入力してください。')->pause(1000);

        $browser->click('.nav-item:nth-child(5) a')->pause(2000)
            ->assertSee('エントリー情報')
            ->assertSee('オファー情報')
            ->assertSee('面接情報')
            ->assertSee('結果情報');
    }

    private function updateStatus(Browser $browser, $btn, $status, $text)
    {
        $browser->click('@btn_to_edit_hr')->pause(3000)
            ->scrollIntoView('@hr_offer_confirm')
            ->assertSee('公開')
            ->assertSee('非公開')
            ->assertSee('内定承諾')
            ->click('@' . $btn)
            ->scrollIntoView('@btn_save')->pause(2000)
            ->click('@btn_save')
            ->waitFor('.toast-body')
            ->assertSee('データの保存に成功しました');

        if ($status != HRS_STATUS_CONFIRM) {
            $this->assertDatabaseHas('hrs', [
                'id' => 25,
                HR::STATUS => $status
            ]);

            $browser->visit('hr/list')->pause(2000)
                ->assertSeeIn('tbody tr td:nth-child(7)', $text)
                ->click('@btn_detail_hr')->pause(3000);
        } else {
            $browser->visit('hr/list')->pause(2000)
                ->assertDontSeeIn('tbody tr td:nth-child(7)', $text)
                ->click('@btn_detail_hr')->pause(3000);
        }
    }

    public function caseEditHr(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('hr/list')->pause(2000)
            ->click('@btn_detail_hr')->pause(3000)
            ->click('@btn_to_edit_hr')->pause(3000)
            ->scrollIntoView('@btn_save')->pause(2000)
            ->click('@btn_save')
            ->waitFor('.toast-body')
            ->assertSee('フォームデータの検証に失敗しました。必須項目を入力してください。');

        $file = 'tests\FileTest\HR_list.csv';
        $browser->scrollIntoView('@file_cv')
            ->attach('@file_cv', $file)->pause(2000)
            ->assertSee('HR_list.csv')
            ->attach('@file_JD', $file)->pause(2000)
            ->assertSee('HR_list.csv');
        $browser->scrollIntoView('@btn_save')
            ->click('@btn_save')
            ->waitFor('.toast-body')
            ->assertSee('データの保存に成功しました')->pause(2000);

        $this->updateStatus($browser, 'hr_private', HRS_STATUS_PRIVATE, '非公開');
        $this->updateStatus($browser, 'hr_public', HRS_STATUS_PUBLIC, '公開');
        $this->updateStatus($browser, 'hr_offer_confirm', HRS_STATUS_CONFIRM, '内定承諾');

        $browser->click('@btn_to_edit_hr')->pause(3000);
        $this->inputFieldList($browser);

        $browser->scrollIntoView('@btn_save')
            ->click('@btn_save')
            ->waitFor('.toast-body')
            ->assertSee('データの保存に成功しました')->pause(2000);

        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function importFileHrError(Browser $browser, $file, $msg, $nameFile)
    {
        $browser->attach('@btn_file_import_hr', $file)->pause(2000)
            ->assertSee($nameFile)
            ->click('@import')->pause(2000)
            ->assertSee($msg);
    }

    public function importFileHrSuccess(Browser $browser)
    {
        $hrOrg = HrOrganization::factory()->create([
            HrOrganization::CORPORATE_NAME_EN => 'corporate_name_en',
            HrOrganization::CORPORATE_NAME_JA => 'corporate_name_ja'
        ]);

        $file = 'tests/FileTest/file_some_data_error.csv';
        $browser->attach('@btn_file_import_hr', $file)->pause(2000)
//            ->assertSee('file_some_data_error.csv')
            ->click('@import')->pause(2000)
            ->assertSee('ファイルを選択してください。 1件中2件のインポートに成功しました。')->pause(3000);

        $this->assertDatabaseHas('hrs', [
            HR::HR_ORGANIZATION_ID => $hrOrg->id,
            HR::FULL_NAME => 'Mckenna Hyatt'
        ]);

        $browser->visit('hr/list')->pause(2000)
            ->assertSeeIn('#hr-table-list tbody tr td:nth-child(2)', 26)
            ->assertSeeIn('#hr-table-list tbody tr td:nth-child(3)', 'Mckenna Hyatt')->pause(2000);
    }

    public function caseImportFileHr(Browser $browser)
    {
        $this->login(SUPER_ADMIN);
        $browser->visit('hr/list')->pause(2000)
            ->click('#dropdown-1')->pause(2000)
            ->assertSeeIn('@btn_import_csv_hr', '一括追加')
            ->click('@btn_import_csv_hr')->pause(2000);

        $errExtension = 'tests/Browser/HrTest.php';
        $this->importFileHrError($browser, $errExtension, trans('errors.hrs.error_file_import.extension'), 'HrTest.php');

        $errFileSize = 'tests/FileTest/error_file_size.crdownload';
        $this->importFileHrError($browser, $errFileSize, trans('api.uploadFile.size'), error_file_size . crdownload);

        $errFileFormat = 'tests/FileTest/error_file_format.csv';
        $this->importFileHrError($browser, $errFileFormat, trans('errors.hrs.error_file_import.format'), 'error_file_format.csv');

        $errFileData = 'tests/FileTest/error_file_data_fail.csv';
        $browser->attach('@btn_file_import_hr', $errFileData)->pause(2000)
            ->assertSee('error_file_data_fail.csv')
            ->click('@import')->pause(2000)
            ->assertSee('インポートに失敗しました。')
            ->assertSee('団体名、団体名（日本語）が存在しません。');

        $this->importFileHrSuccess($browser);
        $browser->pause(2000)->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function caseRegisterHr(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr/create')->pause(2000)->assertSee('人材登録');

            $browser->scrollIntoView('@hr_create_cancel')
                ->click('@hr_create_cancel')->pause(1000)
                ->assertSee('入力内容が破棄されます。')
                ->assertSee('このページから移動してもよろしいですか？')->pause(1000)
                ->click('@btn_accept_leaving')->pause(2000)
                ->assertSee('人材一覧')
                ->visit('/hr/create');

            if ($type == SUPER_ADMIN || $type == HR_MANAGER) {
                $hrOrg = HrOrganization::query()->inRandomOrder()->first();
                $browser->assertDisabled('@organization_name_ja')
//                    ->assertDisabled('@country_name')
                    ->type('@country_name', 'a')
                    ->select('@organization_name', $hrOrg->id);

                $organization_name_ja = $browser->value('@organization_name_ja');
                $this->assertEquals($organization_name_ja, $hrOrg->corporate_name_ja);

            } else {
                $hrOrg = HrOrganization::query()->where(HrOrganization::USER_ID, Auth::id())->first();
                $browser->assertDisabled('@organization_name')
                    ->assertDisabled('@organization_name_ja')
                    ->assertDisabled('@country_name');
                $organization_name = $browser->value('@organization_name');
                $organization_name_ja = $browser->value('@organization_name_ja');
                $this->assertEquals($organization_name, $hrOrg->corporate_name);
                $this->assertEquals($organization_name_ja, $hrOrg->corporate_name_ja);
            }

            $browser->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@full_name')
                ->assertSee('入力してください。')
                ->type('@full_name', $this->faker->name);

            $browser->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@full_name_furigana')
                ->assertSee('入力してください。')
                ->type('@full_name_furigana', $this->faker->name);

            $date_of_birth = $this->faker->dateTimeBetween('-50 years', '-18 years');
            $browser->select('@date_of_birth_year', $date_of_birth->format('Y'))
                ->select('@date_of_birth_month', $date_of_birth->format('m'))
                ->select('@date_of_birth_day', $date_of_birth->format('d'))
                ->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@japanese_level')
                ->assertSee('選択してください。');

            $browser->click('@japanese_level')
                ->click('#app > div > div.display-app.container > div.display-app-main > div > div > div > div > div.hr-registration-form-autox > div > div:nth-child(1) > div.hr-registration-form-item-from > div.item-from-wrap.border-t.border-l.border-r.border-b > div.item-from-inputs.border-l > select > option:nth-child(1)');

            $dateTime = $this->faker->dateTimeBetween('-5 years', '-2 years');
            $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
            $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType)->inRandomOrder()->pluck('id')->first();

            $jobTypeDiff = JobType::query()
                ->where(JobType::TYPE, JOB_TYPE_COL_2)
                ->where('id', '!=', $jobType)
                ->inRandomOrder()->pluck('id')->first();
            $jobInfoDiff = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobTypeDiff)->inRandomOrder()->pluck('id')->first();

            $browser->scrollIntoView('@final_education_timing_year')
                ->select('@final_education_timing_year', $dateTime->format('Y'))
                ->select('@final_education_timing_month', $dateTime->format('m'))
                ->click('@final_education_classification')->pause(1000)
                ->click('#app > div > div.display-app.container > div.display-app-main > div > div > div > div > div.hr-registration-form-autox > div > div:nth-child(2) > div.hr-registration-form-item-from > div:nth-child(2) > div.item-from-inputs.border-l > select > option:nth-child(1)')
                ->pause(1000)
                ->click('@final_education_degree')->pause(1000)
                ->click('#app > div > div.display-app.container > div.display-app-main > div > div > div > div > div.hr-registration-form-autox > div > div:nth-child(2) > div.hr-registration-form-item-from > div:nth-child(3) > div.item-from-inputs.border-l > select > option:nth-child(3)')
                ->pause(1000)
                ->select('@major_classification', $jobType)
                ->select('@middle_classification', $jobInfo)
                ->select('@major_classification', $jobTypeDiff)
//            ->assertSee('選択してください。')
                ->select('@middle_classification', $jobInfoDiff);
//
            $browser->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@main_job_career_1_year_from');
//            ->assertSee('選択してください。');

            $dateMainJob = $dateTime->modify('+1 year');
            $browser->select('@main_job_career_1_year_from', $dateMainJob->format('Y'))
                ->select('@main_job_career_1_month_from', $dateMainJob->format('m'))->pause(2000)
                ->script("document.getElementById('main-job-career-1').click();");
            $browser->assertDisabled('@main_job_career_1_year_to')
                ->assertDisabled('@main_job_career_1_month_to')
                ->script("document.getElementById('main-job-career-1').click();");
            $browser->select('@main_job_career_1_year_to', $dateMainJob->format('Y'))
                ->select('@main_job_career_1_month_to', $dateMainJob->format('m'))->pause(2000);

            $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->pluck('id')->first();
            $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType)->inRandomOrder()->pluck('id')->first();
            $browser->select('@main_job_career_1_department', $jobType)
                ->select('@main_job_career_1_job_title', $jobInfo)
                ->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@main_job_career_1_detail')
//                ->assertSee('入力してください。')
                ->type('@main_job_career_1_detail', $this->faker->text(20))->pause(2000);

            $browser->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')->pause(2000)
                ->scrollIntoView('@mail_address');
//                ->assertSee('入力してください。');

            $browser->type('@mail_address', $this->faker->text(20))->pause(2000)
                ->scrollIntoView('@hr_create_save')
                ->click('@hr_create_save')
                ->waitFor('.toast-body')
                ->assertSee('The mail address must be a valid email address.')
                ->scrollIntoView('@mail_address')
                ->type('@mail_address', $this->faker->email);

            $browser->scrollIntoView('@hr_create_cancel')
                ->click('@hr_create_cancel')
                ->click('@btn_continue_create')->pause(1000)
                ->click('@hr_create_save')
                ->waitFor('.toast-body')
                ->assertSee('データの保存に成功しました')->pause(2000);

            $browser->pause(2000)->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function searchHr(Browser $browser, $type)
    {
        $this->searchWithHrOrg($browser, $type);

        $gender = rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE);
        $selector = '#dusk_gender div > div:nth-child(' . $gender . ') > label';
        $this->searchByCheck($browser, $type, '@btn_gender', $selector, HR::GENDER, [$gender]);

        $this->searchByAge($browser, $type);
        $this->searchByFinalEduDate($browser, $type, true);

        $eduClass = $this->faker->randomElement(HR_FINAL_EDUCATION);
        $this->searchByCheck($browser, $type, '@btn_edu_class', '#dusk_edu_class > div:nth-child(' . $eduClass . ') > label', HR::FINAL_EDUCATION_CLASSIFICATION, [$eduClass]);

        $eduDegree = rand(1, 2);
        $this->searchByCheck($browser, $type, '@btn_edu_degree', '#dusk_edu_degree div div > div:nth-child(' . $eduDegree . ') > label', HR::FINAL_EDUCATION_DEGREE, [$eduDegree]);

        $majorClass = JobType::query()->where(JobType::TYPE, 2)->inRandomOrder()->first()->id;
        $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
        $btnReflect = '@btn_reflect';
        $beforeSelector = '#dusk_parent_option > div:nth-child(' . ($majorClass - 17) . ')';
        $selector = '#dusk_children_option > div.head-collapse-parents-title > div > label';
        $this->searchByCheck($browser, $type, '@btn_edu_course', $selector, HR::MIDDLE_CLASSIFICATION_ID, $middleClass, '@btn_specify', $beforeSelector, '学科を選ぶ', $btnReflect);

        $workStyle = $this->faker->randomElement(HRS_WORK_FORM);
        $this->searchByCheck($browser, $type, '@btn_work_forms', '#dusk_work_forms div:nth-child(' . $workStyle . ') > label', HR::WORK_FORM, [$workStyle]);

        $this->searchByCheck($browser, $type, '@btn_work_part_time', '#dusk_work_part_time div > div > label', HR::PREFERRED_WORKING_HOURS, 1);

        $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
        $this->searchByCheck($browser, $type, '@btn_japan_levels', '#dusk_japan_levels div:nth-child(' . $japan . ') > label', HR::JAPANESE_LEVEL, [$japan]);

        $majorClass = JobType::query()->where(JobType::TYPE, 1)->inRandomOrder()->first()->id;
        $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
        $btnReflect = '@btn_reflect_job';
        $beforeSelector = '#dusk_parent_option_job > div:nth-child(' . $majorClass . ')';
        $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
        $this->searchByCheck($browser, $type, '@btn_main_job', $selector, 'main_jobs', $middleClass, '@btn_specify_job', $beforeSelector, '職種を選ぶ', $btnReflect);

        $this->searchWithText($browser, $type);
        $browser->scrollIntoView('.btn-logout')
            ->click('.btn-logout');
    }

    public function caseHrSearch(Browser $browser)
    {
        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr/list')->pause(2000);
            $this->searchHr($browser, $type);
        }
    }

    public function caseHrSearchForCompany(Browser $browser)
    {
        $types = [COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr-search')->pause(2000);
            $this->searchWithHrOrg($browser, $type, false);

            $browser->visit('hr-search')->pause(2000);
            $gender = rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE);
            $selector = '#dusk_gender div > div:nth-child(' . $gender . ') > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::GENDER, [$gender], null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $this->searchByAge($browser, $type, false);

            $browser->visit('hr-search')->pause(2000);
            $this->searchByFinalEduDate($browser, $type, false);

            $browser->visit('hr-search')->pause(2000);
            $eduClass = $this->faker->randomElement(HR_FINAL_EDUCATION);
            $selector = '#dusk_edu_class div > div:nth-child(' . $eduClass . ') > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::FINAL_EDUCATION_CLASSIFICATION, [$eduClass], null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $eduDegree = rand(1, 2);
            $selector = '#dusk_edu_degree div div > div:nth-child(' . $eduDegree . ') > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::FINAL_EDUCATION_DEGREE, [$eduDegree], null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $majorClass = JobType::query()->where(JobType::TYPE, 2)->inRandomOrder()->first()->id;
            $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
            $btnReflect = '@btn_reflect';
            $beforeSelector = '#dusk_parent_option > div:nth-child(' . ($majorClass - 17) . ')';
            $selector = '#dusk_children_option > div.head-collapse-parents-title > div > label';
            $this->searchByCheck($browser, $type, '@btn_edu_course', $selector, HR::MIDDLE_CLASSIFICATION_ID, $middleClass, null, $beforeSelector, '学科を選ぶ', $btnReflect, true);

            $browser->visit('hr-search')->pause(2000);
            $workStyle = $this->faker->randomElement(HRS_WORK_FORM);
            $selector = '#dusk_work_forms div div:nth-child(' . $workStyle . ') > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::WORK_FORM, [$workStyle], null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $selector = '#dusk_work_part_time div > div > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::PREFERRED_WORKING_HOURS, 1, null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
            $selector = '#dusk_japan_levels div:nth-child(' . $japan . ') > label';
            $this->searchByCheck($browser, $type, $selector, $selector, HR::JAPANESE_LEVEL, [$japan], null, null, null, null, true);

            $browser->visit('hr-search')->pause(2000);
            $majorClass = JobType::query()->where(JobType::TYPE, 1)->inRandomOrder()->first()->id;
            $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
            $btnReflect = '@btn_reflect_job';
            $beforeSelector = '#dusk_parent_option_job > div:nth-child(' . $majorClass . ')';
            $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
            $this->searchByCheck($browser, $type, '@btn_main_job', $selector, 'main_jobs', $middleClass, '@btn_specify_job', $beforeSelector, '職種を選ぶ', $btnReflect, true);

            $browser->visit('hr-search')->pause(2000);
            $this->searchWithText($browser, $type);

            $browser->visit('hr-search/list')->pause(2000);
            $this->searchHr($browser, $type);

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    public function caseSearchJobToOffer(Browser $browser)
    {
        $this->fakeDataJob();
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];

        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('hr/detail/25')->pause(2000)
                ->scrollIntoView('#offer')
                ->click('#offer')->pause(2000);

            $this->searchWithCompany($browser, $type);

            $majorClass = Work::query()->inRandomOrder()->first()->major_classification_id;
            $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id');
            $beforeSelector = '#dusk_parent_option_job > div:nth-child(' . $majorClass . ')';
            $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
            $this->searchByCheckWork($browser, $type, '@select_job_btn', $selector, Work::MIDDLE_CLASSIFICATION_ID, $middleClass, $beforeSelector, '職種を選ぶ', '@btn_reflect_job', false, false, true);

            $this->searchWithIncome($browser, $type, false, true);

            $city = rand(1, 6);
            $beforeSelector = '#dusk_city_id  div div > div > div > div.multiselect__tags';
            $selector = '#dusk_city_id ul li:nth-child(' . ($city + 1) . ')';
            $this->searchByCheckWork($browser, $type, '@btn_working_place', $selector, Work::CITY_ID, [$city], $beforeSelector, '都道府県を選ぶ', null, true, false, true);

            $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
            $this->searchByCheckWork($browser, $type, '@btn_japan_levels', '#dusk_japan_levels div:nth-child(' . $japan . ') > label', LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID, [$japan], null, null, null, false, false, true);

            $work_forms = HRS_WORK_FORM[array_rand(HRS_WORK_FORM)];
            $this->searchByCheckWork($browser, $type, '@btn_work_forms', '#dusk_work_forms div > div:nth-child(' . $work_forms . ') label', Work::FORM_OF_EMPLOYMENT, [$work_forms], null, null, null, false, false, true);

            $passion = PassionWork::query()->inRandomOrder()->first()->passion_id;
            $this->searchByCheckWork($browser, $type, '@btn_passion_works', '#dusk_passion_works div > div:nth-child(' . $passion . ') label', PassionWork::PASSION_ID, [$passion], null, null, null, false, false, true);

            $this->searchWithTextWork($browser, $type, false, true);

            $browser->scrollIntoView('button[class="close"]')
                ->click('button[class="close"]')
                ->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }

    private function fakeDataEntry()
    {
        $this->fakeDataJob();
        for ($i = 1; $i <= 21; $i++) {
            $hrId = HR::query()->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;
            $workId = Work::query()->where(Work::STATUS, WORK_STATUS_RECRUITING)->inRandomOrder()->first()->id;
            $status = rand(1, 4);
            Entry::create([
                Entry::ENTRY_CODE => 'E' . rand(00000000, 10000000),
                Entry::HR_ID => $hrId,
                Entry::WORK_ID => $workId,
                Entry::REMARKS => $this->faker->text(30),
                Entry::DISPLAY => 'on',
                Entry::STATUS => $status,
                Entry::REQUEST_DATE => \Carbon\Carbon::now()->format('Y-m-d')
            ]);
        }
    }

    private function fakeDataOffer()
    {
        $this->fakeDataJob();
        for ($i = 1; $i <= 21; $i++) {
            $status = rand(1, 3);
            $hrId = HR::query()->where(HR::STATUS, HRS_STATUS_PUBLIC)->inRandomOrder()->first()->id;
            $workId = Work::query()->where(Work::STATUS, WORK_STATUS_RECRUITING)->inRandomOrder()->first()->id;
            Offer::factory()->create([
                Offer::HR_ID => $hrId,
                Offer::WORK_ID => $workId,
                Offer::REMARKS => $this->faker->text(30),
                Offer::DISPLAY => 'on',
                Offer::STATUS => $status,
                Offer::REQUEST_DATE => \Carbon\Carbon::now()->format('Y-m-d')
            ]);
        }
    }

    private function fakeDataInterview()
    {
        for ($i = 1; $i <= 21; $i++) {
            Interview::factory()->create();
        }
    }

    private function fakeDataResult()
    {
        for ($i = 1; $i <= 21; $i++) {
            Result::factory()->create();
        }
    }

    private function searchMatching(Browser $browser, $type, $model, $table)
    {
        $this->searchWithHrOrg($browser, $type, true, $model, $table);

        $gender = rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE);
        $selector = '#dusk_gender div > div:nth-child(' . $gender . ') > label';
        $this->searchByCheck($browser, $type, '@btn_gender', $selector, HR::GENDER, [$gender], null, null, null, null, false, $model, $table);

        $this->searchByAge($browser, $type, true, $model, $table);
        $this->searchByFinalEduDate($browser, $type, true, $model, $table);

        $eduClass = $this->faker->randomElement(HR_FINAL_EDUCATION);
        $this->searchByCheck($browser, $type, '@btn_edu_class', '#dusk_edu_class > div:nth-child(' . $eduClass . ') > label', HR::FINAL_EDUCATION_CLASSIFICATION, [$eduClass], null, null, null, null, false, $model, $table);

        $eduDegree = rand(1, 2);
        $this->searchByCheck($browser, $type, '@btn_edu_degree', '#dusk_edu_degree div div > div:nth-child(' . $eduDegree . ') > label', HR::FINAL_EDUCATION_DEGREE, [$eduDegree], null, null, null, null, false, $model, $table);

        $majorClass = JobType::query()->where(JobType::TYPE, 2)->inRandomOrder()->first()->id;
        $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
        $btnReflect = '@btn_reflect';
        $beforeSelector = '#dusk_parent_option > div:nth-child(' . ($majorClass - 17) . ')';
        $selector = '#dusk_children_option > div.head-collapse-parents-title > div > label';
        $this->searchByCheck($browser, $type, '@btn_edu_course', $selector, HR::MIDDLE_CLASSIFICATION_ID, $middleClass, '@btn_specify', $beforeSelector, '学科を選ぶ', $btnReflect, false, $model, $table);

        $workStyle = $this->faker->randomElement(HRS_WORK_FORM);
        $this->searchByCheck($browser, $type, '@btn_work_forms', '#dusk_work_forms div:nth-child(' . $workStyle . ') > label', HR::WORK_FORM, [$workStyle], null, null, null, null, false, $model, $table);

        $this->searchByCheck($browser, $type, '@btn_work_part_time', '#dusk_work_part_time div > div > label', HR::PREFERRED_WORKING_HOURS, 1, null, null, null, null, false, $model, $table);

        $japan = LanguageRequirement::query()->inRandomOrder()->first()->id;
        $this->searchByCheck($browser, $type, '@btn_japan_levels', '#dusk_japan_levels div:nth-child(' . $japan . ') > label', HR::JAPANESE_LEVEL, [$japan], null, null, null, null, false, $model, $table);

        $majorClass = JobType::query()->where(JobType::TYPE, 1)->inRandomOrder()->first()->id;
        $middleClass = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $majorClass)->pluck('id')->toArray();
        $btnReflect = '@btn_reflect_job';
        $beforeSelector = '#dusk_parent_option_job > div:nth-child(' . $majorClass . ')';
        $selector = '#dusk_children_option_job > div.head-collapse-parents-title > div > label';
        $this->searchByCheck($browser, $type, '@btn_main_job', $selector, 'main_jobs', $middleClass, '@btn_specify_job', $beforeSelector, '職種を選ぶ', $btnReflect, false, $model, $table);

        $this->searchWithText($browser, $type, $model, $table);
    }

    public function caseSearchMatching(Browser $browser)
    {
        $this->fakeDataEntry();
        $this->fakeDataOffer();
        $this->fakeDataInterview();
        $this->fakeDataResult();

        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER, \COMPANY, \HR];
        foreach ($types as $type) {
            $this->login($type);
            $browser->visit('matching-management')->pause(2000);
            $model = Entry::class;
            $table = 'entry-table-list';
            $browser->scrollIntoView('#dusk_matching ul li:nth-child(1)')
                ->click('#dusk_matching ul li:nth-child(1)');
            $this->searchMatching($browser, $type, $model, $table);

            $model = Offer::class;
            $table = 'offer-table-list';
            $browser->scrollIntoView('#dusk_matching ul li:nth-child(2)')->pause(2000)
                ->click('#dusk_matching ul li:nth-child(2)');
            $this->searchMatching($browser, $type, $model, $table);

            $model = Interview::class;
            $table = 'interview-table';
            $browser->scrollIntoView('#dusk_matching ul li:nth-child(3)')->pause(3000)
                ->click('#dusk_matching ul li:nth-child(3)');
            $this->searchMatching($browser, $type, $model, $table);

            $model = Result::class;
            $table = 'result-table-list';
            $browser->scrollIntoView('#dusk_matching ul li:nth-child(4)')->pause(2000)
                ->click('#dusk_matching ul li:nth-child(4)');
            $this->searchMatching($browser, $type, $model, $table);

            $browser->scrollIntoView('.btn-logout')
                ->click('.btn-logout');
        }
    }
}
