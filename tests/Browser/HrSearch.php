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
use App\Models\User;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;

trait HrSearch
{
    protected $faker;
    protected $hrId;
    protected $mainTbl;

    protected function fakeDataHr()
    {
        $this->faker = \Faker\Factory::create();
        $hrManager = User::query()->where(User::TYPE, \HR_MANAGER)->first();
        HrOrganization::factory()->create([
            HrOrganization::USER_ID => $hrManager->id,
            HrOrganization::MAIL_ADDRESS => $hrManager->mail_address,
        ]);

        for ($i = 1; $i <= 25; $i++) {
            if ($i <= 5) {
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

    private function getItems($type)
    {
        $this->mainTbl = 'hrs';
        $items = HR::select('hrs.*', 'hrs.user_id', 'hrs.status', 'hr_organization.corporate_name_en', 'hr_organization.corporate_name_ja', 'job_type.name_ja as job_type', 'job_info.name_ja as job_info')->with(['mainJobs']);

        $items->join('hr_organization', 'hr_organization.id', '=', 'hrs.hr_organization_id');
        $items->join('job_type', 'job_type.id', '=', 'hrs.major_classification_id');
        $items->join('job_info', 'job_info.id', '=', 'hrs.middle_classification_id');
        if ($type == \HR) {
            $items->where('hrs.user_id', $this->hrId);
        }

        if ($type == COMPANY_MANAGER || $type == \COMPANY) {
            $items->where('hrs.status', HRS_STATUS_PUBLIC);
        }
        return $items;
    }

    public function getTableName($model)
    {
        return app($model)->getTable();
    }

    private function getItemsMatching($type, $model)
    {
        $this->mainTbl = $this->getTableName($model);
        $items = $model::select('*', "$this->mainTbl.id", "$this->mainTbl.updated_at")
            ->join('hrs', 'hrs.id', '=', "$this->mainTbl.hr_id")
            ->join('works', 'works.id', '=', "$this->mainTbl.work_id")
            ->where('hrs.status', '<>', HRS_STATUS_PRIVATE)
            ->whereNull('hrs.deleted_at')
            ->whereNull('works.deleted_at');

        if ($type == HR) {
            $items->where('hrs.user_id', $this->hrId);
        }
        if ($type == COMPANY) {
            $items->where('works.user_id', $this->userCompanyId);
        }
        return $items->where('display', 'on');
    }

    protected function searchWithHrOrg(Browser $browser, $type, $isClick = true, $model = HR::class, $table = 'hr-table-list')
    {
        if ($type == \HR) {
            $hrOrg = HrOrganization::query()->where(HrOrganization::STATUS, CONFIRM)
                ->where(HrOrganization::USER_ID, $this->hrId)
                ->inRandomOrder()->first();
        } else
            $hrOrg = HrOrganization::query()->where(HrOrganization::STATUS, CONFIRM)->inRandomOrder()->first();
        $items = ($table == 'hr-table-list') ? $this->getItems($type) : $this->getItemsMatching($type, $model);

        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000);
        if ($isClick) {
            $browser->scrollIntoView('@btn_organization_name')
                ->click('@btn_organization_name');
        } else {
            $browser->scrollIntoView('@hr_org_id')
                ->click('@hr_org_id');
        }

        $browser->select('@hr_org_id', $hrOrg->id);
        if (!$isClick) {
            $browser->scrollIntoView('@btn_search_with_conditions')
                ->click('@btn_search_with_conditions')->pause(5000);
        }
        $browser->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(2000)
            ->scrollIntoView('#' . $table)->pause(2000);

        $object = $items->where(HR::HR_ORGANIZATION_ID, $hrOrg->id)->orderByDesc($this->mainTbl . '.id')->get()->toArray();
        $this->assertCountRecord($browser, count($object), $table);
        if (count($object) > 0) {
            $browser->assertSeeIn('#' . $table, $object[0][HR::FULL_NAME_JA]);
        }
    }

    protected function searchByAge(Browser $browser, $type, $isClick = true, $model = HR::class, $table = 'hr-table-list')
    {
        $ageFrom = rand(18, 30);
        $ageTo = rand(40, 60);
        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000);
        if ($isClick) {
            $browser->scrollIntoView('@btn_age')
                ->click('@btn_age')->pause(1000);
        } else {
            $browser->scrollIntoView('@age_from')->pause(1000);
        }
        $browser->select('@age_from', $ageFrom)
            ->select('@age_to', $ageTo);
        if (!$isClick) {
            $browser->scrollIntoView('@btn_search_with_conditions')
                ->click('@btn_search_with_conditions')->pause(2000);
        }
        $browser->scrollIntoView('@btn_search_with_conditions')->pause(2000)
            ->click('@btn_search_with_conditions')->pause(5000)
            ->scrollIntoView('#' . $table);

        $items = ($table == 'hr-table-list') ? $this->getItems($type) : $this->getItemsMatching($type, $model);
        $items->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) >= ".$ageFrom);
        $items->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) <= ".$ageTo);
        $object = $items->orderByDesc($this->mainTbl . '.id')->get()->toArray();

        $count = count($object);
        $this->assertCountRecord($browser, $count, $table);

        if ($count >= 1) {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="' . $table . '"]/tbody/tr'));
            $dataList = [];
            if ($table == 'hr-table-list') {
                foreach ($attributes as $attribute) {
                    $dataAgeList[] = explode("\n", $attribute->getText())[5] ?? '';
                }

                foreach ($dataAgeList as $value) {
                    if (!$value) continue;
                    $this->assertTrue($value >= $ageFrom);
                    $this->assertTrue($value <= $ageTo);
                }
            } else {
                foreach ($attributes as $attribute) {
                    $temp = explode("\n", $attribute->getText())[0] ?? '';
                    $dataList[] = explode(" ", $temp)[0];
                }
                foreach ($dataList as $key => $value) {
                    if (!$value) break;
                    $this->assertEquals($value, $object[$key]['id']);
                }
            }
        }
    }

    private function assertCountRecord(Browser $browser, $itemCount, $table = 'hr-table-list')
    {
        $count = count($browser->elements('#' . $table . ' > tbody tr'));
        if ($itemCount > 20) {
            $this->assertEquals($count, 20);
            $browser->scrollIntoView('.pagination')->pause(5000)
                ->assertSeeIn('.pagination', '2');
        } else {
            $this->assertEquals($count, $itemCount);
            $browser->scrollIntoView('.pagination')->pause(3000)
                ->assertDontSeeIn('.pagination', '2');
        }
    }

    protected function searchByFinalEduDate(Browser $browser, $type, $isClick = true, $model = HR::class, $table = 'hr-table-list')
    {
        $dateFrom = $this->faker->dateTimeBetween('-20 years', '-10 years');
        $dateTo = $this->faker->dateTimeBetween('-5 years', '-1 days');

        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000);
        if ($isClick) {
            $browser->scrollIntoView('@btn_final_education_date')
                ->click('@btn_final_education_date');
        } else {
            $browser->scrollIntoView('@final_education_date_from_year');
        }
        $browser->select('@final_education_date_from_year', $dateFrom->format('Y'))
            ->select('@final_education_date_from_month', $dateFrom->format('m'))
            ->select('@final_education_date_to_year', $dateTo->format('Y'))
            ->select('@final_education_date_to_month', $dateTo->format('m'));
        if (!$isClick) {
            $browser->scrollIntoView('@btn_search_with_conditions')
                ->click('@btn_search_with_conditions')->pause(2000);
        }
        $browser->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(3000)
            ->scrollIntoView('#' . $table)->pause(2000);

        $items = ($table == 'hr-table-list') ? $this->getItems($type) : $this->getItemsMatching($type, $model);
        $dateFrom = Carbon::parse($dateFrom->format('Y-m'))->startOfMonth()->toDateString();
        $items->where('final_education_date', '>=', $dateFrom);
        $dateTo = Carbon::parse($dateTo->format('Y-m'))->endOfMonth()->toDateString();
        $items->where('final_education_date', '<=', $dateTo);
        $object = $items->orderByDesc($this->mainTbl . '.id')->get()->toArray();
        $count = count($object);
        $this->assertCountRecord($browser, $count, $table);

        if ($count >= 1) {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="' . $table . '"]/tbody/tr'));
            $dataList = [];
            if ($table == 'hr-table-list') {
                foreach ($attributes as $attribute) {
                    $dataList[] = explode("\n", $attribute->getText())[0] ?? '';
                }
            } else {
                foreach ($attributes as $attribute) {
                    $temp = explode("\n", $attribute->getText())[0] ?? '';
                    $dataList[] = explode(" ", $temp)[0];
                }
            }
            foreach ($dataList as $key => $value) {
                if (!$value) break;
                $this->assertEquals($value, $object[$key]['id']);
            }
        }
    }

    protected function searchByCheck(Browser $browser, $type, $btnSelect, $selector, $column, $value, $btnSpecify = null, $beforeSelector = null, $text = null, $btnReflect = null, $isCompany = false, $model = HR::class, $table = 'hr-table-list')
    {
        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000)
            ->scrollIntoView($btnSelect)
            ->click($btnSelect)->pause(1000);

        if (in_array($column, [HR::MIDDLE_CLASSIFICATION_ID, 'main_jobs'])) {
            if (!$isCompany)
                $browser->click($btnSpecify)->pause(1000);
            $browser->assertSee($text)
                ->click($beforeSelector)
                ->pause(1000);
        }

        $arrayCheck = [HR::GENDER, HR::FINAL_EDUCATION_CLASSIFICATION, HR::FINAL_EDUCATION_DEGREE, HR::WORK_FORM, HR::PREFERRED_WORKING_HOURS, HR::JAPANESE_LEVEL];
        if (in_array($column, $arrayCheck) && $isCompany) {
            $browser->pause(1000);
        } else
            $browser->click($selector)->pause(1000);

        if (in_array($column, [HR::MIDDLE_CLASSIFICATION_ID, 'main_jobs'])) {
            $browser->click($btnReflect);
        }

        if ($isCompany) {
            $browser->scrollIntoView('@btn_search_with_conditions')
                ->click('@btn_search_with_conditions')->pause(2000);
        }
        $browser->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(5000)
            ->scrollIntoView('#' . $table)->pause(5000);
        $items = ($table == 'hr-table-list') ? $this->getItems($type) : $this->getItemsMatching($type, $model);

        if (!in_array($column, [HR::FINAL_EDUCATION_DEGREE, 'main_jobs', HR::PREFERRED_WORKING_HOURS]))
            $items->whereIn('hrs.' . $column, $value);
        elseif ($column == 'main_jobs')
            $items->whereHas('mainJobs', fn($q) => $q->whereIn(HRMainJobCareer::JOB_ID, $value));
        elseif ($column == HR::FINAL_EDUCATION_DEGREE) {
            if (in_array(HR_EDUCATION_DEGREES_TYPE['university_or_more'], $value))
                $items->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 0, 3, true)));
            if (in_array(HR_EDUCATION_DEGREES_TYPE['aside_from_university'], $value))
                $items->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 3, 3, true)));
        } else {
            $items->where(HR::PREFERRED_WORKING_HOURS, '<=', 28);
        }
        $object = $items->orderByDesc($this->mainTbl . '.id')->get()->toArray();
        $count = count($object);
        $this->assertCountRecord($browser, $count, $table);
        if ($count >= 1) {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="' . $table . '"]/tbody/tr'));
            $dataList = [];
            foreach ($attributes as $attribute) {
                $temp = explode("\n", $attribute->getText())[0] ?? '';
                $dataList[] = explode(" ", $temp)[0];
            }
            foreach ($dataList as $key => $value) {
                if (!$value) break;
                $this->assertEquals($value, $object[$key]['id']);
            }
        }
    }

    protected function searchWithText(Browser $browser, $type, $model = HR::class, $table = 'hr-table-list')
    {
        $hr = HR::query()->inRandomOrder()->first();
        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000)
            ->scrollIntoView('@input_search')
            ->type('@input_search', $hr->full_name_ja)
            ->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(3000)
            ->scrollIntoView('#' . $table)->pause(2000);

        $items = ($table == 'hr-table-list') ? $this->getItems($type) : $this->getItemsMatching($type, $model);
        $object = $items->where('hrs.' . HR::FULL_NAME, 'LIKE', "%$hr->full_name_ja%")
            ->orWhere('hrs.' . HR::FULL_NAME_JA, 'LIKE', "%$hr->full_name_ja%")
            ->orderByDesc($this->mainTbl . '.id')->get()->toArray();
        $count = count($object);
        $this->assertCountRecord($browser, $count, $table);

        if ($count >= 1) {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="' . $table . '"]/tbody/tr'));
            $dataList = [];
            foreach ($attributes as $attribute) {
                $temp = explode("\n", $attribute->getText())[0] ?? '';
                $dataList[] = explode(" ", $temp)[0];
            }
            foreach ($dataList as $key => $value) {
                if (!$value) break;
                $this->assertEquals($value, $object[$key]['id']);
            }
        }
    }
}
