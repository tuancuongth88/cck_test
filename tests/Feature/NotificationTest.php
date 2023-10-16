<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\HrOrganization;
use App\Models\Interview;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\UploadFile;
use App\Models\User;
use App\Notifications\DistributionNotification;
use App\Notifications\RemindAccountNotification;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $types;
    protected $dataTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        Company::factory()->create([
            Company::USER_ID => User::where(User::TYPE, \COMPANY)->first()->id
        ]);
        $hrOrg = HrOrganization::factory()->create([
            HrOrganization::USER_ID => User::where(User::TYPE, HR)->first()->id
        ]);
        HR::factory()->create([HR::HR_ORGANIZATION_ID => $hrOrg->id]);

        $this->dataTest = [
            'title' => $this->faker->text(50),
            'text' => $this->faker->text(100),
            'image_id' => UploadFile::factory()->create()->id
        ];
        $this->types = [SUPER_ADMIN, HR_MANAGER, COMPANY_MANAGER, \COMPANY, \HR];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testDistributionCreateWithItemsEmpty()
    {
        $this->loginWith(SUPER_ADMIN);
        foreach ($this->dataTest as $key => $value) {
            $data = $this->dataTest;
            $data[$key] = '';
            $resp = $this->post('/api/notification', $data);
            if ($key == 'image_id')
                $this->assertEquals($resp->decodeResponseJson()['code'], CODE_SUCCESS);
            else {
                $this->assertEquals($resp->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
                $this->assertEquals($resp->decodeResponseJson()['message'], trans('api.noti.' . $key . '.required'));
            }
        }
    }

    public function testDistributionCreateWithValidate()
    {
        $this->loginWith(SUPER_ADMIN);
        $dataFail = [
            'title' => Str::random(60),
            'text' => Str::random(1010),
            'image_id' => 999
        ];
        foreach ($dataFail as $key => $value) {
            $data = $this->dataTest;
            $data[$key] = $value;
            $resp = $this->post('/api/notification', $data);

            $this->assertEquals($resp->decodeResponseJson()['code'], Response::HTTP_UNPROCESSABLE_ENTITY);
            if($key != 'image_id')
                $this->assertEquals($resp->decodeResponseJson()['message'], trans('api.noti.'.$key.'.max'));
            else
                $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.mes.invalid_photo'));
        }
    }

    public function testDistributionCreateWithPermission()
    {
        $types = [HR_MANAGER, COMPANY_MANAGER, HR, COMPANY];
        foreach ($types as $type) {
            $this->loginWith($type);
            $resp = $this->post('/api/notification', $this->dataTest);
            $this->assertEquals($resp->decodeResponseJson()['status'], Response::HTTP_UNAUTHORIZED);
            $this->assertEquals($resp->decodeResponseJson()['message'], trans('messages.mes.permission'));
        }
    }

    public function testDistributionCreateSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $resp = $this->post('/api/notification', $this->dataTest);
        $this->assertEquals($resp->decodeResponseJson()['code'], CODE_SUCCESS);
        $user = User::query()->count();
        $notifies = Notification::query()->where(Notification::TYPE, DistributionNotification::class)->count();
        $this->assertTrue($user == $notifies);
    }

    public function testDistributionIndexSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notification/distribution/other');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['result'];
            $this->assertEquals(1, count($result));
            $this->assertEquals(User::where(User::TYPE, $type)->first()->id, $result[0]['notifiable_id']);
        }
    }

    public function testDistributionSortByCreateAt()
    {
        $this->loginWith(\HR);
        for ($i = 1; $i <= 5; $i++) {
            Notification::factory()->create([Notification::CREATED_AT => $this->faker->dateTimeBetween('-1 Month')]);
        }
        $response = $this->get('api/notification/distribution/other');
        $listResult = $response->decodeResponseJson()['data']['result'];
        $count = count($listResult);
        foreach ($listResult as $key => $item) {
            $keyItem = $count == $key + 1 ? $key : $key + 1;
            $this->assertTrue($item[Notification::CREATED_AT] >= $listResult[$keyItem][Notification::CREATED_AT]);
        }
    }

    public function testDistributionShowPaginateSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notification/distribution/other');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testDistributionNextPageSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notification/distribution/other?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testDistributionPaginateFirstPage()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notification/distribution/other?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testDistributionDetailFail()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $notifyIdNotPermission = Notification::query()->where(Notification::NOTIFIABLE_ID,'!=', Auth::id())->first()->id;
            $response = $this->get('api/notification/'.$notifyIdNotPermission);

            $this->assertEquals($response->decodeResponseJson()['code'], CODE_NO_ACCESS);
            $this->assertEquals($response->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));

            $notifyNotExist = $this->get('api/notification/999');
            $this->assertEquals($notifyNotExist->decodeResponseJson()['code'], CODE_NO_ACCESS);
            $this->assertEquals($notifyNotExist->decodeResponseJson()['message'], trans('messages.data_does_not_exist'));
        }
    }

    public function testDistributionDetailSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->post('/api/notification', $this->dataTest);

        foreach ($this->types as $type) {
            $this->loginWith($type);
            $notify = Notification::where(Notification::TYPE, DistributionNotification::class)
                ->where(Notification::NOTIFIABLE_ID, Auth::id())->first();
            $response = $this->get('api/notification/' . $notify->id);
            $this->assertEquals(CODE_SUCCESS, $response->decodeResponseJson()['code']);
            $data = json_decode($response->decodeResponseJson()['data']['data']);
            $this->assertTrue($this->dataTest['title'] == $data->title);
            $this->assertTrue($this->dataTest['text'] == $data->text);
            $this->assertTrue(UploadFile::find($this->dataTest['image_id'])->file_path == $data->image);
        }
    }

    private function fakeData()
    {
        Entry::factory()->count(3)->create();
        Entry::factory()->create([Entry::DISPLAY => 'off']);
        Offer::factory()->count(4)->create();
    }

    public function testOnGoingJobIndexSuccess()
    {
        foreach ($this->types as $type) {
            $this->artisan('migrate:fresh --seed');
            $this->loginWith($type);
            $this->fakeData();
            $response = $this->get('api/user/notify');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['pagination']['total_records'];
            $this->assertEquals(7, $result);
            $response->assertJsonStructure([
                "code",
                "data" => [
                    "result" => [['date', 'full_name', 'full_name_ja', 'job_name', 'company_name', 'company_name_ja', 'occupation']],
                    "pagination"
                ]
            ]);
        }
    }

    public function testOnGoingJobShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $this->fakeData();
            $response = $this->get('api/user/on-going-job');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testOnGoingJobNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/user/on-going-job?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testOnGoingJobPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/user/on-going-job?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testMessageListSuccess(){
        foreach ($this->types as $type) {
            $user = User::query()->where('type', $type)->first();
            $this->fakeMessage($user->id);
            $this->loginWith($type);
            $response = $this->get('api/notify');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $result = $response->decodeResponseJson()['data']['pagination']['total_records'];
            $this->assertEquals(10, $result);
            $response->assertJsonStructure([
                "code",
                "data" => [
                    "result" => [['id', 'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at', 'created_at', 'updated_at']],
                    "pagination"
                ]
            ]);
        }
    }

    public function testMessageListShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $user = User::query()->where('type', $type)->first();
            $this->fakeMessage($user->id);
            $response = $this->get('api/notify');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testMessageListNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notify?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testMessageListPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->get('api/notify?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    private function fakeMessage($userId){
        for ($i = 0; $i < 10; $i++){
            $user = User::query()->find($userId);
            $data['permission'] = User::getPermissionName($user);
            $data['subject'] = 'test message';
            $data['text'] = 'this is note text';
            $data['company'] = 'Veho Company';
            $data['content'] = '求人にエントリーが実行されました。<br><a href="/matching-management">こちら</a>よりエントリーの内容を確認してください。';
            $data['entry_code'] = '001';
            $data['job'] = 'Develop PHP Senior';
            $data['full_name_ja'] = 'Nguyen Thi Nhi';
            $data['date'] = Carbon::now()->format('Y/m/d');
            $data['contentHTML'] = view('messages.remind.format_b', compact('data'))->render();
            \Illuminate\Support\Facades\Notification::send($user, new RemindAccountNotification($data));
        }
    }
}


