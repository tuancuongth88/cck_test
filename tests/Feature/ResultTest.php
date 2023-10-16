<?php

namespace Tests\Feature;

use App\Jobs\NotificationHRJob;
use App\Models\Company;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\Interview;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Result;
use App\Models\Work;
use App\Notifications\OfferConfirmNotification;
use App\Notifications\OfferDeclineNotification;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ResultTest extends BaseTest
{
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

    public function testIndexSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            if($type == HR) {
                $this->user_login->get('api/logout');
            }
        }
    }

    public function testShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?page=1&per_page=1');
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
                    Result::HR_ID => $hr_id,
                    Result::WORK_ID => $work->id
                ];
            } else {
                $data = [
                    Result::HR_ID => HR::factory()->create([
                        HR::USER_ID => Auth::id(),
                        HR::CREATED_BY => Auth::id()
                    ])->id,
                    Result::WORK_ID => $work->id
                ];
            }
            $this->user_login->post('api/result', $data);
        }
        $this->loginWith(\HR);
        $response = $this->user_login->get('api/result');
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
                    Result::HR_ID => $hr->id,
                    Result::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => $user_company_id
                    ])->id
                ];
            } else {
                $data = [
                    Result::HR_ID => $hr->id,
                    Result::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => Auth::id()
                    ])->id
                ];
            }
            $this->user_login->post('api/result', $data);
        }
        $this->loginWith(\COMPANY);
        $response = $this->user_login->get('api/result');
        $this->assertCount(3, $response->decodeResponseJson()['data']['result']);
    }


    public function testDeleteResultWithEmpty()
    {
        Result::factory()->count(5)->create();
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/result/hide', []);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testDeleteWithCompanyFail() {
        $this->loginWith(\COMPANY);
        $work = Work::factory()->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => Company::factory()->create()->id]);
        Result::factory()->create([Result::WORK_ID => $work->id, Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_OFFER_CONFIRM]);
        Result::factory()->count(5)->create();
        $ids = Result::query()
            ->where(Result::STATUS_SELECTION, '!=', RESULT_STATUS_SELECTION_OFFER)
            ->pluck('id')->toArray();
        $response = $this->user_login->post('api/result/hide', ["ids" => $ids]);
        $this->assertEquals(CODE_NO_ACCESS, $response->decodeResponseJson()['code']);
        $this->assertEquals(trans('messages.mes.permission'), $response->decodeResponseJson()['message']);
    }

    public function testDeleteOfferSuccess()
    {
        $types = [SUPER_ADMIN];
        foreach ($types as $type) {
            Result::factory()->count(5)->create([Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_OFFER_DECLINE]);
            $ids = Result::query()
                ->where(Result::STATUS_SELECTION, '!=', RESULT_STATUS_SELECTION_OFFER)
                ->pluck('id')->toArray();
            $this->loginWith($type);
            $response = $this->user_login->post('api/result/hide', ["ids" => $ids]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $response->assertJson([
                "code" => 200,
                "message" => "成功を削除",
                "data" => null
            ]);
            $isOff = Result::query()->whereIn('id', $ids)->where(Result::DISPLAY, 'off')->count();
            $this->assertTrue(count($ids) == $isOff);
        }
    }

    public function testSearchByGender()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Result::factory()->count(5)->create()->first();
        $gender = rand(HRS_GENDER_MALE, HRS_GENDER_FEMALE);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?gender%5B%5D='.$gender);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $count = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->where(HR::GENDER, $gender)
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                })
                ->whereIn('results.id', $id)->count();
            if($count == 0)
                $this->assertTrue($result == null);
            else
                $this->assertCount($count, $result);
        }
    }

    public function testSearchByAge()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Result::factory()->count(5)->create()->first();
        $ageFrom = rand(18, 20);
        $ageTo = rand(30, 40);

        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?age_from='.$ageFrom.'&age_to='.$ageTo);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $count = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                })
                ->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) >= " . $ageFrom)
                ->whereRaw("(DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), '%Y') + 0) <= " . $ageTo)
                ->whereIn('results.id', $id)->count();

            if($count == 0)
                $this->assertTrue($result == null);
            else
                $this->assertCount($count, $result);
        }
    }

    public function testSearchByEduDate()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Result::factory()->count(5)->create()->first();
        $eduDateFrom = $this->faker->dateTimeBetween('-10 years', '-4 years')->format('Y-m');
        $eduDateTo = $this->faker->dateTimeBetween('-2 years')->format('Y-m');
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?edu_date_from='.$eduDateFrom.'&edu_date_to='.$eduDateTo);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $dateFrom = Carbon::parse($eduDateFrom)->toDateString();
            $dateTo = Carbon::parse($eduDateTo)->toDateString();
            $count = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                })
                ->where(HR::FINAL_EDUCATION_DATE, '>=', $dateFrom)
                ->where(HR::FINAL_EDUCATION_DATE, '<=', $dateTo)
                ->whereIn('results.id', $id)->count();

            if($count == 0)
                $this->assertTrue($result == null);
            else
                $this->assertCount($count, $result);
        }
    }

    private function formArray($column, $array, $field)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Result::factory()->count(5)->create()->first();
        foreach ($types as $type) {
            $this->loginWith($type);
            $rand = $this->faker->randomElements($array, 2);
            $response = $this->user_login->get('api/result?'.$field.'%5B%5D='.$rand[0].'&'.$field.'%5B%5D='.$rand[1]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $count = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                })
                ->whereIn($column, $rand)
                ->whereIn('results.id', $id)->count();

            if($count == 0)
                $this->assertTrue($result == null);
            else
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
        Result::factory()->count(5)->create()->first();
        $rand = $this->faker->randomElement(HR_EDUCATION_DEGREES_TYPE);
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/result?edu_degree%5B%5D='.$rand);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $hr = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                });
            if($rand == HR_EDUCATION_DEGREES_TYPE['university_or_more']) {
                $count = $hr->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 0, 3, true)))
                    ->whereIn('results.id', $id)->count();

            } else {
                $count = $hr->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 3, 3, true)))
                    ->whereIn('results.id', $id)->count();
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

    public function testSearchByText()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        Result::factory()->count(5)->create()->first();
        $name = HR::query()->inRandomOrder()->pluck(HR::FULL_NAME_JA)->first();
        foreach ($types as $type) {
            $this->loginWith($type);
            $name = $name . $this->faker->text(5);
            $response = $this->user_login->get('api/result?search='.$name);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $id = [];
            foreach ($result as $value) {
                $id[] = $value['id'];
            }
            $count = Result::query()
                ->join('hrs', 'hrs.id', '=', 'results.hr_id')
                ->when(in_array($type, [\COMPANY, COMPANY_MANAGER]), function ($query) {
                    $query->where(HR::STATUS, HRS_STATUS_PUBLIC);
                })
                ->where(HR::FULL_NAME_JA, $name)
                ->orWhere(HR::FULL_NAME, $name)
                ->whereIn('results.id', $id)->count();

            $this->assertCount($count, $result);
        }
    }

    private function fakeResult($status = RESULT_STATUS_SELECTION_OFFER, $time = null)
    {
        $this->loginWith(\HR);
        $theHr = HR::query()->where(HR::HR_ORGANIZATION_ID, Auth::user()->hrOrganization->id)->first();
        $this->loginWith(\COMPANY);

        Work::factory()->count(3)->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => $this->company->id,
            Work::USER_ID => Auth::id()
        ]);
        $theJob = Work::query()->where(Work::COMPANY_ID, $this->company->id)->first();

        return Result::factory()->create([
            Result::WORK_ID => $theJob->id,
            Result::HR_ID => $theHr->id,
            Result::STATUS_SELECTION => $status,
            Result::TIME => $time ?: Carbon::now()->addDay(1)->format('Ymd')
        ]);
    }

    public function testDetailFail()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/result/-1');
            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_FORBIDDEN);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));
        }
    }

    public function testDetailSuccess()
    {
        $result_id = $this->fakeResult()->id;
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/result/'.$result_id)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $data = $resp->getData()->data;
            $this->assertTrue(!empty($data));
        }
    }

    public function testUpdateWithIdFail()
    {
        $this->fakeResult();
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $status = rand(RESULT_STATUS_SELECTION_OFFER_DECLINE, RESULT_STATUS_SELECTION_OFFER_CONFIRM);
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->put('api/result/-1', ['status' => $status]);
            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));
        }
    }

    public function testUpdateWithStatusFail()
    {
        $result = $this->fakeResult();
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->put('api/result/' . $result->id, ['status' => 9999]);
            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('api.result.status'));
        }
    }

    public function testUpdateWithHrConfirmOfferSuccess()
    {
        $result = $this->fakeResult();
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $this->put('api/result/' . $result->id, ['status' => 3])->assertStatus(Response::HTTP_OK);
        }
    }

    public function testUpdateNotPassSuccess()
    {
        $result = $this->fakeResult(RESULT_STATUS_SELECTION_NOT_PASS);
        $types = [\HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $result = $this->get('api/result/' . $result->id)->assertStatus(Response::HTTP_OK);
            $this->assertTrue($result->decodeResponseJson()['data']['status_selection'] == RESULT_STATUS_SELECTION_NOT_PASS);
        }
    }

    public function testUpdateOfficialDeclineSuccess()
    {
        $result = $this->fakeResult(RESULT_STATUS_SELECTION_OFFER);
        $types = [\HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $data = [
                'status' => RESULT_STATUS_SELECTION_OFFER_DECLINE,
                'note' => $this->faker->text];
            $result = $this->put('api/result/' . $result->id, $data)->assertStatus(Response::HTTP_OK);
            $this->assertTrue($result->decodeResponseJson()['data']['status_selection'] == RESULT_STATUS_SELECTION_OFFER_DECLINE);
        }
    }

    public function testUpdateOfficialExpirationSuccess()
    {
        $time = Carbon::now()->subDay(1)->format('Ymd');
        $result = $this->fakeResult(RESULT_STATUS_SELECTION_OFFER, $time);
        $types = [\HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $result = $this->get('api/result/' . $result->id)->assertStatus(Response::HTTP_OK);
            $this->assertTrue($result->decodeResponseJson()['data']['time'] < Carbon::now()->format('Ymd'));
        }
    }

    public function testUpdateOfficialOfferTimeEditSuccess()
    {
        $time = Carbon::now()->format('Ymd');
        $result = $this->fakeResult(RESULT_STATUS_SELECTION_OFFER, $time);
        $types = [\HR, COMPANY_MANAGER, SUPER_ADMIN];
        $id = $result->id;
        foreach ($types as $type) {
            $this->loginWith($type);
            $data = [
                'status' => RESULT_STATUS_SELECTION_OFFER,
                'time' => Carbon::now()->addMonth(1)->format('Ymd')
            ];
            if ($type == \HR){
                $response = $this->put('api/result/' . $id, $data);
                $this->assertTrue($response->decodeResponseJson()['code'] == CODE_UNAUTHORIZED);
            }else{
                $this->put('api/result/' . $id, $data)->assertStatus(Response::HTTP_OK);
            }
        }
    }

    public function testUpdateHireDateFail()
    {
        $result_id = $this->fakeResult()->id;
        $types = [COMPANY_MANAGER, SUPER_ADMIN];
        $data = [
            'status' => RESULT_STATUS_SELECTION_OFFER_CONFIRM,
            'hire_date' => Carbon::now()->subMonth()->format('Y-m-d')
        ];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('put', 'api/result/'.$result_id, $data);
            $this->assertEquals($resp->decodeResponseJson()['code'], HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($resp->decodeResponseJson()['message'][0], 'The hire date must be a date after yesterday.');
        }

        $types = [HR_MANAGER, \HR, \COMPANY];
        $data = [
            'status' => RESULT_STATUS_SELECTION_OFFER_CONFIRM,
            'hire_date' => Carbon::now()->addDay()->format('Y-m-d')
        ];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('put', 'api/result/'.$result_id, $data);
            $this->assertEquals($resp->decodeResponseJson()['code'], CODE_UNAUTHORIZED);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.mes.permission'));
        }
    }

    public function testUpdateHireDateSuccess()
    {
        $result_id = $this->fakeResult()->id;
        $types = [COMPANY_MANAGER, SUPER_ADMIN];
        $data = [
            'status' => RESULT_STATUS_SELECTION_OFFER_CONFIRM,
            'hire_date' => Carbon::now()->addDay()->format('Y-m-d')
        ];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('put', 'api/result/'.$result_id, $data);
            $this->assertEquals($resp->decodeResponseJson()['code'], CODE_SUCCESS);
            $this->assertDatabaseHas('results', [
                'id' => $result_id,
                Result::HIRE_DATE => $data['hire_date']
            ]);
        }
    }

    private function assertNotification($object, $type)
    {
        $notifications = Notification::query()
            ->where('type', $type)
            ->where('notifiable_id', $object->user_id)->get();
        $this->assertNotEmpty($notifications);
        $this->assertTrue($notifications->count() > 0);
    }

    private function freshData($result)
    {
        Result::query()->find($result->id)->update([Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_OFFER, Result::DISPLAY => 'on']);
        Schema::disableForeignKeyConstraints();
        Interview::query()->truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function fakeDataResult($user_company_id){
        for ($i = 1; $i <= 5; $i++) {
            $this->loginWith(\COMPANY_MANAGER);
            $hr = HR::factory()->create([
                HR::USER_ID => Auth::id(),
                HR::CREATED_BY => Auth::id()
            ]);
            if($i<=3) {
                $data = [
                    Result::HR_ID => $hr->id,
                    Result::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => $user_company_id
                    ])->id
                ];
            } else {
                $data = [
                    Result::HR_ID => $hr->id,
                    Result::WORK_ID => Work::factory()->create([
                        Work::STATUS => WORK_STATUS_RECRUITING,
                        Work::USER_ID => Auth::id()
                    ])->id
                ];
            }
            $this->user_login->post('api/result', $data);
        }
    }
}
