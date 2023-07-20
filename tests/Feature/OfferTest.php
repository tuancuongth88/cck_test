<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Offer;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Http\Response;

class OfferTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $types;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->types = [SUPER_ADMIN, HR_MANAGER, COMPANY_MANAGER, \HR, \COMPANY];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreateWithAllEmpty()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/offer', []);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateWithHrItemNotExist()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            if($type == COMPANY) {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING, Work::COMPANY_ID => $this->user_login->company->id])->create();
            }
            else {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING])->create();
            }
            $response = $this->user_login->post('api/offer', [
                Offer::HR_ID => 999,
                Offer::WORK_ID => $work->id
            ]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateWithHrItemIsPrivate()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            if($type == COMPANY) {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING, Work::COMPANY_ID => $this->user_login->company->id])->create();
            }
            else {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING])->create();
            }
            $response = $this->user_login->post('api/offer', [
                Offer::HR_ID => HR::factory()->state([HR::CREATED_BY => Auth::id(), HR::STATUS => HRS_STATUS_PRIVATE])->create()->id,
                Offer::WORK_ID => $work->id
            ]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateWithWorkItemNotExist()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/offer', [
                Offer::HR_ID => HR::factory()->state([HR::CREATED_BY => Auth::id()])->create()->id,
                Offer::WORK_ID => 999
            ]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateWithWorkStatus()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/offer', [
                Offer::HR_ID => HR::factory()->state([HR::CREATED_BY => Auth::id()])->create()->id,
                Offer::WORK_ID => Work::factory()->state([Work::STATUS => WORK_STATUS_PAUSED])->create()->id
            ]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testCreateSuccess()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            if($type == COMPANY) {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING, Work::COMPANY_ID => $this->company->id])->create();
            }
            else {
                $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING])->create();
            }
            $data = [
                Offer::HR_ID => HR::factory()->state([HR::CREATED_BY => Auth::id()])->create()->id,
                Offer::WORK_ID => $work->id
            ];
            $response = $this->user_login->post('api/offer', $data);

            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertDatabaseHas('offers', [
                Offer::HR_ID => $data[Offer::HR_ID],
                Offer::WORK_ID => $data[Offer::WORK_ID]
            ]);
        }
    }

    public function testIndexSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            if($type == \HR) {
                $this->user_login->get('api/logout');
            }
         }
    }

    public function testShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testListWithHr()
    {
        $this->loginWith(\HR);
        $hr_id = $this->hr->id;
        for ($i = 1; $i <= 5; $i++) {
            $this->loginWith(\COMPANY_MANAGER);
            $work = Work::factory()->state([Work::STATUS => WORK_STATUS_RECRUITING])->create();
            if($i<=3) {
                $data = [
                    Offer::HR_ID => $hr_id,
                    Offer::WORK_ID => $work->id
                ];
            } else {
                $data = [
                    Offer::HR_ID => HR::factory()->create([
                        HR::USER_ID => Auth::id(),
                        HR::CREATED_BY => Auth::id()
                    ])->id,
                    Offer::WORK_ID => $work->id
                ];
            }
            $this->user_login->post('api/offer', $data);
        }
        $this->loginWith(\HR);
        $response = $this->user_login->get('api/offer');
        $this->assertCount(3, $response->decodeResponseJson()['data']['result']);
    }

    public function testListWithCompany()
    {
        $this->loginWith(\COMPANY);
        $user_company_id = $this->company->user_id;
        for ($i = 1; $i <= 5; $i++) {
            $this->loginWith(\COMPANY_MANAGER);
            $hr = HR::factory()->create([
                HR::USER_ID => Auth::id(),
                HR::CREATED_BY => Auth::id()
            ]);
            if($i<=3) {
                $data = [
                    Offer::HR_ID => $hr->id,
                    Offer::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => $user_company_id
                    ])->id
                ];
            } else {
                $data = [
                    Offer::HR_ID => $hr->id,
                    Offer::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => Auth::id()
                    ])->id
                ];
            }
            $this->user_login->post('api/offer', $data);
        }
        $this->loginWith(\COMPANY);
        $response = $this->user_login->get('api/offer');
        $this->assertCount(3, $response->decodeResponseJson()['data']['result']);
    }

    public function testSortByColumn()
    {
        Offer::factory()->count(5)->create();

        foreach ($this->types as $type) {
            $this->loginWith($type);
            $fields = ['id', 'offer_date', 'full_name', 'work_title', 'status'];
            foreach ($fields as $field) {
                $response = $this->user_login->json('get', 'api/offer?field=' . $field . '&sort_by=desc');
                $listResult = $response->decodeResponseJson()['data']['result'];
                $count = count($listResult);
                foreach ($listResult as $key => $item) {
                    $keyItem = $count == $key + 1 ? $key : $key + 1;
                    if ($field == 'status') {
                        $this->assertTrue($item['status_selection_name'] >= $listResult[$keyItem]['status_selection_name']);
                    } else {
                        $this->assertTrue($item[$field] >= $listResult[$keyItem][$field]);
                    }
                }
                $this->user_login->get('api/logout');
            }
        }
    }

    public function testDeleteOfferWithEmpty()
    {
        Offer::factory()->count(5)->create();
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/offer/remove-offer', []);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testDeleteWithCompanyFail() {
        $this->loginWith(\COMPANY);
        $work = Work::factory()->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => Company::factory()->create()->id]);
        Offer::factory()->create([Offer::WORK_ID => $work->id, Offer::STATUS => OFFER_STATUS_CONFIRM]);
        Offer::factory()->count(5)->create();
        $ids = Offer::query()
            ->where(Offer::STATUS, '!=', OFFER_STATUS_REQUESTING)
            ->pluck('id')->toArray();
        $response = $this->user_login->post('api/offer/remove-offer', ["ids" => $ids]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $this->assertEquals(trans('api.offer.delete.request'), $response->decodeResponseJson()['message']);
    }

    public function testDeleteOfferSuccess()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            Offer::factory()->count(5)->create();
            $ids = Offer::query()
                ->where(Offer::STATUS, '!=', OFFER_STATUS_REQUESTING)
                ->pluck('id')->toArray();
            $this->loginWith($type);
            $response = $this->user_login->post('api/offer/remove-offer', ["ids" => $ids]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $response->assertJson([
                    "code" => 200,
                    "message" => "成功を削除",
                    "data" => null
            ]);
            $isOff = Offer::query()->whereIn('id', $ids)->where(Offer::DISPLAY, 'off')->count();
            $this->assertTrue(count($ids) == $isOff);
        }
    }

    public function testSearchByHrOrg()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $offer = Offer::factory()->count(5)->create()->first();
        $hrOrg_id = $offer->hr->hrOrg->id;
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?hr_org_id=' . $hrOrg_id);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertCount(5, $response->decodeResponseJson()['data']['result']);
        }
    }

    public function testSearchByGender()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $gender = rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?gender%5B%5D='.$gender);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $count = HR::query()->where(HR::GENDER, $gender)->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchByAge()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $ageFrom = rand(18, 20);
        $ageTo = rand(30, 40);

        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?age_from='.$ageFrom.'&age_to='.$ageTo);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $dateFrom = Carbon::now()->subYears($ageFrom)->toDateString();
            $dateTo = Carbon::now()->subYears($ageTo)->toDateString();
            $count = HR::query()
                ->where(HR::DATE_OF_BIRTH, '<=', $dateFrom)
                ->where(HR::DATE_OF_BIRTH, '>=', $dateTo)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchByEduDate()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $eduDateFrom = $this->faker->dateTimeBetween('-10 years')->format('Y-m');
        $eduDateTo = $this->faker->dateTimeBetween('-2 years')->format('Y-m');
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?edu_date_from='.$eduDateFrom.'&edu_date_to='.$eduDateTo);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $dateFrom = Carbon::parse($eduDateFrom)->toDateString();
            $dateTo = Carbon::parse($eduDateTo)->toDateString();
            $count = HR::query()
                ->where(HR::FINAL_EDUCATION_DATE, '>=', $dateFrom)
                ->where(HR::FINAL_EDUCATION_DATE, '<=', $dateTo)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }

    private function formArray($column, $array, $field)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        foreach ($types as $type) {
            $this->loginWith($type);
            $rand = $this->faker->randomElements($array, 2);

            $response = $this->user_login->get('api/offer?'.$field.'%5B%5D='.$rand[0].'&'.$field.'%5B%5D='.$rand[1]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $count = HR::query()
                ->whereIn($column, $rand)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }
    public function testSearchByEduClass()
    {
        $this->formArray(HR::FINAL_EDUCATION_CLASSIFICATION, HR_FINAL_EDUCATION, 'edu_class');
    }

    public function testSearchByEduDegree()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $rand = $this->faker->randomElement(HR_EDUCATION_DEGREES_TYPE);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/offer?edu_degree%5B%5D='.$rand);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            if($rand == HR_EDUCATION_DEGREES_TYPE['university_or_more']) {
                $count = HR::query()
                    ->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 0, 3, true)))
                    ->whereIn('id', $hr_id)->count();
            } else {
                $count = HR::query()
                    ->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 3, 3, true)))
                    ->whereIn('id', $hr_id)->count();
            }
            $this->assertCount($count, $result);
        }
    }

    public function testSearchByWorkForm()
    {
        $this->formArray(HR::WORK_FORM, HRS_WORK_FORM, 'work_forms');
    }

    public function testSearchByJapanLevel()
    {
        $japan = LanguageRequirement::query()->pluck('id');
        $this->formArray(HR::JAPANESE_LEVEL, $japan, 'japan_levels');
    }

    public function testSearchByMiddleClass()
    {
        $major = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
        $middle = JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major)->pluck('id');
        $this->formArray(HR::MIDDLE_CLASSIFICATION_ID, $middle, 'middle_class');
    }

    public function testSearchByMainJob()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $mainJob = HRMainJobCareer::query()->pluck('id');
        foreach ($types as $type) {
            $this->loginWith($type);
            $rand = $this->faker->randomElements($mainJob, 2);
            $response = $this->user_login->get('api/offer?main_job_ids%5B%5D='.$rand[0].'&main_job_ids%5B%5D='.$rand[1]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $count = HR::query()->whereIn('id', $hr_id)->whereHas('mainJobs',function ($q) use ($rand){
                $q->whereIn('id', $rand);
            })->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchByText()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Offer::factory()->count(5)->create()->first();
        $name = HR::query()->inRandomOrder()->pluck(HR::FULL_NAME_JA)->first();
        foreach ($types as $type) {
            $this->loginWith($type);
            $name = $name . $this->faker->text(5);
            $response = $this->user_login->get('api/offer?search='.$name);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = $value['hr_id'];
            }
            $count = HR::query()
                ->where(HR::FULL_NAME_JA, $name)
                ->orWhere(HR::FULL_NAME, $name)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }
}
