<?php

namespace Tests\Feature;

use App\Jobs\AutoUpdateStatusWorkJob;
use App\Models\City;
use App\Models\Company;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Passion;
use App\Models\Work;
use Carbon\Carbon;
use Complex\Exception;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class WorkTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $dataTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $jobType = JobType::query()->first();
        $jobInfo = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $jobType->id)->first();
        $this->dataTest = [
            Work::TITLE => $this->faker->name,
            Work::MAJOR_CLASSIFICATION_ID => $jobType->id,
            Work::MIDDLE_CLASSIFICATION_ID => $jobInfo->id,
            Work::COMPANY_ID => Company::factory()->create()->id,
            Work::APPLICATION_PERIOD_FROM => Carbon::now()->format('Y-m-d'),
            Work::APPLICATION_PERIOD_TO => Carbon::now()->addMonth()->format('Y-m-d'),
            Work::JOB_DESCRIPTION => $this->faker->text,
            Work::APPLICATION_CONDITION => $this->faker->text,
            Work::APPLICATION_REQUIREMENT => $this->faker->text,
            'language_requirements' => "[1,2,3]",
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
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreateWithItemsNull()
    {
        foreach ($this->dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->user_admin->post('api/work', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateSuccess()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/work', $this->dataTest);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testGetDetail()
    {
        $this->user_admin->post('api/work', $this->dataTest);
        $data = Work::query()->first();
        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER, COMPANY, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/work/'. $data->id);

            if($type == COMPANY) {
                $this->assertEquals(Response::HTTP_NOT_FOUND, $response->decodeResponseJson()['code']);
            } else {
                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $this->assertTrue($response->decodeResponseJson()['data'][Work::TITLE] == $this->dataTest[Work::TITLE]);
            }
        }
    }

    public function testGetDetailWithHr()
    {
        $this->dataTest[Work::VACATION] = $this->faker->text(30);
        $response = $this->user_admin->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $types = [HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/work/'. $id);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertTrue($response->decodeResponseJson()['data'][Work::VACATION] == $this->dataTest[Work::VACATION]);
        }

        Work::where('id', $id)->update([Work::STATUS => WORK_STATUS_OUT_OF_RECRUITMENT_PERIOD]);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/work/'. $id);
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->decodeResponseJson()['code']);
        }
    }

    public function testGetDetailWithCompany()
    {
        $this->loginWith(COMPANY);
        $this->dataTest[Work::JOB_DESCRIPTION] = $this->faker->text(30);
        $response = $this->user_login->post('api/work', $this->dataTest);
        $response = $this->user_login->get('api/work/'. $response->decodeResponseJson()['data']['id']);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertTrue($response->decodeResponseJson()['data'][Work::JOB_DESCRIPTION] == $this->dataTest[Work::JOB_DESCRIPTION]);
    }

    public function testEditWithItemsNull()
    {
        $response = $this->user_admin->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        foreach ($this->dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->user_admin->post('api/work/'. $id, $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testEditWithCompanyFail()
    {
        $this->dataTest[Work::TITLE] = $this->faker->text(30);
        $response = $this->user_admin->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $this->loginWith(COMPANY);
        $response = $this->user_login->post('api/work/'. $id, $this->dataTest);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->decodeResponseJson()['code']);
    }

    public function testEditWithCompanySuccess()
    {
        $this->dataTest[Work::TITLE] = $this->faker->text(30);
        $this->loginWith(COMPANY);
        $response = $this->user_login->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $response = $this->user_login->post('api/work/'. $id, $this->dataTest);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertTrue($response->decodeResponseJson()['data'][Work::TITLE] == $this->dataTest[Work::TITLE]);
    }

    public function testEditSuccess()
    {
        $this->loginWith(COMPANY);
        $types = [SUPER_ADMIN, COMPANY_MANAGER];
        $response = $this->user_login->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        foreach ($types as $type) {
            $this->loginWith($type);
            $this->dataTest[Work::JOB_DESCRIPTION] = $this->faker->text(30);
            $response = $this->user_login->post('api/work/'. $id, $this->dataTest);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertTrue($response->decodeResponseJson()['data'][Work::JOB_DESCRIPTION] == $this->dataTest[Work::JOB_DESCRIPTION]);
        }
    }

    public function testDeleteWithCompanyFail()
    {
        $response = $this->user_admin->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $this->loginWith(COMPANY);
        $response = $this->user_login->delete('api/work/'. $id, $this->dataTest);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->decodeResponseJson()['code']);
    }

    public function testDeleteWithCompanySuccess()
    {
        $this->loginWith(COMPANY);
        $response = $this->user_login->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $response = $this->user_login->delete('api/work/'. $id);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
    }

    public function testDeleteSuccess()
    {
        $this->loginWith(COMPANY);
        $types = [SUPER_ADMIN, COMPANY_MANAGER];
        foreach ($types as $type) {
            $response = $this->user_login->post('api/work', $this->dataTest);
            $id = $response->decodeResponseJson()['data']['id'];
            $this->loginWith($type);
            $response = $this->user_login->delete('api/work/'. $id, $this->dataTest);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testIndexSuccess()
    {
        $result = $this->user_admin->get('api/work')
            ->assertStatus(CODE_SUCCESS);
    }

    public function testShowPaginateSuccess()
    {
        $response = $this->user_admin->json('get', 'api/work')
            ->assertStatus(CODE_SUCCESS);
        $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
    }

    public function testNextPageSuccess()
    {
        $response = $this->user_admin->json('get', 'api/work?page=2&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testPaginateFirstPage()
    {
        $response = $this->user_admin->json('get', 'api/work?page=1&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testListWithCompany()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[Work::TITLE] = $this->faker->name;
            $this->dataTest[Work::APPLICATION_PERIOD_FROM] = Carbon::now()->format('Y-m-d');
            $this->dataTest[Work::APPLICATION_PERIOD_TO] = Carbon::now()->addMonth()->format('Y-m-d');
            $this->dataTest[Work::JOB_DESCRIPTION] = $this->faker->text;
            $this->dataTest[Work::APPLICATION_CONDITION] = $this->faker->text;
            $this->dataTest[Work::APPLICATION_REQUIREMENT] = $this->faker->text;
            if($i >= 3) {
                $this->loginWith(COMPANY);
                $this->user_login->post('api/work', $this->dataTest);
            } else {
                $this->user_admin->post('api/work', $this->dataTest);
            }
        }
        $response = $this->user_admin->json('get', 'api/work?page=1&per_page=1');
        $this->assertEquals(3, $response->decodeResponseJson()['data']['pagination']['total_records']);
    }

    public function testListWithHr()
    {
        $this->loginWith(COMPANY_MANAGER);
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[Work::TITLE] = $this->faker->name;
            $this->dataTest[Work::APPLICATION_PERIOD_FROM] = Carbon::now()->format('Y-m-d');
            $this->dataTest[Work::APPLICATION_PERIOD_TO] = Carbon::now()->addMonth()->format('Y-m-d');
            $this->dataTest[Work::JOB_DESCRIPTION] = $this->faker->text;
            $this->dataTest[Work::APPLICATION_CONDITION] = $this->faker->text;
            $this->dataTest[Work::APPLICATION_REQUIREMENT] = $this->faker->text;

            $this->user_login->post('api/work', $this->dataTest);
        }
        Work::query()->where('id', '<', 2)->update([Work::STATUS => WORK_STATUS_PAUSED]);
        $this->loginWith(HR);
        $response = $this->user_login->json('get', 'api/work?page=1&per_page=1');
        $this->assertEquals(4, $response->decodeResponseJson()['data']['pagination']['total_records']);
    }

    public function testSortByColumn()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[Work::TITLE] = $this->faker->name;
            $this->dataTest[Work::COMPANY_ID] = Company::factory()->create([Company::FULL_NAME => $this->faker->name])->id;
            $this->user_admin->post('api/work', $this->dataTest);
        }

        $fields = ["id", Work::TITLE, Work::UPDATED_AT];
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            foreach ($fields as $field) {
                $response = $this->user_login->json('get', 'api/work?field=' . $field . '&sort_by=desc');
                $listResult = $response->decodeResponseJson()['data']['result'];
                $count = count($listResult);
                foreach ($listResult as $key => $item) {
                    $keyItem = $count == $key + 1 ? $key : $key + 1;
                    $this->assertTrue($item[$field] >= $listResult[$keyItem][$field]);
                }
            }
        }
    }

    public function testSearchByKeySearch()
    {
        $company_name = [];
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[Work::TITLE] = $this->faker->name;
            $company_name[$i] = $this->faker->name;
            $this->dataTest[Work::COMPANY_ID] = Company::factory()->create([Company::COMPANY_NAME => $company_name[$i]])->id;
            $this->user_admin->post('api/work', $this->dataTest);
        }

        foreach ($company_name as $name) {
            $response = $this->user_admin->json('get', 'api/work?key_search='.$name);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $response->assertSeeText($name);
        }
    }

    public function testSearchByMiddleClass()
    {
        $ids = [];
        for ($i = 1; $i <= 5; $i++) {
            $middle_class = JobInfo::inRandomOrder()->first();
            $ids[$i] = [$middle_class->id, $middle_class->job_type_id];
            $this->dataTest[Work::MAJOR_CLASSIFICATION_ID] = $middle_class->job_type_id;
            $this->dataTest[Work::MIDDLE_CLASSIFICATION_ID] = $middle_class->id;
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            for ($i = 1; $i <= 5; $i++) {
                $this->loginWith($type);
                $response = $this->user_login->json('get', 'api/work?middle_classification_id%5B%5D=' . $ids[$i][0]);
                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $result = $response->decodeResponseJson()['data']['result'];
                $this->assertTrue($result[0]['middle_classification_id'] == $ids[$i][0]);
                $this->assertTrue($result[0]['major_classification_id'] == $ids[$i][1]);
                $this->assertTrue($response->decodeResponseJson()['data']['pagination']['total_records'] >= 1);
            }
        }
    }

    public function testSearchByIncome()
    {
        $income = rand(500, 1000);
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[Work::EXPECTED_INCOME] = $income * $i;
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->json('get', 'api/work?income_from=' . $income * 3 . '&income_to=' . $income * 4);

            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $this->assertCount(2, $result);
            foreach ($result as $item) {
                $this->assertGreaterThanOrEqual($income * 3, $item['expected_income']);
                $this->assertLessThanOrEqual($income * 4, $item['expected_income']);
            }
        }
    }

    public function testSearchByCity()
    {
        $city = [];
        for ($i = 1; $i <= 5; $i++) {
            $city[$i] = City::inRandomOrder()->first()->id;
            $this->dataTest[Work::CITY_ID] = $city[$i];
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            for ($i = 1; $i <= 5; $i++) {
                $this->loginWith($type);
                $response = $this->user_login->json('get', 'api/work?city_id=' . $city[$i]);

                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $result = $response->decodeResponseJson()['data']['result'];
                $this->assertCount(1, $result);
                $this->assertTrue($result[0]['city_id'] == $city[$i]);
            }
        }
    }

    public function testSearchByLanguage()
    {
        $ids = [];
        for ($i = 1; $i <= 5; $i++) {
            $ids[$i] = LanguageRequirement::inRandomOrder()->first()->id;
            $this->dataTest['language_requirements'] = "[".$ids[$i]."]";
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            for ($i = 1; $i <= 5; $i++) {
                $this->loginWith($type);
                $response = $this->user_login->json('get', 'api/work?language_requirements%5B%5D=' . $ids[$i]);
                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $result = $response->decodeResponseJson()['data']['result'];
                $this->assertTrue($result[0]['language_requirements'][0]['id'] == $ids[$i]);
                $this->assertTrue($response->decodeResponseJson()['data']['pagination']['total_records'] >= 1);
            }
        }
    }

    public function testSearchByFormOfEmployment()
    {
        $ids = [];
        for ($i = 1; $i <= 5; $i++) {
            $ids[$i] = $this->faker->randomElement([WORK_FULL_TIME_EMPLOYEE, WORK_CONTRACT_EMPLOYEE, WORK_TEMPORARY_EMPLOYEE, WORK_OTHER_EMPLOYEE]);
            $this->dataTest[Work::FORM_OF_EMPLOYMENT] = $ids[$i];
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            for ($i = 1; $i <= 5; $i++) {
                $this->loginWith($type);
                $response = $this->user_login->json('get', 'api/work?form_of_employment%5B%5D=' . $ids[$i]);
                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $result = $response->decodeResponseJson()['data']['result'];
                $this->assertTrue($result[0]['form_of_employment'] == $ids[$i]);
                $this->assertTrue($response->decodeResponseJson()['data']['pagination']['total_records'] >= 1);
            }
        }
    }

    public function testSearchByPassion()
    {
        $ids = [];
        for ($i = 1; $i <= 5; $i++) {
            $ids[$i] = Passion::inRandomOrder()->first()->id;
            $this->dataTest['passions'] = "[".$ids[$i]."]";
            $this->loginWith(COMPANY);
            $this->user_login->post('api/work', $this->dataTest);
        }
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY, HR_MANAGER, HR];
        foreach ($types as $type) {
            for ($i = 1; $i <= 5; $i++) {
                $this->loginWith($type);
                $response = $this->user_login->json('get', 'api/work?passion_works%5B%5D=' . $ids[$i]);
                $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
                $result = $response->decodeResponseJson()['data']['result'];
                $this->assertTrue($result[0]['passion'][0]['id'] == $ids[$i]);
                $this->assertTrue($response->decodeResponseJson()['data']['pagination']['total_records'] >= 1);
            }
        }
    }

    public function testPauseJob()
    {
        $response = $this->user_admin->post('api/work', $this->dataTest);
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $id = $response->decodeResponseJson()['data']['id'];
            $result = $this->user_login->post('api/work/update-status-work/' . $id, ['status' => WORK_STATUS_PAUSED]);
            if ($type == COMPANY) {
                $this->assertEquals(Response::HTTP_NOT_FOUND, $result->decodeResponseJson()['code']);
            } else {
                $this->assertEquals(Response::HTTP_OK, $result->decodeResponseJson()['code']);
                $this->assertTrue($result->decodeResponseJson()['data'][Work::STATUS] == WORK_STATUS_PAUSED);

                $this->loginWith(HR_MANAGER);
                $list = $this->user_login->json('get', 'api/work?page=1&per_page=1');
                $this->assertEquals(0, $list->decodeResponseJson()['data']['pagination']['total_records']);
            }
        }
    }

    public function testPauseJobWithCompany()
    {

        $this->loginWith(COMPANY);
        $response = $this->user_login->post('api/work', $this->dataTest);
        $id = $response->decodeResponseJson()['data']['id'];
        $result = $this->user_login->post('api/work/update-status-work/' . $id, ['status' => WORK_STATUS_PAUSED]);

        $this->assertEquals(Response::HTTP_OK, $result->decodeResponseJson()['code']);
        $this->assertTrue($result->decodeResponseJson()['data'][Work::STATUS] == WORK_STATUS_PAUSED);

        $this->loginWith(HR_MANAGER);
        $list = $this->user_login->json('get', 'api/work?page=1&per_page=1');
        $this->assertEquals(0, $list->decodeResponseJson()['data']['pagination']['total_records']);
    }

    public function testAutoUpdateStatusJob()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/work', $this->dataTest);

            $this->loginWith(HR);
            $list = $this->user_login->json('get', 'api/work?page=1&per_page=1');
            $this->assertEquals(1, $list->decodeResponseJson()['data']['pagination']['total_records']);

            $work_id = $response->decodeResponseJson()['data']['id'];
            $work = Work::find($work_id);
            $work->application_period_from = Carbon::now()->subMonth()->format('Y-m-d');
            $work->application_period_to = Carbon::now()->subDay()->format('Y-m-d');
            $work->save();

            $job = new AutoUpdateStatusWorkJob();
            $job->handle();
            $work->refresh();
            $this->assertDatabaseHas('works', [
                'id' => $work_id,
                Work::STATUS => WORK_STATUS_OUT_OF_RECRUITMENT_PERIOD
            ]);
            $list = $this->user_login->json('get', 'api/work?page=1&per_page=1');
            $this->assertEquals(0, $list->decodeResponseJson()['data']['pagination']['total_records']);
        }
    }
}
