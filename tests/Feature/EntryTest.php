<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HRMainJobCareer;
use App\Models\HrOrganization;
use App\Models\JobInfo;
use App\Models\JobType;
use App\Models\LanguageRequirement;
use App\Models\UploadFile;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;

class EntryTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $work_id;
    protected $types;
    protected $typeAll;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->loginWith(\HR);
        $this->types = [SUPER_ADMIN, HR_MANAGER, HR];
        $this->typeAll = [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER, \COMPANY, \HR];
        $this->fakeData();
    }

    private function fakeData()
    {
        $user_company = User::query()->where(User::TYPE, \COMPANY)->first();
        $company_id = Company::firstOrCreate(
            [Company::USER_ID => $user_company->id],
            Company::factory()->make()->toArray()
        )->id;

        Work::factory()->count(3)->create([
            Work::STATUS => WORK_STATUS_RECRUITING,
            Work::TITLE => $this->faker->title,
            Work::COMPANY_ID => $company_id,
            Work::USER_ID => $user_company->id
        ]);
        HR::factory()->count(3)->create();
        HRMainJobCareer::factory()->count(2)->create([HRMainJobCareer::HRS_ID => HR::query()->inRandomOrder()->pluck('id')->first()]);
    }
    public function dataTest()
    {
        $theJob = Work::query()->inRandomOrder()->first();
        return [
            'items' => [
                [
                    "work_id" => $theJob->id,
                    "hr_id" => HR::first()->id,
                    "remarks" => $this->faker->text(),
                    "motivation_file_id" => UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE])->id,
                    "recommendation_file_id" => "",
                    "other_files" => []
                ],
                [
                    "work_id" => $theJob->id,
                    "hr_id" => HR::query()->inRandomOrder()->pluck('id')->first(),
                    "remarks" => $this->faker->text(),
                    "motivation_file_id" => UploadFile::factory()->create([
                        'file_model' => 'entry',
                        UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE
                    ])->id,
                    "recommendation_file_id" => UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => RECOMMENDATION_FILE])->id,
                    "other_files" => []
                ],
                [
                    "work_id" => $theJob->id,
                    "hr_id" => HR::query()->inRandomOrder()->pluck('id')->first(),
                    "remarks" => $this->faker->text(),
                    "motivation_file_id" => UploadFile::factory()->create([
                        'file_model' => 'entry',
                        UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE
                    ])->id,
                    "recommendation_file_id" => "",
                    "other_files" => []
                ]
            ]
        ];
    }

    private function createNew() {
        $this->loginWith(\HR);
        $dataTest = $this->dataTest();
        $this->post('api/entry', $dataTest);
    }

    private function fieldSort($field, $sortBy)
    {
        if (!Entry::first()) {
            $this->createNew();
        }
        foreach ($this->typeAll as $type) {
            $this->loginWith($type);
            $response = $this->json('get', 'api/entries', [
                'field' => $field,
                'sort_by' => $sortBy
            ])->assertSeeText(CODE_SUCCESS)
                ->assertOk();
            $listResult = $response->decodeResponseJson()['data']['result'];

            if($field == 'updated_at') {
                $field_sort = 'updating_date';
            } else {
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

    private function searchInput($param, $column, $rand)
    {
        if (!Entry::first()) {
            $this->createNew();
        }
        foreach ($this->typeAll as $type) {
            $this->loginWith($type);
            $resp = $this->json('get', 'api/entries', $param)
                ->assertSeeText(CODE_SUCCESS)
                ->assertOk();
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

//    public function testCreateWithItemsNull()
//    {
//        foreach ($this->types as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->post('api/entry', []);
//            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testCreateWithValidation()
//    {
//        foreach ($this->types as $type) {
//            $dataFail = [
//                "work_id" => 999,
//                "hr_id" => HR::factory()->create([HR::CREATED_BY => Auth::id(), HR::STATUS => HRS_STATUS_PRIVATE])->id,
//                "motivation_file_id" => 999,
//                "recommendation_file_id" => UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE])->id,
//            ];
//            $data = [
//                "work_id" => $this->work_id,
//                "hr_id" => HR::factory()->create([HR::CREATED_BY => Auth::id()])->id,
//                "remarks" => $this->faker->text(),
//                "motivation_file_id" => UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE])->id,
//                "recommendation_file_id" => UploadFile::factory()->create([UploadFile::FILE_ITEM_TYPE => RECOMMENDATION_FILE])->id,
//                "other_files" => []
//            ];
//
//            foreach ($dataFail as $key => $fail) {
//                $data[$key] = $dataFail[$key];
//                $this->loginWith($type);
//                $response = $this->user_login->post('api/entry', ['items' => $data]);
//                $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
//            }
//        }
//    }
//
//    public function testCreateSuccess()
//    {
//            $hrUser = User::where('type', HR)->first();
//            $dataTest = $this->dataTest();
//            $response = $this->actingAs($hrUser)->post('api/entry', $dataTest);
//
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//
//            $datas = $response->getData()->data;
//            foreach ($datas as $key => $data) {
//                $this->assertDatabaseHas('entries', [
//                    'id' => $data->id,
//                    Entry::ENTRY_CODE => $data->code,
//                    Entry::HR_ID => $data->hr_id,
//                ]);
//
//                $this->assertDatabaseHas('files', [
//                    'id' => $dataTest['items'][$key]['motivation_file_id'],
//                    UploadFile::FILE_ITEM_ID => $data->id,
//                    UploadFile::FILE_ITEM_TYPE => MOTIVATION_FILE,
//                ]);
//            }
//    }
//
//    public function testListSuccess()
//    {
//        if (!Entry::first()) {
//            $this->createNew();
//        }
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $resp = $this->json('get', 'api/entries')
//                ->assertSeeText(CODE_SUCCESS)
//                ->assertOk();
//            $this->assertEquals($resp->decodeResponseJson()['code'], Response::HTTP_OK);
//        }
//    }
//
//    public function testIndexSuccess()
//    {
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->get('api/entries');
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//        }
//    }
//
//    public function testShowPaginateSuccess()
//    {
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->get('api/entries');
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
//        }
//    }
//
//    public function testNextPageSuccess()
//    {
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->get('api/entries?page=2&per_page=1');
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
//        }
//    }
//
//    public function testPaginateFirstPage()
//    {
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->get('api/entries?page=1&per_page=1');
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
//        }
//    }

    public function testDeleteSuccess()
    {
        $types = [SUPER_ADMIN, HR_MANAGER, COMPANY_MANAGER];
        foreach ($types as $type) {
            $theJob = Work::query()->inRandomOrder()->first();
            $theHr = HR::query()->inRandomOrder()->first();
            $status = [ENTRY_STATUS_REJECT, ENTRY_STATUS_DECLINE, ENTRY_STATUS_CONFIRM];

            Entry::factory()->count(3)->create([
                Entry::WORK_ID => $theJob->id,
                Entry::HR_ID => $theHr->id,
                Entry::STATUS => $this->faker->randomElement($status)
            ]);

            $this->loginWith($type);
            $entry_id = Entry::query()->where(Entry::STATUS, '!=', ENTRY_STATUS_REQUESTING)
                ->inRandomOrder()->pluck('id')->first();
            $response = $this->json('post', 'api/entry/hide', ['ids' => [$entry_id]]);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertDatabaseHas('entries', [
                'id' => $entry_id,
                Entry::DISPLAY => 'off'
            ]);
        }
    }

    public function testDeleteWithHr()
    {
        $this->loginWith(\HR);
        $theJob = Work::query()->inRandomOrder()->first();
        $status = [ENTRY_STATUS_REJECT, ENTRY_STATUS_DECLINE, ENTRY_STATUS_CONFIRM];
        $theHr = HR::query()->where(HR::HR_ORGANIZATION_ID, Auth::user()->hrOrganization->id)->first();

        $entry_status_fail = Entry::factory()->create([
            Entry::WORK_ID => $theJob->id,
            Entry::HR_ID => $theHr->id,
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry_status_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $entry_fail = Entry::factory()->create([
            Entry::WORK_ID => $theJob->id,
            Entry::HR_ID => HR::factory()->create([HR::HR_ORGANIZATION_ID => 10])->id,
            Entry::STATUS => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $entry = Entry::factory()->create([
            Entry::WORK_ID => $theJob->id,
            Entry::HR_ID => $theHr->id,
            Entry::STATUS => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry]]);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertDatabaseHas('entries', [
            'id' => $entry,
            Entry::DISPLAY => 'off'
        ]);
    }

    public function testDeleteWithCompany()
    {
        $this->loginWith(\COMPANY);
        $theJob = Work::query()->where(Work::COMPANY_ID, $this->company->id)->first();
        $status = [ENTRY_STATUS_REJECT, ENTRY_STATUS_DECLINE, ENTRY_STATUS_CONFIRM];
        $theHr = HR::query()->inRandomOrder()->first();

        $entry_status_fail = Entry::factory()->create([
            Entry::WORK_ID => $theJob->id,
            Entry::HR_ID => $theHr->id,
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry_status_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $entry_fail = Entry::factory()->create([
            Entry::WORK_ID => Work::factory()->create([Work::COMPANY_ID => 10])->id,
            Entry::HR_ID => $theHr->id,
            Entry::STATUS => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry_fail]]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        $response->assertSeeText(trans('api.entry.delete.request'));

        $entry = Entry::factory()->create([
            Entry::WORK_ID => $theJob->id,
            Entry::HR_ID => $theHr->id,
            Entry::STATUS => $this->faker->randomElement($status)
        ])->id;
        $response = $this->post('api/entry/hide', ['ids' => [$entry]]);
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $this->assertDatabaseHas('entries', [
            'id' => $entry,
            Entry::DISPLAY => 'off'
        ]);
    }

    public function testDeleteFail()
    {
        if (!Entry::first()) {
            $this->createNew();
        }
        $hrUser = User::where(User::TYPE, HR)->first();
        $resp = $this->actingAs($hrUser)->json('post', 'api/entry/hide', [
            'ids' => [9999]
        ])->assertDontSeeText(CODE_SUCCESS);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $resp->decodeResponseJson()['code']);
    }

//    public function testListSortByIdAscSuccess()
//    {
//        $this->fieldSort('id', 'asc');
//    }
//
//    public function testListSortByIdDescSuccess()
//    {
//        $this->fieldSort('id', 'desc');
//    }
//
//    public function testListSortByEntryCodeAscSuccess()
//    {
//        $this->fieldSort('entry_code', 'asc');
//    }
//
//    public function testListSortByEntryCodeDescSuccess()
//    {
//        $this->fieldSort('entry_code', 'desc');
//    }
//
//    public function testListSortByRequestDateAscSuccess()
//    {
//        $this->fieldSort('request_date', 'asc');
//    }
//
//    public function testListSortByRequestDateDescSuccess()
//    {
//        $this->fieldSort('request_date', 'desc');
//    }
//
//    public function testListSortByFullNameAscSuccess()
//    {
//        $this->fieldSort('full_name', 'asc');
//    }
//
//    public function testListSortByFullNameDescSuccess()
//    {
//        $this->fieldSort('full_name', 'desc');
//    }
//
//    public function testListSortByWorkTitleAscSuccess()
//    {
//        $this->fieldSort('work_title', 'asc');
//    }
//
//    public function testListSortByWorkTitleDescSuccess()
//    {
//        $this->fieldSort('work_title', 'desc');
//    }
//
//    public function testListSortByUpdatedAtAscSuccess()
//    {
//        $this->fieldSort('updated_at', 'asc');
//    }
//
//    public function testListSortByUpdatedAtDescSuccess()
//    {
//        $this->fieldSort('updated_at', 'desc');
//    }
//
//    public function testSearchGenderSuccess()
//    {
//        $param = [
//            'gender' => [rand(HRS_GENDER_FEMALE, HRS_GENDER_MALE)]
//        ];
//        $this->searchInput($param, HR::GENDER, $param['gender']);
//    }
//
//    public function testSearchAgeSuccess()
//    {
//        $param = [
//            'age_from' => rand(18, 20),
//            'age_to' => rand(30, 40)
//        ];
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $resp = $this->json('get', 'api/entries', $param)
//                ->assertSeeText(CODE_SUCCESS)
//                ->assertOk();
//            $result = $resp->decodeResponseJson()['data']['result'];
//
//            $hr_id = [];
//            foreach ($result as $value) {
//                $hr_id[] = data_get($value, 'hr_id');
//            }
//            $count = HR::query()
//                ->where(HR::DATE_OF_BIRTH, '<=', $param['age_from'])
//                ->where(HR::DATE_OF_BIRTH, '>=', $param['age_to'])
//                ->whereIn('id', $hr_id)->count();
//            $this->assertCount($count, $result);
//        }
//    }
//
//    public function testSearchEducationDateSuccess()
//    {
//        $param = [
//            'edu_date_from' => $this->faker->dateTimeBetween('-10 years', '-5 years')->format('Y-m'),
//            'edu_date_to' => $this->faker->dateTimeBetween('-3 years')->format('Y-m'),
//        ];
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $resp = $this->json('get', 'api/entries', $param)
//                ->assertSeeText(CODE_SUCCESS)
//                ->assertOk();
//            $result = $resp->decodeResponseJson()['data']['result'];
//
//            $hr_id = [];
//            foreach ($result as $value) {
//                $hr_id[] = data_get($value, 'hr_id');
//            }
//            $dateFrom = Carbon::parse($param['edu_date_from'])->toDateString();
//            $dateTo = Carbon::parse($param['edu_date_from'])->toDateString();
//            $count = HR::query()
//                ->where(HR::FINAL_EDUCATION_DATE, '>=', $dateFrom)
//                ->where(HR::FINAL_EDUCATION_DATE, '<=', $dateTo)
//                ->whereIn('id', $hr_id)->count();
//            $this->assertCount($count, $result);
//        }
//    }
//
//    public function testSearchWorkFormSuccess()
//    {
//        $this->searchInput(['work_forms' => HRS_WORK_FORM], HR::WORK_FORM, HRS_WORK_FORM);
//    }
//
//    public function testSearchJapanLevelsSuccess()
//    {
//        $param = [
//            'japan_levels' => LanguageRequirement::query()->pluck('id'),
//        ];
//        $this->searchInput($param, HR::JAPANESE_LEVEL, $param['japan_levels']);
//    }
//
//    public function testSearchMainJobsSuccess()
//    {
//        $mainJob = HRMainJobCareer::query()->pluck('id');
//        foreach ($this->typeAll as $type) {
//            $this->loginWith($type);
//            $rand = $this->faker->randomElements($mainJob, 2);
//            $response = $this->user_login->get('api/offer?entries%5B%5D='.$rand[0].'&main_job_ids%5B%5D='.$rand[1]);
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $result = $response->decodeResponseJson()['data']['result'];
//            $hr_id = [];
//            foreach ($result as $value) {
//                $hr_id[] = data_get($value, 'hr_id');
//            }
//            $count = HR::query()->whereIn('id', $hr_id)->whereHas('mainJobs',function ($q) use ($rand){
//                $q->whereIn('id', $rand);
//            })->count();
//            $this->assertCount($count, $result);
//        }
//    }
//
//    public function testSearchEducationDegreeSuccess()
//    {
//        $rand = $this->faker->randomElement(HR_EDUCATION_DEGREES_TYPE);
//        foreach ($this->types as $type) {
//            $this->loginWith($type);
//            $response = $this->user_login->get('api/entries?edu_degree%5B%5D='.$rand);
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $result = $response->decodeResponseJson()['data']['result'];
//            $hr_id = [];
//            foreach ($result as $value) {
//                $hr_id[] = $value['hr_id'];
//            }
//            if($rand == HR_EDUCATION_DEGREES_TYPE['university_or_more']) {
//                $count = HR::query()
//                    ->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 0, 3, true)))
//                    ->whereIn('id', $hr_id)->count();
//            } else {
//                $count = HR::query()
//                    ->whereIn(HR::FINAL_EDUCATION_DEGREE, array_keys(array_slice(HR_EDUCATION_DEGREES, 3, 3, true)))
//                    ->whereIn('id', $hr_id)->count();
//            }
//            $this->assertCount($count, $result);
//        }
//    }
//
//    public function testSearchMiddleClassificationSuccess()
//    {
//        $major = JobType::query()->where(JobType::TYPE, JOB_TYPE_COL_2)->inRandomOrder()->pluck('id')->first();
//        $param = ['middle_class' => JobInfo::query()->where(JobInfo::JOB_TYPE_ID, $major)->pluck('id')];
//        $this->searchInput($param, HR::MIDDLE_CLASSIFICATION_ID, $param['middle_class']);
//    }
//
//    public function testSearchByText()
//    {
//        $types = [SUPER_ADMIN, COMPANY_MANAGER, \COMPANY];
//        $name = HR::query()->inRandomOrder()->pluck(HR::FULL_NAME_JA)->first();
//        foreach ($types as $type) {
//            $this->loginWith($type);
//            $name = $name . $this->faker->text(5);
//            $response = $this->user_login->get('api/entries?search='.$name);
//            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
//            $result = $response->decodeResponseJson()['data']['result'];
//            $hr_id = [];
//            foreach ($result as $value) {
//                $hr_id[] = $value['hr_id'];
//            }
//            $count = HR::query()
//                ->where(HR::FULL_NAME_JA, $name)
//                ->orWhere(HR::FULL_NAME, $name)
//                ->whereIn('id', $hr_id)->count();
//            $this->assertCount($count, $result);
//        }
//    }

//    public function testDetailSuccess()
//    {
//        if (!Entry::first()) {
//            $this->createNew();
//        }
//        $hrUser = User::where(User::TYPE, HR)->first();
//        $resp = $this->actingAs($hrUser)->json('get', 'api/entry/1')
//            ->assertSeeText(CODE_SUCCESS)
//            ->assertOk()
//        ;
//        $data = $resp->getData()->data;
//        $this->assertTrue(!empty($data));
//    }
//
//    public function testCompanyUpdateStatusRejectSuccess()
//    {
//        if (!Entry::first()) {
//            $this->createNew();
//        }
//        $hrUser = User::where(User::TYPE, COMPANY)->first();
//        $theEntry = Entry::where('status', ENTRY_STATUS_REQUESTING)->first();
//        $resp = $this->actingAs($hrUser)->json('post', 'api/entry/' . $theEntry->id . '/update-status', [
//            'status' => 2,
//            'why_reject' => 1,
//            'other_note' => '',
//        ])
//            ->assertSeeText(CODE_SUCCESS)
//            ->assertOk()
//        ;
//        $data = $resp->getData()->data;
//        $this->assertTrue(!empty($data));
//    }
//
//    public function testHrUpdateStatusRejectSuccess()
//    {
//        if (!Entry::first()) {
//            $this->createNew();
//        }
//        $hrUser = User::where(User::TYPE, HR)->first();
//        $theEntry = Entry::where('status', ENTRY_STATUS_REQUESTING)->first();
//        $resp = $this->actingAs($hrUser)->json('post', 'api/entry/1/update-status', [
//            'status' => 3,
//            'why_reject' => 1,
//            'other_note' => '',
//        ])
//            ->assertSeeText(CODE_SUCCESS)
//            ->assertOk()
//        ;
//        $data = $resp->getData()->data;
//        $this->assertTrue(!empty($data));
//    }

//    public function testUpdateStatusConfirmSuccess()
//    {
//        if (!Entry::first()) {
//            $this->createNew();
//        }
//        $hrUser = User::where(User::TYPE, COMPANY)->first();
//        $theEntry = Entry::where('status', ENTRY_STATUS_REQUESTING)->first();
//        $resp = $this->actingAs($hrUser)->json('post', 'api/interview', [
//            'entry_id' => $theEntry->id,
//        ])
//            ->assertSeeText(CODE_SUCCESS)
//            ->assertOk()
//        ;
//        $data = $resp->getData()->data;
//        $this->assertTrue(!empty($data));
//    }

}
