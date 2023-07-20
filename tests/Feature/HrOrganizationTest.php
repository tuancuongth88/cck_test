<?php

namespace Tests\Feature;

use App\Models\HrOrganization;
use App\Models\UploadFile;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class HrOrganizationTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $dataTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->faker = Faker::create();
        $file = UploadFile::factory()->create();

        $this->dataTest = [
            HrOrganization::CORPORATE_NAME_EN => 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
            HrOrganization::CORPORATE_NAME_JA => 'LOD人材開発株式会社',
            HrOrganization::LICENSE_NO => '12345678',
            HrOrganization::ACCOUNT_CLASSIFICATION => '1',
            HrOrganization::COUNTRY => array_rand(COUNTRY),
            HrOrganization::REPRESENTATIVE_FULL_NAME => '鈴木　一郎',
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA => 'スズキ　イチロウ',
            HrOrganization::REPRESENTATIVE_CONTACT => '+84 0112345678',
            HrOrganization::ASSIGNEE_CONTACT => '+84 0187654321',
            HrOrganization::POST_CODE => '100-001',
            HrOrganization::PREFECTURES => 'Hanoi',
            HrOrganization::MUNICIPALITY => 'Ba Dinh',
            HrOrganization::NUMBER => '266 Lieu Giay Doi Can ',
            HrOrganization::OTHER => '鈴木　太郎',
            HrOrganization::MAIL_ADDRESS => 'okuridashi_hanoi@gmail.vn',
            HrOrganization::URL => 'https://okuridashi_hanoi.com',
            HrOrganization::CERTIFICATE_FILE => $file->id,
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
        unset($dataTest[HrOrganization::REPRESENTATIVE_CONTACT], $dataTest[HrOrganization::OTHER]);
        foreach ($dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $response = $this->call('POST', 'api/hr-organization', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithItemsMaxCharacter()
    {
        $keys = [
            HrOrganization::CORPORATE_NAME_EN,
            HrOrganization::CORPORATE_NAME_JA,
            HrOrganization::LICENSE_NO,
            HrOrganization::REPRESENTATIVE_FULL_NAME,
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA,
            HrOrganization::PREFECTURES,
            HrOrganization::MUNICIPALITY,
            HrOrganization::NUMBER,
            HrOrganization::OTHER,
            HrOrganization::MAIL_ADDRESS
        ];
        $str = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque.";
        foreach ($keys as $key => $value) {
            $data = $this->dataTest;
            $data[$value] = $str;
            $response = $this->call('POST', 'api/hr-organization', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithItemsInvalid()
    {
        $keys = [
            HrOrganization::MAIL_ADDRESS => 'user@example..com',
            HrOrganization::POST_CODE => 'a9999999999',
            HrOrganization::URL => 'http//example.com:',
            HrOrganization::COUNTRY => 10
        ];
        foreach ($keys as $key => $value) {
            $data = $this->dataTest;
            $data[$key] = $value;
            $response = $this->call('POST', 'api/hr-organization', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testCreateWithMailItemHasExist()
    {
        $dataTest = $this->dataTest;
        unset($dataTest['is_create']);
        HrOrganization::factory()->create($dataTest);
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        $this->assertEquals(trans('messages.email_existed'), $response->getData()->message);
    }

    public function testCreateWithFileItemNotExist()
    {
        $data = $this->dataTest;
        $data[HrOrganization::CERTIFICATE_FILE] = 9999;
        $response = $this->call('POST', 'api/hr-organization', $data);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testCreateSuccess()
    {
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testIndexSuccess()
    {
        $this->user_admin->json('get', 'api/hr-organization')
            ->assertStatus(Response::HTTP_OK);
    }

    public function testShowPaginateSuccess()
    {
        $response = $this->user_admin->json('get', 'api/hr-organization')
            ->assertStatus(Response::HTTP_OK);
        $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
    }

    public function testPaginateFirstPage()
    {
        $response = $this->user_admin->json('get', 'api/hr-organization?page=1&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testNextPageSuccess()
    {
        $response = $this->user_admin->json('get', 'api/hr-organization?page=2&per_page=1')
            ->assertStatus(CODE_SUCCESS);
        $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
    }

    public function testSortByColumn()
    {
        HrOrganization::factory()->count(4)->create();
        $fields = [
            HrOrganization::CORPORATE_NAME_EN,
            HrOrganization::CORPORATE_NAME_JA,
            HrOrganization::LICENSE_NO,
            HrOrganization::ACCOUNT_CLASSIFICATION,
            HrOrganization::COUNTRY,
            HrOrganization::REPRESENTATIVE_FULL_NAME,
            HrOrganization::REPRESENTATIVE_FULL_NAME_FURIGANA,
            HrOrganization::REPRESENTATIVE_CONTACT,
            HrOrganization::ASSIGNEE_CONTACT,
            HrOrganization::POST_CODE,
            HrOrganization::PREFECTURES,
            HrOrganization::MUNICIPALITY,
            HrOrganization::NUMBER,
            HrOrganization::OTHER,
            HrOrganization::MAIL_ADDRESS,
            HrOrganization::URL,
            HrOrganization::CERTIFICATE_FILE,
            HrOrganization::STATUS,
            'updated_at'
        ];
        foreach ($fields as $field) {
            $response = $this->user_admin->json('get', 'api/hr-organization?field='.$field.'&sort_by=desc')
                ->assertStatus(CODE_SUCCESS);
            $listResult = $response->decodeResponseJson()['data']['result'];
            $count = count($listResult);
            foreach ($listResult as $key => $item){
                $keyItem = $count == $key + 1 ? $key : $key + 1;
                $this->assertTrue($item[$field] >= $listResult[$keyItem][$field]);
            }
        }
    }

    public function testGetDetail(){
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $data = HrOrganization::query()->first();
        $result = $this->user_admin->get('api/hr-organization/'.$data->id)
            ->assertStatus(CODE_SUCCESS);
        $this->assertTrue($response->decodeResponseJson()['data']['corporate_name_ja'] == $result->decodeResponseJson()['data']['corporate_name_ja']);
    }

    public function testUpdateWithItemsNull()
    {
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $dataTest = $this->dataTest;
        unset($dataTest[HrOrganization::REPRESENTATIVE_CONTACT], $dataTest[HrOrganization::OTHER], $dataTest['is_create']);
        foreach ($dataTest as $key => $value) {
            $data = $this->dataTest;
            unset($data[$key]);
            $result = $this->user_admin->put('api/hr-organization/'.$response->decodeResponseJson()['data']['id'], $data);
            $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function testUpdateWithEmailNotChange()
    {
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $this->dataTest[HrOrganization::CORPORATE_NAME_JA] = $this->faker->name;
        $result = $this->user_admin->put('api/hr-organization/'.$response->decodeResponseJson()['data']['id'], $this->dataTest);
        $this->assertEquals(Response::HTTP_OK, $result->decodeResponseJson()['code']);
        $this->assertTrue($result->decodeResponseJson()['data'][HrOrganization::CORPORATE_NAME_JA] == $this->dataTest[HrOrganization::CORPORATE_NAME_JA]);
    }

    public function testUpdateSuccess()
    {
        $putData = $this->dataTest;
        $putData[HrOrganization::CORPORATE_NAME_JA] = $this->faker->name;
        $putData[HrOrganization::MAIL_ADDRESS] = $this->faker->email;

        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $data = $this->user_admin->put('api/hr-organization/'.$response->decodeResponseJson()['data']['id'], $putData)
            ->assertStatus(CODE_SUCCESS);
        $this->assertTrue($putData[HrOrganization::CORPORATE_NAME_JA] == $data->decodeResponseJson()['data'][HrOrganization::CORPORATE_NAME_JA]);
    }

    public function testExaminationResultConfirm(){
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $result = $this->user_admin->post('api/update-status-hr', ['hr_id' => $response->decodeResponseJson()['data']['id'], 'status' => CONFIRM]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }

    public function testExaminationResultReject(){
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $result = $this->user_admin->post('api/update-status-hr', ['hr_id' => $response->decodeResponseJson()['data']['id'], 'status' => REJECT]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }

    public function testExaminationResultStopFail(){
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $result = $this->user_admin->post('api/update-status-hr', ['hr_id' => $response->decodeResponseJson()['data']['id'], 'status' => STOP_USING]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testExaminationResultStop(){
        $response = $this->call('POST', 'api/hr-organization', $this->dataTest);
        $this->user_admin->post('api/update-status-hr', ['hr_id' => $response->decodeResponseJson()['data']['id'], 'status' => CONFIRM]);
        $result = $this->user_admin->post('api/update-status-hr', ['hr_id' => $response->decodeResponseJson()['data']['id'], 'status' => STOP_USING]);
        $this->assertTrue($result->decodeResponseJson()['code'] == Response::HTTP_OK);
    }
}
