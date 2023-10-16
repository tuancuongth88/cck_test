<?php

namespace Tests\Feature;

use App\Jobs\NotificationInterviewJob;
use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\Interview;
use App\Models\InterviewInfo;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Result;
use App\Models\User;
use App\Models\Work;
use App\Notifications\InterviewAdjustmentAdjustedNotification;
use App\Notifications\InterviewAdjustmentAdjustingNotification;
use App\Notifications\InterviewCancelNotification;
use App\Notifications\InterviewDeclineNotification;
use App\Notifications\InterviewHrRejectTimeNotification;
use App\Notifications\InterviewNoPassNotification;
use App\Notifications\InterviewOfferNotification;
use App\Notifications\InterviewPassNotification;
use App\Notifications\InterviewURLSettingCompanyNotification;
use App\Notifications\InterviewURLSettingManagerNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\In;
use Tests\TestCase;
use Faker\Factory as Faker;

class InterviewTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $types;

    public function setUp(): void
    {
        $this->types = [HR_MANAGER, COMPANY_MANAGER];
        $this->faker = Faker::create();
        parent::setUp();

    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testIndexSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    private function fieldSort($field)
    {
        Interview::factory()->count(5)->create();

        foreach ($this->types as $type) {
            $this->loginWith($type);
            $sort = ['asc', 'desc'];
            foreach ($sort as $sortBy) {
                $response = $this->json('get', 'api/interview', [
                    'field' => $field,
                    'sort_by' => $sortBy
                ])->assertSeeText(CODE_SUCCESS)
                    ->assertOk();
                $listResult = $response->decodeResponseJson()['data']['result'];

                if($field == 'updated_at') {
                    $field_sort = 'updating_date';
                } elseif ($field == 'code') {
                    $field_sort = 'entry_code';
                }
                else {
                    $field_sort = $field;
                }
                $count = count($listResult);
                foreach ($listResult as $key => $item) {
                    $keyItem = $count == $key + 1 ? $key : $key + 1;
                    if($sortBy == 'asc')
                        $this->assertTrue($item[$field_sort] <= $listResult[$keyItem][$field_sort]);
                    else
                        $this->assertTrue($item[$field_sort] >= $listResult[$keyItem][$field_sort]);
                }
            }
        }
    }

    public function testListSortByIdSuccess()
    {
        $this->fieldSort('id');
    }

    public function testListSortByCodeSuccess()
    {
        $this->fieldSort('code');
    }

    public function testListSortByInterviewDateSuccess()
    {
        $this->fieldSort('interview_date');
    }

    public function testListSortByFullNameSuccess()
    {
        $this->fieldSort('full_name');
    }

    public function testListSortByJobNameSuccess()
    {
        $this->fieldSort('job_name');
    }

    public function testListSortByStatusSelectionSuccess()
    {
        $this->fieldSort('status_selection');
    }

    public function testListSortByInterviewAdjustmentSuccess()
    {
        $this->fieldSort('status_interview_adjustment');
    }

    public function testDeleteSuccess()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, COMPANY_MANAGER];
        foreach ($types as $type) {
            $this->loginWith($type);
            $status_fail = STATUS_SELECTIONS;
            unset($status_fail[INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL], $status_fail[INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE]);
            $id_fail = Interview::factory()->create([Interview::STATUS_SELECTION => $this->faker->randomElement(array_keys($status_fail))])->id;

            $response = $this->json('post', 'api/interview/hide', ['ids' => [$id_fail]]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
            $this->assertDatabaseHas('interviews', [
                'id' => $id_fail,
                Interview::DISPLAY => 'on'
            ]);

            $status = [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE];
            Interview::factory()->count(3)->create([
                Interview::STATUS_SELECTION => $this->faker->randomElement($status)
            ]);
            $interview_id = Interview::query()
                ->whereIn(Interview::STATUS_SELECTION, [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE])
                ->inRandomOrder()->pluck('id')->first();
            $response = $this->json('post', 'api/interview/hide', ['ids' => [$interview_id]]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertDatabaseHas('interviews', [
                'id' => $interview_id,
                Interview::DISPLAY => 'off'
            ]);
        }
    }

    public function testDeleteWithHr()
    {
        $this->loginWith(\HR);
        $status = [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE];

        $theHr = HR::query()->where(HR::HR_ORGANIZATION_ID, Auth::user()->hrOrganization->id)->first();
        $interview_status_fail = Interview::factory()->create([Interview::HR_ID => $theHr->id])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$interview_status_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $interview_fail = Interview::factory()->create([
            Interview::HR_ID => HR::factory()->create([HR::HR_ORGANIZATION_ID => 10])->id,
            Interview::STATUS_SELECTION => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/interview/hide', ['ids' => [$interview_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $interview = Interview::factory()->create([Interview::STATUS_SELECTION => $this->faker->randomElement($status)])->id;
        $response = $this->post('api/interview/hide', ['ids' => [$interview]]);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertDatabaseHas('interviews', [
            'id' => $interview,
            Interview::DISPLAY => 'off'
        ]);
    }

    public function testDeleteWithCompany()
    {
        $this->loginWith(\COMPANY);
        $status = [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE];

        $interview_status_fail = Interview::factory()->create()->id;
        $response = $this->post('api/interview/hide', ['ids' => [$interview_status_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $interview_fail = Interview::factory()->create([
            Interview::WORK_ID => Work::factory()->create([Work::COMPANY_ID => 10])->id,
            Interview::STATUS_SELECTION => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/interview/hide', ['ids' => [$interview_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $interview = Interview::factory()->create([Interview::STATUS_SELECTION => $this->faker->randomElement($status)])->id;
        $response = $this->post('api/interview/hide', ['ids' => [$interview]]);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertDatabaseHas('interviews', [
            'id' => $interview,
            Interview::DISPLAY => 'off'
        ]);
    }

    public function testDeleteWithEmpty()
    {
        Interview::factory()->create([
            Interview::STATUS_SELECTION => rand(INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE)
        ]);
        $hrUser = User::where(User::TYPE, HR)->first();
        $resp = $this->actingAs($hrUser)->post('api/interview/hide', ['ids' => []])->assertDontSeeText(CODE_SUCCESS);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $resp->decodeResponseJson()['code']);
    }

    private function searchInput($param, $column, $rand)
    {
        Interview::factory()->count(5)->create();
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview', $param)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $this->assertEquals(Response::HTTP_OK, $resp->decodeResponseJson()['code']);
            $result = $resp->decodeResponseJson()['data']['result'];

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

    public function testSearchByHrOrg()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY, HR_MANAGER];
        Interview::factory()->count(5)->create();
        $interview = Interview::query()->inRandomOrder()->first();
        $hrOrg_id = $interview->hr->hrOrg->id;
        foreach ($types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview?hr_org_id=' . $hrOrg_id);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertTrue($response->getData()->data->result != null);
        }
    }

    public function testSearchGenderSuccess()
    {
        $param = [
            'gender' => [rand(HRS_GENDER_FEMALE, HRS_GENDER_MALE)]
        ];
        $this->searchInput($param, HR::GENDER, $param['gender']);
    }

    public function testSearchAgeSuccess()
    {
        Interview::factory()->count(5)->create();
        $param = [
            'age_from' => rand(18, 20),
            'age_to' => rand(30, 40)
        ];
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview', $param)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $result = $resp->decodeResponseJson()['data']['result'];

            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = data_get($value, 'hr_id');
            }

            $dateFrom = Carbon::now()->subYears($param['age_from'])->toDateString();
            $dateTo = Carbon::now()->subYears($param['age_to'])->toDateString();
            $count = HR::query()
                ->where(HR::DATE_OF_BIRTH, '<=', $dateFrom)
                ->where(HR::DATE_OF_BIRTH, '>=', $dateTo)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchEducationDateSuccess()
    {
        Interview::factory()->count(5)->create();
        $param = [
            'edu_date_from' => $this->faker->dateTimeBetween('-10 years', '-5 years')->format('Y-m'),
            'edu_date_to' => $this->faker->dateTimeBetween('-3 years')->format('Y-m'),
        ];
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview', $param)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $result = $resp->decodeResponseJson()['data']['result'];

            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = data_get($value, 'hr_id');
            }
            $dateFrom = Carbon::parse($param['edu_date_from'])->toDateString();
            $dateTo = Carbon::parse($param['edu_date_to'])->toDateString();
            $count = HR::query()
                ->where(HR::FINAL_EDUCATION_DATE, '>=', $dateFrom)
                ->where(HR::FINAL_EDUCATION_DATE, '<=', $dateTo)
                ->whereIn('id', $hr_id)->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchWorkFormSuccess()
    {
        $this->searchInput(['work_forms' => HRS_WORK_FORM], HR::WORK_FORM, HRS_WORK_FORM);
    }

    public function testSearchJapanLevelsSuccess()
    {
        $language = LanguageRequirement::query()->pluck('id')->toArray();
        $param = [
            'japan_levels' => $this->faker->randomElements($language, 2),
        ];
        $this->searchInput($param, HR::JAPANESE_LEVEL, $param['japan_levels']);
    }

    public function testSearchMainJobsSuccess()
    {
        Interview::factory()->count(5)->create();
        $mainJob = HRMainJobCareer::query()->pluck('id');
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $rand = $this->faker->randomElements($mainJob, 2);
            $response = $this->user_login->get('api/interview?main_job_ids%5B%5D='.$rand[0].'&main_job_ids%5B%5D='.$rand[1]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $hr_id = [];
            foreach ($result as $value) {
                $hr_id[] = data_get($value, 'hr_id');
            }
            $count = HR::query()->whereIn('id', $hr_id)->whereHas('mainJobs',function ($q) use ($rand){
                $q->whereIn('id', $rand);
            })->count();
            $this->assertCount($count, $result);
        }
    }

    public function testSearchEducationDegreeSuccess()
    {
        Interview::factory()->count(5)->create();
        $rand = $this->faker->randomElement(HR_EDUCATION_DEGREES_TYPE);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/interview?edu_degree%5B%5D='.$rand);
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

    public function testSearchMiddleClassificationSuccess()
    {
        $major = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
        $param = ['middle_class' => JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major)->pluck('id')];
        $this->searchInput($param, HR::MIDDLE_CLASSIFICATION_ID, $param['middle_class']);
    }

    public function testSearchByText()
    {
        Interview::factory()->count(5)->create();
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $name = HR::query()->inRandomOrder()->pluck(HR::FULL_NAME_JA)->first();
        foreach ($types as $type) {
            $this->loginWith($type);
            $name = $name . $this->faker->text(5);
            $response = $this->user_login->get('api/interview?search='.$name);
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

    public function testDetailFail()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview/999');
            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_FORBIDDEN);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));
        }

        $interview_id = Interview::factory()->create([
            Interview::WORK_ID => Work::factory()->create()->id,
            Interview::HR_ID => HR::factory()->create()->id,
        ])->id;
        $types = [\COMPANY, \HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview/'.$interview_id);
            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_FORBIDDEN);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));
        }
    }

    public function testDetailSuccess()
    {
        foreach ($this->types as $type) {
            $interview_id = Interview::factory()->create()->id;
            $this->loginWith($type);
            $resp = $this->json('get', 'api/interview/'.$interview_id)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $data = $resp->getData()->data;
            $this->assertTrue(!empty($data));
        }
    }

    private function fakeData()
    {
        return [
            "interview_type" => INTERVIEW_TYPE_PRIVATE,
            "times" => [
                [
                    "date" => Carbon::now()->addDays(1)->format('Y-m-d'),
                    "start_time" => "9:00",
                    "start_time_at" => "AM",
                    "expected_time" => "60"
                ],
                [
                    "date" => Carbon::now()->addDays(2)->format('Y-m-d'),
                    "start_time" => "9:00",
                    "start_time_at" => "AM",
                    "expected_time" => "60"
                ],
                [
                    "date" => Carbon::now()->addDays(3)->format('Y-m-d'),
                    "start_time" => "9:00",
                    "start_time_at" => "AM",
                    "expected_time" => "60"
                ],
                [
                    "date" => Carbon::now()->addDays(4)->format('Y-m-d'),
                    "start_time" => "9:00",
                    "start_time_at" => "AM",
                    "expected_time" => "60"
                ],
                [
                    "date" => Carbon::now()->addDays(5)->format('Y-m-d'),
                    "start_time" => "9:00",
                    "start_time_at" => "AM",
                    "expected_time" => "60"
                ],
            ]
        ];

    }

    public function testSetupCalendarWithValidation()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $interview_id = Interview::factory()->create()->id;
            $this->loginWith($type);
            $dataFail = [
                "date" => Carbon::now()->subMonth()->format('Y-m-d'),
                "start_time" => Carbon::now()->format('H'),
                "start_time_at" => "",
                "expected_time" => rand(1,10)
            ];
            foreach ($dataFail as $key => $value) {
                $data = $this->fakeData();
                $data['times'][rand(0,4)][$key] = $value;
                $resp = $this->put('api/interview/setup-calendar/' . $interview_id, $data);
                $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $interview_id = Interview::factory()->create([Interview::TYPE => INTERVIEW_TYPE_PRIVATE])->id;
            $data = $this->fakeData();
            $data['interview_type'] = INTERVIEW_TYPE_GROUP;
            $resp = $this->put('api/interview/setup-calendar/' . $interview_id, $data);
            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
            $this->assertTrue($resp->decodeResponseJson()['message'] == trans('api.interview.review.check.type'));
        }
    }

    public function testSetupCalendarWithInterviewStatusFail()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $statusList = [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS];
            foreach ($statusList as $status)
            {
                $interview = Interview::factory()->create([Interview::STATUS_SELECTION => $status]);
                $data = $this->fakeData();
                $resp = $this->put('api/interview/setup-calendar/' . $interview->id, $data);
                $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
                $this->assertTrue($resp->decodeResponseJson()['message'] == trans('api.interview.hr.decline'));
            }

            $statusList = array_keys(STATUS_ADJUSTINGS);
            foreach ($statusList as $status)
            {
                if($status == INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT) continue;
                $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => $status]);
                $data = $this->fakeData();
                $resp = $this->put('api/interview/setup-calendar/' . $interview->id, $data);
                $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
                $this->assertTrue($resp->decodeResponseJson()['message'] == trans('api.interview.review.check.status'));
            }
        }
    }

    public function testSetupCalendarWithCalendarExist()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $statusList = [INTERVIEW_INFO_STATUS_HR_DECLINE, INTERVIEW_INFO_STATUS_COMPANY_NO_PASS, INTERVIEW_INFO_STATUS_COMPANY_OFFER, INTERVIEW_INFO_STATUS_TEMPORARY];
            foreach ($statusList as $status) {
                $interview_info = InterviewInfo::factory()->create([InterviewInfo::STATUS => $status]);
                $interview_id = $interview_info->interview_id;
                $data = $this->fakeData();
                $resp = $this->put('api/interview/setup-calendar/' . $interview_id, $data);
                $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
                $this->assertTrue($resp->decodeResponseJson()['message'] == trans('api.interview.info.check.create'));
            }
        }
    }

    private function assertNotification($interview, $type, $typeNotify)
    {
        $company = $interview->work->company;
        $hrOrg = $interview->hr->hrOrg;
        if ($typeNotify == NOTIFY_FOR_COMPANY)
            $users = User::query()->where(function ($query) use ($company) {
                $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                    ->orWhereHas('company', function ($q) use ($company) {
                        $q->where('id', $company->id);
                    });
            })->pluck('id');
        elseif ($typeNotify == NOTIFY_FOR_HR)
            $users = User::query()->where(function ($query) use ($hrOrg) {
                $query->whereIn('type', [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])
                    ->orWhereHas('hrOrganization', function ($q) use ($hrOrg) {
                        $q->where('id', $hrOrg->id);
                    });
            })->pluck('id');
        else
            $users = User::query()->whereIn('type', [SUPER_ADMIN, HR_MANAGER])->get();
        $notifications = Notification::query()
            ->where('type', $type)
            ->whereIn('notifiable_id', $users)->get();
        $this->assertNotEmpty($notifications);
        $this->assertTrue($notifications->count() > 0);
    }

    private function setupCalendarPrivate($isOffer = false)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            if ($isOffer)
                $interview = Interview::factory()->create([Interview::INTERVIEW_CODE => '']);
            else
                $interview = Interview::factory()->create();
            $data = $this->fakeData();
            $resp = $this->put('api/interview/setup-calendar/' . $interview->id, $data);
            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_OK);
            $calendar = $resp->decodeResponseJson()['data']['calendar'];
            $interviewInfoId = $calendar[count($calendar) - 1]['id'];
            $this->assertDatabaseHas('interview_infos', [
                'id' => $interviewInfoId,
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_TEMPORARY,
                InterviewInfo::INTERVIEW_NUMBER => INTERVIEW_INFO_NUMBER_FIRST
            ]);

            $interviewInfo = InterviewInfo::find($interviewInfoId);
            $calendar_interview = json_decode($interviewInfo->calendar_interview, true);
            $this->assertEquals($calendar_interview, $data['times']);

            $this->assertDatabaseHas('interviews', [
                'id' => $interview->id,
                Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING,
            ]);

            $this->assertNotification($interview, InterviewAdjustmentAdjustingNotification::class, NOTIFY_FOR_HR);
        }
    }

    public function testSetupCalendarEntryPrivateSuccess()
    {
        $this->setupCalendarPrivate();
        $this->artisan('migrate:fresh --seed');

        //check number value
        $this->loginWith(\COMPANY);
        $interview = Interview::factory()->create();
        $data = $this->fakeData();
        $resp = $this->put('api/interview/setup-calendar/' . $interview->id, $data);
        $interviewInfoId = $resp->decodeResponseJson()['data']['id'];
        InterviewInfo::find($interviewInfoId)->update([InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_FIRST]);
        Interview::find($interview->id)->update([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT]);
        $resp = $this->put('api/interview/setup-calendar/' . $interview->id, $data);

        $calendar = $resp->decodeResponseJson()['data']['calendar'];
        $interviewInfoId = $calendar[count($calendar) - 1]['id'];
        $this->assertDatabaseHas('interview_infos', [
            'id' => $interviewInfoId,
            InterviewInfo::INTERVIEW_NUMBER => 2
        ]);
    }

    public function testSetupCalendarEntryGroupSuccess()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->count(3)->create([
                Interview::INTERVIEW_CODE => strtotime(now()),
                Interview::TYPE => INTERVIEW_TYPE_GROUP,
            ])->pluck('id');
            $data = $this->fakeData();
            $data['interview_type'] = INTERVIEW_TYPE_GROUP;
            $resp = $this->put('api/interview/setup-calendar/' . $interview[0], $data);

            $this->assertTrue($resp->decodeResponseJson()['code'] == Response::HTTP_OK);
            $interviewInfo = InterviewInfo::query()
                ->whereIn(InterviewInfo::INTERVIEW_ID, $interview)
                ->where(InterviewInfo::STATUS, INTERVIEW_INFO_STATUS_TEMPORARY)
                ->where(InterviewInfo::INTERVIEW_TYPE, $data['interview_type'])
                ->count();
            $this->assertEquals(3, $interviewInfo);

            $interviewInfo = InterviewInfo::where(InterviewInfo::INTERVIEW_ID, $interview[1])
                ->where(InterviewInfo::STATUS, INTERVIEW_INFO_STATUS_TEMPORARY)
                ->first();
            $calendar_interview = json_decode($interviewInfo->calendar_interview, true);
            $this->assertEquals($calendar_interview, $data['times']);
            $interview = Interview::find($interview[0]);
            $this->assertNotification($interview, InterviewAdjustmentAdjustingNotification::class, NOTIFY_FOR_HR);
        }
    }

    public function testSetupCalendarOfferSuccess()
    {
        $this->setupCalendarPrivate(true);
    }

    private function checkValidation($types, $api)
    {
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview_info = InterviewInfo::factory()->create([InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE]);
            $interviewId = $interview_info->interview_id;
            $noteEmpty = $this->put('api/interview/' . $api . $interviewId, []);
            $this->assertEquals($noteEmpty->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);

            $idFail = $this->put('api/interview/' . $api . '999', [
                'note' => $this->faker->text()
            ]);
            $this->assertEquals($idFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($idFail->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));

            $status = rand(INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE);
            Interview::query()->where('id', $interview_info->interview_id)->update([Interview::STATUS_SELECTION => $status]);

            $statusFail = $this->put('api/interview/' . $api . $interviewId, [
                'note' => $this->faker->text()
            ]);
            $this->assertEquals($statusFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($statusFail->decodeResponseJson()['message'], trans('api.interview.hr.decline'));
        }
    }

    private function checkWithPrivateInterview($types, $api, $statusSelection, $status, $model, $typeNotify)
    {
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview_info = InterviewInfo::factory()->create([InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE]);
            $interviewId = $interview_info->interview_id;
            $this->put('api/interview/' . $api . $interviewId, [
                'note' => $this->faker->text()
            ])->assertOk();

            $this->assertDatabaseHas('interviews', [
                'id' => $interviewId,
                Interview::STATUS_SELECTION => $statusSelection
            ]);

            $interview_info = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE,
                InterviewInfo::STATUS => 2
            ]);

            $this->put('api/interview/' . $api . $interview_info->interview_id, [
                'note' => $this->faker->text()
            ])->assertOk();
            $this->assertDatabaseHas('interview_infos', [
                'id' => $interview_info->id,
                InterviewInfo::STATUS => $status
            ]);
            $this->assertNotification($interview_info->interview, $model, $typeNotify);
        }
    }

    private function fakeInterviewGroup()
    {
        $this->loginWith(\COMPANY);
        $data = [
            Interview::INTERVIEW_CODE => strtotime(now()),
            Interview::TYPE => INTERVIEW_TYPE_GROUP,
        ];
        $interviewId = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($i == 1) {
                $data[Interview::HR_ID] = HR::factory()->create([
                    HR::USER_ID => User::query()->where(User::TYPE, \HR)->first()->id
                ])->id;
            } else {
                $data[Interview::HR_ID] = HR::factory()->create()->id;
            }
            $interviewId[] = Interview::factory()->create($data)->id;
        }
        $data = $this->fakeData();
        $data['interview_type'] = INTERVIEW_TYPE_GROUP;
        $this->put('api/interview/setup-calendar/' . $interviewId[0], $data);

        return $interviewId;
    }

    private function checkWithGroupInterview($types, $api, $statusSelection, $model, $typeNotify)
    {
        $interviewId = $this->fakeInterviewGroup();

        foreach ($types as $type) {
            $this->loginWith($type);
            $this->put('api/interview/' . $api . $interviewId[0], [
                'note' => $this->faker->text()
            ])->assertOk();

            $this->assertDatabaseHas('interviews', [
                'id' => $interviewId[0],
                Interview::STATUS_SELECTION => $statusSelection
            ]);

            $interviewNotDecline = Interview::query()
                ->where('id', '!=', $interviewId[0])
                ->where(Interview::STATUS_SELECTION, '!=', $statusSelection)
                ->count();
            $this->assertTrue($interviewNotDecline == 3);
            $interview = Interview::find($interviewId[0]);
            $this->assertNotification($interview, $model, $typeNotify);
        }
    }

    public function testHrDeclineCalendarWithValidation()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $this->checkValidation($types, 'confirm-interview-hr-decline/');
    }

    public function testHrDeclineCalendarWithPrivate()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $this->checkWithPrivateInterview($types, 'confirm-interview-hr-decline/', INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_INFO_STATUS_HR_DECLINE, InterviewDeclineNotification::class, NOTIFY_FOR_COMPANY);
    }

    public function testHrDeclineCalendarWithGroup()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $this->checkWithGroupInterview($types, 'confirm-interview-hr-decline/', INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, InterviewDeclineNotification::class, NOTIFY_FOR_COMPANY);
    }

    public function testInterviewCancelWithValidation()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $this->checkValidation($types, 'confirm-interview-company-cancel/');
    }

    public function testInterviewCancelWithPrivate()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $this->checkWithPrivateInterview($types, 'confirm-interview-company-cancel/', INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_INFO_STATUS_COMPANY_CANCEL, InterviewCancelNotification::class, NOTIFY_FOR_HR);
    }

    public function testInterviewCancelWithGroup()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        $this->checkWithGroupInterview($types, 'confirm-interview-company-cancel/', INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, InterviewCancelNotification::class, NOTIFY_FOR_HR);
    }

    public function testHrConfirmCalendarWithValidate()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING]);
            $calenderNotExist = $this->put('api/interview/confirm-calendar/' . $interview->id, ['time' => $this->faker->randomElements(CALENDAR_TIMELINE)[0]]);
            $this->assertEquals($calenderNotExist->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($calenderNotExist->decodeResponseJson()['message'], trans('api.interview.confrim.calendar.check'));

            $interview_info = InterviewInfo::factory()->create([InterviewInfo::INTERVIEW_ID => $interview->id]);
            $timeFail = $this->put('api/interview/confirm-calendar/' . $interview->id, ['time' => 9999]);
            $this->assertEquals($timeFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);

            $time = $this->faker->randomElements(CALENDAR_TIMELINE)[0];
            $idFail = $this->put('api/interview/confirm-calendar/999', ['time' => $time]);
            $this->assertEquals($idFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);

            $interview->status_interview_adjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT;
            $interview->save();
            $statusAdjustment = $this->put('api/interview/confirm-calendar/' . $interview->id, ['time' => $time]);
            $this->assertEquals($statusAdjustment->decodeResponseJson()['message'], trans('api.interview.review.check.status'));

            $interview->status_interview_adjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING;
            $interview->status_selection = $this->faker->randomElements([INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])[0];
            $interview->save();
            $statusSelection = $this->put('api/interview/confirm-calendar/' . $interview->id, ['time' => $time]);
            $this->assertEquals($statusSelection->decodeResponseJson()['message'], trans('api.interview.hr.decline'));

            $interview->status_selection = INTERVIEW_STATUS_SELECTION_DOC_PASS;
            $interview->save();
            $interview_info->status = rand(2, 10);
            $interview_info->save();
            $statusFail = $this->put('api/interview/confirm-calendar/' . $interview->id, ['time' => $time]);
            $this->assertEquals($statusFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($statusFail->decodeResponseJson()['message'], trans('api.interview.confrim.calendar.check'));
        }
    }

    private function confirmCalendarPrivate($types, $time, $data, $interviewInfoStatus, $statusAdjustment)
    {
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTING]);
            $interviewId = $interview->id;
            $interview_info_fail = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_PASS
            ]);
            $interview_info = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::INTERVIEW_TYPE => INTERVIEW_TYPE_PRIVATE,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_TEMPORARY
            ]);
            $this->put('api/interview/confirm-calendar/' . $interviewId, ['time' => $time])->assertOk();
            $this->assertDatabaseHas('interview_infos', [
                'id' => $interview_info->id,
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::STATUS => $interviewInfoStatus
            ]);
            $this->assertDatabaseHas('interview_infos', [
                'id' => $interview_info_fail->id,
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_PASS
            ]);
            $this->assertDatabaseHas('interviews', [
                'id' => $interviewId,
                Interview::INTERVIEW_ADJUSTMENT => $statusAdjustment,
            ]);
            $interviewInfo = InterviewInfo::find($interview_info->id);
            $calendar_interview = json_decode($interviewInfo->calendar_interview, true);
            if ($interviewInfoStatus == INTERVIEW_INFO_STATUS_HR_CONFIRM) {
                $this->assertEquals($calendar_interview[0], $data['times'][$time - 1]);
                $this->assertNotification($interview, InterviewURLSettingCompanyNotification::class, NOTIFY_FOR_COMPANY);
                $this->assertNotification($interview, InterviewURLSettingManagerNotification::class, NOTIFY_FOR_MANAGER);
            } else {
                $this->assertEquals($calendar_interview, $data['times']);
                $this->assertNotification($interview, InterviewHrRejectTimeNotification::class, NOTIFY_FOR_COMPANY);
            }
        }
    }

    private function confirmCalendarGroup($types, $time, $data, $interviewInfoStatus, $statusAdjustment)
    {
        $interviewId = $this->fakeInterviewGroup();
        foreach ($types as $type) {
            $this->loginWith($type);
            $this->put('api/interview/confirm-calendar/' . $interviewId[0], ['time' => $time]);
            $interviewInfo = InterviewInfo::query()->whereIn(InterviewInfo::INTERVIEW_ID, $interviewId)
                ->where(InterviewInfo::STATUS, $interviewInfoStatus)
                ->get();
            $this->assertEquals(count($interviewInfo), count($interviewId));
            foreach ($interviewInfo as $info) {
                $calendar = json_decode($info->calendar_interview, true);
                $interview = Interview::find($interviewId[0]);
                if ($interviewInfoStatus == INTERVIEW_INFO_STATUS_HR_CONFIRM) {
                    $this->assertEquals($calendar[0], $data['times'][$time - 1]);
                    $this->assertNotification($interview, InterviewURLSettingCompanyNotification::class, NOTIFY_FOR_COMPANY);
                    $this->assertNotification($interview, InterviewURLSettingManagerNotification::class, NOTIFY_FOR_MANAGER);
                } else {
                    $this->assertEquals($calendar, $data['times']);
                    $this->assertNotification($interview, InterviewHrRejectTimeNotification::class, NOTIFY_FOR_COMPANY);
                }
            }

            $interviewCount = Interview::query()
                ->where(Interview::INTERVIEW_ADJUSTMENT, $statusAdjustment)
                ->count();
            $this->assertEquals($interviewCount, count($interviewId));
        }
    }

    public function testHrConfirmCalendarSuccess()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $time = rand(1, 5);
        $data = $this->fakeData();

        $this->confirmCalendarPrivate($types, $time, $data, INTERVIEW_INFO_STATUS_HR_CONFIRM, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING);
        $this->artisan('migrate:fresh --seed');
        $this->confirmCalendarGroup($types, $time, $data, INTERVIEW_INFO_STATUS_HR_CONFIRM, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING);
    }

    public function testHrConfirmCalendarRejectSuccess()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, \HR];
        $time = CALENDAR_TIMELINE_OTHER;
        $data = $this->fakeData();

        $this->confirmCalendarPrivate($types, $time, $data, INTERVIEW_INFO_STATUS_HR_REJECT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT);
        $this->artisan('migrate:fresh --seed');
        $this->confirmCalendarGroup($types, $time, $data, INTERVIEW_INFO_STATUS_HR_REJECT, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT);
    }

    public function testSetupZoomWithValidation()
    {
        $types = [SUPER_ADMIN, HR_MANAGER];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING]);
            $data = [
                "url_zoom" => $this->faker->text(40),
                "id_zoom" => $this->faker->text(8),
                "password_zoom" => $this->faker->text(8)
            ];

            $calenderNotExist = $this->put('api/interview/setup-zoom/' . $interview->id, $data);
            $this->assertEquals($calenderNotExist->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals($calenderNotExist->decodeResponseJson()['message'], trans('api.interview.confrim.calendar.check'));

            $interview_info = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::INTERVIEW_NUMBER => INTERVIEW_INFO_NUMBER_FIRST,
                InterviewInfo::CALENDAR_INTERVIEW => json_encode([$this->fakeData()['times'][0]])
            ]);
            $dataEmpty = $this->put('api/interview/setup-zoom/' . $interview->id, []);
            $this->assertEquals($dataEmpty->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);

            $interview->status_interview_adjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT;
            $interview->save();
            $statusAdjustment = $this->put('api/interview/setup-zoom/' . $interview->id, $data);
            $this->assertEquals($statusAdjustment->decodeResponseJson()['message'], trans('api.interview.confrim.calendar.url.empty'));

            $interview->status_interview_adjustment = INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING;
            $interview->status_selection = $this->faker->randomElements([INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS])[0];
            $interview->save();
            $statusSelection = $this->put('api/interview/setup-zoom/' . $interview->id, $data);
            $this->assertEquals($statusSelection->decodeResponseJson()['message'], trans('api.interview.hr.decline'));

            $infoStatus = INTERVIEW_INFO_STATUS;
            foreach ($infoStatus as $status) {
                if($status == INTERVIEW_INFO_STATUS_HR_CONFIRM) continue;
                $interview->status_selection = INTERVIEW_STATUS_SELECTION_DOC_PASS;
                $interview->save();
                $interview_info->status = $status;
                $interview_info->save();
                $statusFail = $this->put('api/interview/setup-zoom/' . $interview->id, $data);
                $this->assertEquals($statusFail->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
                $this->assertEquals($statusFail->decodeResponseJson()['message'], trans('api.interview.confrim.calendar.check'));
            }
        }
    }

    private function setupZoomPrivate($types, $data, $interviewInfoStatus, $statusAdjustment)
    {
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_URL_SETTING]);
            $interviewId = $interview->id;
            $interview_info = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_HR_CONFIRM
            ]);
            $this->put('api/interview/setup-zoom/' . $interviewId, $data)->assertOk();
            $this->assertDatabaseHas('interview_infos', [
                'id' => $interview_info->id,
                InterviewInfo::INTERVIEW_ID => $interviewId,
                InterviewInfo::STATUS => $interviewInfoStatus,
                InterviewInfo::URL_ZOOM => $data['url_zoom'],
                InterviewInfo::ID_ZOOM => $data['id_zoom'],
                InterviewInfo::PASSWORD_ZOOM => $data['password_zoom']
            ]);
            $this->assertDatabaseHas('interviews', [
                'id' => $interviewId,
                Interview::INTERVIEW_ADJUSTMENT => $statusAdjustment,
            ]);
            $this->assertNotification($interview, InterviewAdjustmentAdjustedNotification::class , NOTIFY_FOR_HR);
            $this->assertNotification($interview, InterviewAdjustmentAdjustedNotification::class , NOTIFY_FOR_COMPANY);
        }
    }

    private function setupZoomGroup($types, $data, $interviewInfoStatus, $statusAdjustment)
    {
        $interviewId = $this->fakeInterviewGroup();
        $this->loginWith(\HR);
        $time = rand(1,5);
        $this->put('api/interview/confirm-calendar/' . $interviewId[0], ['time' => $time]);
        foreach ($types as $type) {
            $this->loginWith($type);
            $this->put('api/interview/setup-zoom/' . $interviewId[0], $data)->assertOk();
            $interviewInfo = InterviewInfo::query()->whereIn(InterviewInfo::INTERVIEW_ID, $interviewId)
                ->where(InterviewInfo::STATUS, $interviewInfoStatus)->get();
            $this->assertEquals(count($interviewInfo), count($interviewId));
            $interviewCount = Interview::query()->where(Interview::INTERVIEW_ADJUSTMENT, $statusAdjustment)->count();
            $this->assertEquals($interviewCount, count($interviewId));
            $interview = Interview::find($interviewId[0]);
            $this->assertNotification($interview, InterviewAdjustmentAdjustedNotification::class , NOTIFY_FOR_HR);
            $this->assertNotification($interview, InterviewAdjustmentAdjustedNotification::class , NOTIFY_FOR_COMPANY);
        }
    }

    public function testSetupZoomSuccess()
    {
        $types = [SUPER_ADMIN, HR_MANAGER];
        $data = [
                "url_zoom" => $this->faker->text(40),
                "id_zoom" => $this->faker->text(8),
                "password_zoom" => $this->faker->text(8)
            ];
        $this->setupZoomPrivate($types, $data, INTERVIEW_INFO_STATUS_HR_SET_URL, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED);
        $this->artisan('migrate:fresh --seed');
        $this->setupZoomGroup($types, $data, INTERVIEW_INFO_STATUS_HR_SET_URL, INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED);
    }

    public function testInterviewReviewWithValidate()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED]);
            $interviewInfo = InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_HR_SET_URL
            ]);
            $data = [
                "status" => $this->faker->randomElements(INTERVIEW_STATUS_REVIEW)[0],
                "date_offer" => Carbon::now()->addWeek()->format('Y-m-d'),
                "action" => $this->faker->randomElements(OPTION_PASS_OPERATION)[0]
            ];
            $dataFail = [
                "status" => '999',
                "date_offer" => Carbon::now()->subMonth()->format("Y-m-d"),
                "action" => '999'
            ];
            foreach ($dataFail as $key => $value) {
                $dataTest = $data;
                $dataTest[$key] = $value;
                $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, $dataTest);
                $this->assertEquals($resp->decodeResponseJson()['code'],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_OFFICIAL_OFFER,
                "date_offer" => null,
                "action" => $this->faker->randomElements(OPTION_PASS_OPERATION)[0]
            ]);
            $this->assertEquals($resp->decodeResponseJson()['code'],CODE_NO_ACCESS);
            $this->assertEquals($resp->decodeResponseJson()['message'],trans('api.interview.review.check.status.offer'));

            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_PASS,
                "action" => null
            ]);
            $this->assertEquals($resp->decodeResponseJson()['code'],CODE_NO_ACCESS);
            $this->assertEquals($resp->decodeResponseJson()['message'],trans('api.interview.review.check.pass.action'));

            $interviewInfo->number = 3;
            $interviewInfo->save();
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_PASS,
                "action" => OPTION_PASS_OPERATION_SECOND
            ]);
            $this->assertEquals($resp->decodeResponseJson()['code'],CODE_NO_ACCESS);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('api.interview.review.check.pass.round'));

            $interviewInfo->number = 5;
            $interviewInfo->save();
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_PASS,
                "action" => 5
            ]);
            $this->assertEquals($resp->decodeResponseJson()['code'],CODE_NO_ACCESS);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('api.interview.review.check.pass.round.end'));
        }
    }

    private function checkWithStatus($msg, $statusSelection = null, $statusAdjustment = null, $statusInfo = null)
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = Interview::factory()->create([
                Interview::STATUS_SELECTION => $statusSelection ?? INTERVIEW_STATUS_SELECTION_DOC_PASS,
                Interview::INTERVIEW_ADJUSTMENT => $statusAdjustment ?? INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED
            ]);
            InterviewInfo::factory()->create([
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => $statusInfo ?? INTERVIEW_INFO_STATUS_HR_SET_URL
            ]);
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => $this->faker->randomElements(INTERVIEW_STATUS_REVIEW)[0],
                "date_offer" => Carbon::now()->addWeek()->format('Y-m-d'),
                "action" => $this->faker->randomElements(OPTION_PASS_OPERATION)[0]
            ]);

            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_NO_ACCESS);
            $this->assertTrue($resp->decodeResponseJson()['message'] == $msg);
        }
    }

    public function testInterviewReviewWithStatusInterviewFail()
    {
        $statusSelection = [INTERVIEW_STATUS_SELECTION_INTERVIEW_CANCEL, INTERVIEW_STATUS_SELECTION_INTERVIEW_DECLINE, INTERVIEW_STATUS_SELECTION_FIFTH_PASS];
        $this->checkWithStatus(trans('api.interview.hr.decline'), $this->faker->randomElements($statusSelection)[0]);

        $statusAdjustmentList = array_keys(STATUS_ADJUSTINGS);
        foreach ($statusAdjustmentList as $statusAdjustment) {
            if($statusAdjustment == INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED) continue;
            $this->checkWithStatus(trans('api.interview.review.check.status'), null, $statusAdjustment);
        }

        $this->checkWithStatus(trans('api.interview.review.check.status'), null, null, INTERVIEW_INFO_STATUS_COMPANY_PASS);
    }

    private function fakeDataForReview()
    {
        $interview = Interview::factory()->create([
            Interview::STATUS_SELECTION => INTERVIEW_STATUS_SELECTION_DOC_PASS,
            Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_ADJUSTED
        ]);
        InterviewInfo::factory()->create([
            InterviewInfo::INTERVIEW_ID => $interview->id,
            InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_HR_SET_URL
        ]);
        return $interview;
    }

    public function testInterviewReviewConfirmReviewPass()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = $this->fakeDataForReview();
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_PASS,
                "action" => 2
            ]);

            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_SUCCESS);
            $this->assertDatabaseHas('interview_infos', [
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_PASS
            ]);

            $this->assertDatabaseHas('interviews', [
                'id' => $interview->id,
                Interview::TYPE => INTERVIEW_TYPE_PRIVATE,
                Interview::STATUS_SELECTION => INTERVIEW_STATUS_SELECTION_FIRST_PASS,
                Interview::INTERVIEW_ADJUSTMENT => INTERVIEW_STATUS_INTERVIEW_ADJUSTMENT_BEFORE_ADJUSTMENT
            ]);
            $this->assertNotification($interview, InterviewPassNotification::class, NOTIFY_FOR_HR);
        }
    }

    public function testInterviewReviewConfirmReviewNoPass()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = $this->fakeDataForReview();
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_NO_PASS,
            ]);

            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_SUCCESS);
            $this->assertDatabaseHas('interview_infos', [
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_NO_PASS
            ]);

            $this->assertDatabaseHas('interviews', [
                'id' => $interview->id,
                Interview::DISPLAY => 'off'
            ]);

            $this->assertDatabaseHas('results', [
                Result::INTERVIEW_ID => $interview->id,
                Result::HR_ID => $interview->hr_id,
                Result::WORK_ID => $interview->work_id,
                Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_NOT_PASS
            ]);

            $this->assertNotification($interview, InterviewNoPassNotification::class, NOTIFY_FOR_HR);
        }
    }

    public function testInterviewReviewConfirmReviewOfficialOffer()
    {
        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $interview = $this->fakeDataForReview();

            $date = Carbon::now()->addMonth()->format('Y-m-d');
            $resp = $this->put('api/interview/confirm-interview-company-review/' . $interview->id, [
                "status" => INTERVIEW_STATUS_REVIEW_OFFICIAL_OFFER,
                "date_offer" => $date
            ]);

            $this->assertTrue($resp->decodeResponseJson()['code'] == CODE_SUCCESS);
            $this->assertDatabaseHas('interview_infos', [
                InterviewInfo::INTERVIEW_ID => $interview->id,
                InterviewInfo::STATUS => INTERVIEW_INFO_STATUS_COMPANY_OFFER
            ]);

            $this->assertDatabaseHas('interviews', [
                'id' => $interview->id,
                Interview::DISPLAY => 'off'
            ]);

            $this->assertDatabaseHas('results', [
                Result::INTERVIEW_ID => $interview->id,
                Result::HR_ID => $interview->hr_id,
                Result::WORK_ID => $interview->work_id,
                Result::STATUS_SELECTION => RESULT_STATUS_SELECTION_OFFER,
                Result::TIME => Carbon::parse($date)->format('Ymd')
            ]);

            $this->assertNotification($interview, InterviewOfferNotification::class, NOTIFY_FOR_HR);
        }
    }
}




