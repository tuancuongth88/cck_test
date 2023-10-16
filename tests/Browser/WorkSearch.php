<?php


namespace Tests\Browser;


use App\Models\City;
use App\Models\Company;
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
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;

trait WorkSearch
{
    protected $userCompanyId;

    protected function fakeDataJob()
    {
        $user = User::query()->where('type', \COMPANY)->first();
        $this->userCompanyId = $user->id;
        $company = Company::query()->where('user_id', $user->id)->first();
        if (!$user) {
            $user = User::factory()->create([User::TYPE => COMPANY_MANAGER]);
        }
        if (!$company) {
            $company = Company::factory()->create(['user_id' => $user->id, Company::STATUS => CONFIRM]);
        }

        for ($i = 0; $i <= 20; $i++) {
            $jobType = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->first();
            $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->inRandomOrder()->first();

            $dataTest = [
                Work::TITLE => $this->faker->name,
                Work::USER_ID => $user->id,
                Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
                Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
                Work::COMPANY_ID => $company->id,
                Work::APPLICATION_PERIOD_FROM => $this->faker->dateTimeBetween('-2 days', '+2 weeks')->format('Y-m-d'),
                Work::APPLICATION_PERIOD_TO => $this->faker->dateTimeBetween('+1 month', '+2 months')->format('Y-m-d'),
                Work::JOB_DESCRIPTION => $this->faker->text,
                Work::APPLICATION_CONDITION => $this->faker->text,
                Work::APPLICATION_REQUIREMENT => $this->faker->text,
                Work::OTHER_SKILL => $this->faker->text(200),
                Work::FORM_OF_EMPLOYMENT => $this->faker->randomElement([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE]),
                Work::WORKING_TIME_FROM => Carbon::now()->format('H:i'),
                Work::WORKING_TIME_TO => Carbon::now()->addHour(8)->format('H:i'),
                Work::VACATION => $this->faker->text(200),
                Work::EXPECTED_INCOME => rand(200, 1000),
                Work::CITY_ID => City::query()->inRandomOrder()->pluck('id')->first(),
                Work::TREATMENT_WELFARE => $this->faker->text(100),
                Work::BONUS_PAY_RISE => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::OVER_TIME => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::TRANSFER => rand(WORK_CONFIRM_YES, WORK_CONFIRM_NO),
                Work::INTERVIEW_FOLLOW => rand(1, 5),
            ];

            $work = Work::create($dataTest);
            $language = LanguageRequirement::query()->pluck('id');
            $passion = Passion::query()->pluck('id');

            $this->addRelationHasMany(LanguageRequirementWork::class, $this->faker->randomElements($language, 3),
                $work->id, 'language_requirement_id');

            $this->addRelationHasMany(PassionWork::class, $this->faker->randomElements($passion, 4),
                $work->id, 'passion_id');
            $this->companyId = User::query()->where(User::TYPE, \COMPANY)->first()->id;
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

    private function getItemsWork($type)
    {
        $userId = User::query()->where(User::TYPE, $type)->first()->id;
        $items = Work::query()->with(['passion', 'languageRequirements', 'company', 'city', 'majorClassification', 'middleClassification'])
            ->select('works.*',
                DB::raw('(CASE WHEN user_favorites.relation_id IS NOT NULL THEN true ELSE false END) AS is_favorite'))
            ->leftJoin('user_favorites', function ($join) use ($userId) {
                $join->on('works.id', '=', 'user_favorites.relation_id')
                    ->where('user_favorites.user_id', '=', $userId)
                    ->where('user_favorites.type', FAVORITE_TYPE_WORK);
            });
        if ($type == \COMPANY) {
            $items->where('works.user_id', $this->userCompanyId);
        }

        if ($type == HR_MANAGER || $type == \HR) {
            $items->where(Work::STATUS, WORK_STATUS_RECRUITING);
        }
        return $items;
    }

    private function assertCountRecordWork(Browser $browser, $itemCount, $table)
    {
        $count = count($browser->elements($table . ' > tbody tr'));
        if ($itemCount > 20) {
            $this->assertEquals($count, 20);
            $browser->scrollIntoView('.pagination')
                ->assertSeeIn('.pagination', '2');
        } else {
            $this->assertEquals($count, $itemCount);
        }
    }

    private function assertCountItems(Browser $browser, $items)
    {
        $count = $items->count();
        if ($count > 0) {
            if ($count > 20) {
                $browser->scrollIntoView('@number_case')->pause(2000)
                    ->assertSeeIn('@number_case', 20);
                $browser->scrollIntoView('.pagination')
                    ->assertSeeIn('.pagination', '2');
            } else {
                $browser->scrollIntoView('@number_case')
                    ->assertSeeIn('@number_case', $count);
            }
        } else
            $browser->scrollIntoView('@number_case')
                ->assertSeeIn('@number_case', 0);
    }

    private function assertFormHr(Browser $browser, $items)
    {
        $items->get();
        $i = 1;
        foreach ($items as $item) {
            $itemIndex = '#dusk_job_list > div > div:nth-child(' . $i . ')';
            $occupation = JobType::query()->where('id', $item->major_classification_id)->first()->name_ja;
            $city = City::query()->where('id', $item->city_id)->first()->name_ja;

            $browser->scrollIntoView($itemIndex)
                ->assertSeeIn($itemIndex, $item->title)
                ->assertSeeIn($itemIndex, $occupation)
                ->assertSeeIn($itemIndex, $city);
            $i++;
        }

        $this->assertCountItems($browser, $items);
    }

    private function assertFormCompany(Browser $browser, $items, $table, $text)
    {
        if ($table == '#dusk_job_to_offer') {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*[@id="dusk_job_to_offer"]/tbody/tr'));
        } else {
            $attributes = $browser->driver->findElements(WebDriverBy::xpath('//*/tbody/tr'));
        }

        $dataAgeList = [];
        $jobs = $items->orderByDesc('works.id')->get()->toArray();

        if (count($jobs) >= 1) {
            $this->assertCountRecordWork($browser, count($jobs), $table);

            foreach ($attributes as $attribute) {
                $dataAgeList[] = explode("\n", $attribute->getText())[0] ?? '';
            }
            foreach ($dataAgeList as $key => $value) {
                if (!$value) continue;
                $this->assertEquals($value, $jobs[$key]['id']);
            }
        } else {
            $browser->scrollIntoView($table)
                ->assertSee($text);
        }
    }

    protected function searchByCheckWork(Browser $browser, $type, $btnSelect, $selector, $column, $value, $beforeSelector = null, $text = null, $btnReflect = null, $flag = true, $formHr = true, $isSelectOffer = false)
    {
        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000)
            ->scrollIntoView($btnSelect)
            ->click($btnSelect)->pause(1000);

        if ($column == Work::MIDDLE_CLASSIFICATION_ID || ($column == Work::CITY_ID && $flag)) {
            $browser->assertSee($text)
                ->scrollIntoView($beforeSelector)
                ->click($beforeSelector);
        }

        $browser->click($selector)->pause(2000);
        if ($column == Work::MIDDLE_CLASSIFICATION_ID) {
            $browser->click($btnReflect);
        }

        $browser->scrollIntoView('@btn_search_with_conditions')->pause(2000)
            ->click('@btn_search_with_conditions')->pause(5000);

        $items = $this->getItemsWork($type);

        if ($column == LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID) {
            $items->whereIn('works.id', function ($query) use ($value) {
                $query->select('work_id')
                    ->from('language_requirement_works')
                    ->whereIn(LanguageRequirementWork::LANGUAGE_REQUIREMENT_ID, $value);
            })->get();
        } elseif ($column == PassionWork::PASSION_ID) {
            $items->whereIn('works.id', function ($query) use ($value) {
                $query->select('work_id')
                    ->from('passion_works')
                    ->whereIn('passion_id', $value);
            });
        } else {
            $items->whereIn($column, $value);
        }

        if (in_array($type, [\HR, HR_MANAGER]) || ($type == SUPER_ADMIN && $formHr)) {
            $this->assertFormHr($browser, $items);
        } else {
            if ($isSelectOffer) {
                $table = '#dusk_job_to_offer';
                $text = 'データなし';
            } else {
                $table = '#dusk_job_table';
                $text = '該当するデータは存在しません';
            }
            $this->assertFormCompany($browser, $items, $table, $text);
        }
    }

    protected function searchWithTextWork(Browser $browser, $type, $formHr = true, $isSelectOffer = false)
    {
        if ($isSelectOffer) {
            $table = '#dusk_job_to_offer';
            $text = 'データなし';
        } else {
            $table = '#dusk_job_table';
            $text = '該当するデータは存在しません';
        }

        $company_name = Company::query()->inRandomOrder()->first()->company_name_jp;
        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000)
            ->scrollIntoView('@input_search')
            ->type('@input_search', $company_name)
            ->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(2000);

        if ($formHr)
            $browser->scrollIntoView('@number_case')->pause(2000);
        else
            $browser->scrollIntoView($table)->pause(2000);

        $items = $this->getItemsWork($type);
        $items->whereIn('company_id', function ($query) use ($company_name) {
            $query->select('id')
                ->from('companies')
                ->where('company_name', "like", "%" . $company_name . "%")
                ->orWhere('company_name_jp', "like", "%" . $company_name . "%");
        });

        if (in_array($type, [\HR, HR_MANAGER]) || ($type == SUPER_ADMIN && $formHr)) {
            $this->assertFormHr($browser, $items);
        } else {
            $this->assertFormCompany($browser, $items, $table, $text);
        }
    }

    protected function searchWithCompany(Browser $browser, $type)
    {
        if ($type == \COMPANY) {
            $company = Company::query()->where(Company::STATUS, CONFIRM)
                ->where(Company::USER_ID, $this->userCompanyId)
                ->inRandomOrder()->first();
        } else
            $company = Company::query()->where(Company::STATUS, CONFIRM)->inRandomOrder()->first();

        $browser->scrollIntoView('@btn_clear_settings')
            ->click('@btn_clear_settings')->pause(1000)
            ->scrollIntoView('@btn_company_name')
            ->click('@btn_company_name')->pause(2000)
            ->select('@company_id', $company->id)
            ->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(2000)
            ->scrollIntoView('#dusk_job_to_offer');

        $items = $this->getItemsWork($type);
        $items->where(Work::COMPANY_ID, $company->id);
        $this->assertFormCompany($browser, $items, '#dusk_job_to_offer', 'データなし');
    }

    protected function searchWithIncome(Browser $browser, $type, $formHr = true, $isSelectOffer = false, $isClick = true)
    {
        if ($isSelectOffer) {
            $table = '#dusk_job_to_offer';
            $text = 'データなし';
        } else {
            $table = '#dusk_job_table';
            $text = '該当するデータは存在しません';
        }

        $income = [200, 250, 300, 350, 400, 450, 500, 550, 600, 650, 700];
        $incomeFrom = $this->faker->randomElements($income)[0];
        $incomeTo = $incomeFrom + 300;
        $browser->scrollIntoView('@btn_clear_settings')->click('@btn_clear_settings')->pause(1000);
        if ($isClick) {
            $browser->scrollIntoView('@btn_income')
                ->click('@btn_income');
        } else {
            $browser->scrollIntoView('@income_from');
        }
        $browser->select('@income_from', $incomeFrom)
            ->select('@income_to', $incomeTo)
            ->scrollIntoView('@btn_search_with_conditions')
            ->click('@btn_search_with_conditions')->pause(2000);

        if ($formHr)
            $browser->scrollIntoView('@number_case')->pause(2000);
        else
            $browser->scrollIntoView($table)->pause(3000);

        $items = $this->getItemsWork($type);
        $items->where(Work::EXPECTED_INCOME, '>=', $incomeFrom)
            ->where(Work::EXPECTED_INCOME, '<=', $incomeTo);

        if (in_array($type, [\HR, HR_MANAGER]) || ($type == SUPER_ADMIN && $formHr)) {
            $this->assertFormHr($browser, $items);
        } else {
            $this->assertFormCompany($browser, $items, $table, $text);
        }
    }
}
