<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobInfo;
use App\Models\JobType;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

class CompanyTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    private $dataTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->faker = Faker::create();
        $majorId = JobType::query()
            ->where('type', MAJOR_CLASSIFICATION)
            ->inRandomOrder()
            ->value('id');
        $middleId = JobInfo::query()
            ->where('job_type_id', $majorId)
            ->value('id');

        $this->dataTest = [
            Company::COMPANY_NAME => 'City Computer Co., Ltd.',
            Company::COMPANY_NAME_JP => 'シティコンピュータ株式会社',
            Company::MAJOR_CLASSIFICATION => $majorId,
            Company::MIDDLE_CLASSIFICATION => $middleId,
            Company::POST_CODE => '1020093',
            Company::PREFECTURES => '東京都',
            Company::MUNICIPALITY => '千代田区平河町',
            Company::NUMBER => '1-7-10',
            Company::OTHER => '大盛丸平河町ビル2階',
            Company::TELEPHONE_NUMBER => '+84 0312345678',
            Company::MAIL_ADDRESS => 'vuhoa11052000@gmail.com',
            Company::URL => 'https://okuridashi_hanoi.com',
            Company::JOB_TITLE => '代表取締役会長',
            Company::FULL_NAME => '鈴木　太郎',
            Company::FULL_NAME_FURIGANA => 'スズキ　タロウ',
            Company::REPRESENTATIVE_CONTACT => '+84 0312345678',
            Company::ASSIGNEE_CONTACT => '+84 0312345678',
            'is_create' => 1
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * A basic unit test example 1.
     *
     * @return void
     */
    /** @test */
    public function testCreateWithItemsNull()
    {
        $dataTest = $this->dataTest;
        unset($dataTest[Company::REPRESENTATIVE_CONTACT], $dataTest[Company::OTHER]);
        foreach ($dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->call('POST', 'api/company', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithItemsInvalid()
    {
        $keys = [
            Company::MAIL_ADDRESS => 'user@example..com',
            Company::POST_CODE => 'a9999999999',
            Company::URL => 'http//example.com:'
        ];
        foreach ($keys as $key => $value) {
            $data = $this->dataTest;
            $data[$key] = $value;
            $response = $this->call('POST', 'api/company', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithItemsMaxCharacter()
    {
        $keys = [
            Company::COMPANY_NAME,
            Company::COMPANY_NAME_JP,
            Company::POST_CODE,
            Company::PREFECTURES,
            Company::MUNICIPALITY,
            Company::NUMBER,
            Company::OTHER,
            Company::MAIL_ADDRESS,
            Company::JOB_TITLE,
            Company::FULL_NAME,
            Company::FULL_NAME_FURIGANA,
            Company::REPRESENTATIVE_CONTACT,
            Company::ASSIGNEE_CONTACT,
        ];
        $str = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque.";
        foreach ($keys as $key => $value) {
            $data = $this->dataTest;
            $data[$value] = $str;
            $response = $this->call('POST', 'api/company', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithMailItemHasExist()
    {
        $dataTest = $this->dataTest;
        unset($dataTest['is_create']);
        Company::factory()->create($dataTest);
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        $this->assertEquals(trans('messages.email_existed'), $response->getData()->message);
    }

    public function testCreateWithMajorClassItemNotExist()
    {
        $data = $this->dataTest;
        $data[Company::MAJOR_CLASSIFICATION] = 9999;
        $response = $this->call('POST', 'api/company', $data);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testCreateWithMiddleClassItemNotExist()
    {
        $data = $this->dataTest;
        $data[Company::MIDDLE_CLASSIFICATION] = 9999;
        $response = $this->call('POST', 'api/company', $data);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testCreateSuccess()
    {
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testIndexSuccess()
    {
        $response = $this->user_admin->json('get', 'api/company')
            ->assertStatus(Response::HTTP_OK);
    }

    public function testShowPaginateSuccess()
    {
        $response = $this->user_admin->json('get', 'api/company')
            ->assertStatus(Response::HTTP_OK);
        $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
    }

    public function testNextPageSuccess()
    {
        $response = $this->user_admin->json('get', 'api/company?page=2&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testPaginateFirstPage()
    {
        $response = $this->user_admin->json('get', 'api/company?page=1&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testSortByCode()
    {

        $response = $this->user_admin->json('get', 'api/company?field=company_name_jp&sort_by=desc')
            ->assertStatus(CODE_SUCCESS);
        $listResult = $response->decodeResponseJson()['data']['result'];
        foreach ($listResult as $key => $item){
            $keyItem = count($listResult) == $key + 1 ? $key : $key + 1;
            $this->assertTrue($item['company_name_jp'] <= $listResult[$keyItem]['company_name_jp']);
        }
    }

    public function testGetDetail(){
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $data = Company::query()->first();
        $result = $this->user_admin->get('api/company/'.$data->id)
                                ->assertStatus(CODE_SUCCESS);
        $this->assertTrue($response->decodeResponseJson()['data']['company_name_jp'] == $result->decodeResponseJson()['data']['company_name_jp']);
    }

    public function testUpdateSuccess()
    {
        $putData = $this->dataTest;
        $putData['company_name_jp'] = 'abc';
        $putData['mail_address'] = $this->faker->email;
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $data = $this->user_admin->put('api/company/'.$response->decodeResponseJson()['data']['id'], $putData)
                                ->assertStatus(CODE_SUCCESS);
        $this->assertTrue($putData['company_name_jp'] == $data->decodeResponseJson()['data']['company_name_jp']);
    }

    public function testUpdateWhenAllEmpty()
    {
        $dataEmpty = [
            Company::COMPANY_NAME => '',
            Company::COMPANY_NAME_JP => '',
            Company::POST_CODE => '',
            Company::PREFECTURES => '',
            Company::MUNICIPALITY => '',
            Company::NUMBER => '',
            Company::MAIL_ADDRESS => '',
            Company::JOB_TITLE => '',
            Company::FULL_NAME => '',
            Company::FULL_NAME_FURIGANA => '',
            Company::REPRESENTATIVE_CONTACT => '',
            Company::ASSIGNEE_CONTACT => '',
        ];
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $result = $this->user_admin->put('api/company/'.$response->decodeResponseJson()['data']['id'], $dataEmpty);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testExaminationResultConfirm(){
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $result = $this->user_admin->post('api/update-status-company', ['company_id' => $response->decodeResponseJson()['data']['id'],'status' => CONFIRM]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }

    public function testExaminationResultReject(){
        $response = $this->call('POST', 'api/company', $this->dataTest);

        $result = $this->user_admin->post('api/update-status-company/', ['company_id' => $response->decodeResponseJson()['data']['id'],'status' => REJECT]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }

    public function testExaminationResultStop(){
        $response = $this->call('POST', 'api/company', $this->dataTest);
        $result = $this->user_admin->post('api/update-status-company', ['company_id' => $response->decodeResponseJson()['data']['id'],'status' => REJECT]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }

}
