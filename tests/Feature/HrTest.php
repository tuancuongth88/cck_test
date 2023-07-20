<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\Offer;
use App\Models\Result;
use App\Models\UploadFile;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Spatie\DownloadLink\Test\DownloadLinkTest;
use Illuminate\Http\UploadedFile as FileFaker;

class HrTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $dataTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $hrOrganization = HrOrganization::factory()->create(['user_id' => Auth::id()]);

        $major = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
        $middle = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major)->inRandomOrder()->pluck('id')->first();
        $depart = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_1)->inRandomOrder()->pluck('id')->first();
        $job = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $depart)->inRandomOrder()->pluck('id')->first();

        $this->dataTest = [
            HR::COUNTRY_ID => 1,
            HR::HR_ORGANIZATION_ID => $hrOrganization->id,
            HR::FULL_NAME => $this->faker->name,
            HR::FULL_NAME_JA => $this->faker->name,
            HR::GENDER => rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE),
            HR::DATE_OF_BIRTH => $this->faker->dateTimeBetween('-73 years', '-18 years')->format('Y-m-d'),
            HR::WORK_FORM => "",
            HR::PERSONAL_PR_SPECIAL_NOTES => "",
            HR::JAPANESE_LEVEL => 1,
            HR::FINAL_EDUCATION_DATE => "2020-10",
            HR::FINAL_EDUCATION_CLASSIFICATION => "1",
            HR::FINAL_EDUCATION_DEGREE => 1,
            HR::MAJOR_CLASSIFICATION_ID => $major,
            HR::MIDDLE_CLASSIFICATION_ID => $middle,
            HR::REMARKS => "",
            HR::TELEPHONE_NUMBER => "",
            HR::MOBILE_PHONE_NUMBER => "",
            HR::MAIL_ADDRESS => "test@gmail.com",
            HR::ADDRESS_CITY => "",
            HR::ADDRESS_DISTRICT => "",
            HR::ADDRESS_NUMBER => "",
            HR::ADDRESS_OTHER => "",
            HR::HOMETOWN_CITY => "",
            HR::HOME_TOWN_DISTRICT => "",
            HR::HOME_TOWN_NUMBER => "",
            HR::HOME_TOWN_OTHER => "",
            "main_jobs" => [
                [
                    "main_job_career_date_from" => "2021-01",
                    "to_now" => "no",
                    "main_job_career_date_to" => "2021-02",
                    "department_id" => $depart,
                    "job_id" => $job,
                    "detail" => "gsfdfg"
                ]
            ],
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testIndexSuccess()
    {
        $result = $this->user_admin->get('api/hr')
            ->assertStatus(CODE_SUCCESS);
    }

    public function testShowPaginateSuccess()
    {
        $response = $this->user_admin->json('get', 'api/hr')
            ->assertStatus(CODE_SUCCESS);
        $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
    }

    public function testNextPageSuccess()
    {
        $response = $this->user_admin->json('get', 'api/hr?page=2&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testPaginateFirstPage()
    {
        $response = $this->user_admin->json('get', 'api/hr?page=1&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testSortByColumn()
    {
        $this->loginWith(HR_MANAGER);
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[HR::FULL_NAME] = $this->faker->name;
            $this->dataTest[HR::JAPANESE_LEVEL] = rand(1, 5);
            $this->post('api/hr', $this->dataTest);
        }
        $fields = ["id", "full_name", "japanese_level", "corporate_name_en", "date_of_birth", "status"];

        foreach ($fields as $field) {
            $response = $this->user_admin->json('get', 'api/hr?field='.$field.'&sort_by=desc');
            $listResult = $response->decodeResponseJson()['data']['result'];
            $count = count($listResult);
            foreach ($listResult as $key => $item) {
                $keyItem = $count == $key + 1 ? $key : $key + 1;
                if ($field == "date_of_birth") {
                    $this->assertTrue($item['age'] >= $listResult[$keyItem]['age']);
                } else {
                    $this->assertTrue($item[$field] >= $listResult[$keyItem][$field]);
                }
            }
        }
    }

    public function testSearchByFullName()
    {
        $name = 'test';
        for ($i = 1; $i <= 5; $i++) {
            $this->dataTest[HR::FULL_NAME] = $name . ' ' . $this->faker->name;
            $this->user_admin->post('api/hr', $this->dataTest);
        }
        $response = $this->user_admin->json('get', 'api/hr?search=' . $name)
            ->assertStatus(CODE_SUCCESS);
        $response->assertSee($name);
    }

    public function testFilterWithHrOrg()
    {
        $hrOrganization = HrOrganization::factory()->count(2)->create(['user_id' => Auth::id()])->pluck('id');
        for ($i = 0; $i < 5; $i++) {
            if ($i >= 2) {
                $this->dataTest[HR::HR_ORGANIZATION_ID] = 3;
            } else {
                $this->dataTest[HR::HR_ORGANIZATION_ID] = $i + 1;
            }
            $this->user_admin->post('api/hr', $this->dataTest);
        }
        $response = $this->user_admin->json('get', 'api/hr?hr_org_id=3')
            ->assertStatus(CODE_SUCCESS);
        $response->assertJsonCount(3, 'data.result');
    }

    public function testFilterWithAge()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->dataTest[HR::DATE_OF_BIRTH] = Carbon::now()->subYear(10 * $i)->format('Y-m-d');
            $this->user_admin->post('api/hr', $this->dataTest);
        }
        $response = $this->user_admin->json('get', 'api/hr?age_from=30&age_to=40')
            ->assertStatus(CODE_SUCCESS);
        $response->assertJsonCount(2, 'data');
    }

    public function testFilterWithJapanLevels()
    {
        $this->loginWith(HR_MANAGER);
        for ($i = 0; $i < 5; $i++) {
            if ($i >= 3) {
                $this->dataTest[HR::JAPANESE_LEVEL] = 3;
            } else {
                $this->dataTest[HR::JAPANESE_LEVEL] = rand(1,6);
            }
            $this->post('api/hr', $this->dataTest);
        }
        $response = $this->json('get', 'api/hr?japan_levels%5B%5D=3')
            ->assertStatus(CODE_SUCCESS);
    }

    public function testCreateWithItemsNull()
    {
        $dataTest = $this->dataTest;
        unset(
            $dataTest[HR::GENDER],
            $dataTest[HR::WORK_FORM],
            $dataTest[HR::PREFERRED_WORKING_HOURS],
            $dataTest[HR::PERSONAL_PR_SPECIAL_NOTES],
            $dataTest[HR::REMARKS],
            $dataTest[HR::TELEPHONE_NUMBER],
            $dataTest[HR::MOBILE_PHONE_NUMBER],
            $dataTest[HR::ADDRESS_CITY],
            $dataTest[HR::ADDRESS_DISTRICT],
            $dataTest[HR::ADDRESS_NUMBER],
            $dataTest[HR::ADDRESS_OTHER],
            $dataTest[HR::HOMETOWN_CITY],
            $dataTest[HR::HOME_TOWN_DISTRICT],
            $dataTest[HR::HOME_TOWN_NUMBER],
            $dataTest[HR::HOME_TOWN_OTHER],
        );
        foreach ($dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->user_admin->post('api/hr', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateSuccess()
    {
        $response = $this->user_admin->post('api/hr', $this->dataTest);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
    }

    public function testValidateUpdate()
    {
        $dataTest = $this->dataTest;
        $hr = HR::factory()->create([HR::CREATED_BY => Auth::id()]);
        unset(
            $dataTest[HR::GENDER],
            $dataTest[HR::WORK_FORM],
            $dataTest[HR::PREFERRED_WORKING_HOURS],
            $dataTest[HR::PERSONAL_PR_SPECIAL_NOTES],
            $dataTest[HR::REMARKS],
            $dataTest[HR::TELEPHONE_NUMBER],
            $dataTest[HR::MOBILE_PHONE_NUMBER],
            $dataTest[HR::ADDRESS_CITY],
            $dataTest[HR::ADDRESS_DISTRICT],
            $dataTest[HR::ADDRESS_NUMBER],
            $dataTest[HR::ADDRESS_OTHER],
            $dataTest[HR::HOMETOWN_CITY],
            $dataTest[HR::HOME_TOWN_DISTRICT],
            $dataTest[HR::HOME_TOWN_NUMBER],
            $dataTest[HR::HOME_TOWN_OTHER],
        );
        foreach ($dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->user_admin->put('api/hr/' . $hr->id, $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testUpdateSuccess()
    {
        $dataTest = $this->dataTest;
        $response = $this->user_admin->post('api/hr', $dataTest);
        $hr_id = $response->decodeResponseJson()['data']['id'];
        $dataTest[HR::MAIL_ADDRESS] = $this->faker->email;
        $response = $this->user_admin->put('api/hr/' . $hr_id, $dataTest);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertTrue($response->decodeResponseJson()['data'][HR::MAIL_ADDRESS] == $dataTest[HR::MAIL_ADDRESS]);
    }

    public function testGetDetail()
    {
        $response = $this->user_admin->post('api/hr', $this->dataTest);
        $data = HR::query()->first();
        $result = $this->user_admin->get('api/hr/' . $data->id)
            ->assertStatus(CODE_SUCCESS);
        $this->assertTrue($response->decodeResponseJson()['data'][HR::MAIL_ADDRESS] == $result->decodeResponseJson()['data'][HR::MAIL_ADDRESS]);
    }

    public function testDownloadFileHr()
    {
        $this->user_admin->post('api/hr', $this->dataTest);
        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/hr/download', []);
            $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
            $response->assertHeader('Content-Disposition', 'attachment; filename=HR_list.csv');
            $response->assertOk();
            $response->assertSuccessful();
            $response->assertHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0, private');
            $response->assertHeader('Pragma', 'no-cache');
            $response->assertHeader('Expires', '0');
        }
    }

    public function testImportHrWithFileNotExist()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/hr/check-file-import', ['file_id' => 9999]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testImportHrWithFileTooLarge()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        $file = FileFaker::fake()->create('HR_list.csv', 10000);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/upload', ['file' => $file]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testImportHrWithFileError()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $file_id = UploadFile::factory()->create([
                UploadFile::FILE_NAME => 'HR_list.csv',
                UploadFile::FILE_PATH => 'tests/FileTest/HR_list.csv',
                UploadFile::FILE_EXTENSION => 'csv'
            ])->id;
            $response = $this->user_login->post('api/hr/check-file-import', ['file_id' => $file_id]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertTrue($response->decodeResponseJson()['data']['success'] == null);
            $this->assertTrue($response->decodeResponseJson()['data']['error'] != null);
        }
    }

    public function testImportHrSuccess()
    {
        $file_id = UploadFile::factory()->create([
            UploadFile::FILE_NAME => 'HR_list.csv',
            UploadFile::FILE_PATH => 'tests/FileTest/HR_list.csv',
            UploadFile::FILE_EXTENSION => 'csv'
        ])->id;
        $hrOrg = HrOrganization::factory()->create([
            'user_id' => Auth::id(),
            HrOrganization::CORPORATE_NAME_EN => 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
            HrOrganization::CORPORATE_NAME_JA => 'LOD人材開発株式会社'
        ]);

        $types = [SUPER_ADMIN, HR_MANAGER, HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/hr/check-file-import', ['file_id' => $file_id]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);

            $result = $this->user_login->post('api/hr/import', ['file_id' => $file_id]);
            $this->assertEquals(Response::HTTP_OK, $result->decodeResponseJson()['code']);
            $this->assertDatabaseHas('hrs', [
                HR::HR_ORGANIZATION_ID => $hrOrg->id,
            ]);
        }
    }

    public function testHrMatchingInfoDetail()
    {
        $this->loginWith(\HR);
        $hr_id = HR::first()->id;
        Entry::factory()->create([Entry::HR_ID=> $hr_id]);
        Offer::factory()->create([Entry::HR_ID=> $hr_id]);
        $interview = Interview::factory()->create([Interview::HR_ID => $hr_id]);
        Result::factory()->create([
            Result::HR_ID => $interview->hr_id,
            Result::INTERVIEW_ID => $interview->id,
            Result::WORK_ID => $interview->work_id
        ]);
        $resp = $this->get('api/hr/'.$hr_id)->assertOk();
        $this->assertEquals(Response::HTTP_OK, $resp->decodeResponseJson()['code']);
        $result = $resp->decodeResponseJson()['data'];
        $this->assertTrue($result['entries'][0]['hr_id'] === $hr_id);
        $this->assertTrue($result['offers'][0]['hr_id'] === $hr_id);
        $this->assertTrue($result['interviews'][0]['hr_id'] === $hr_id);
        $this->assertTrue($result['results'][0]['hr_id'] === $hr_id);
    }
}
