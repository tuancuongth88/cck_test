<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Entry;
use App\Models\HR;
use App\Models\Offer;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

    public function testSortByColumn()
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->loginWith(\HR);
            $entry = Entry::factory()->create([
                Entry::HR_ID => HR::factory()->create([HR::CREATED_BY => Auth::id()])->id,
                Entry::WORK_ID => Work::factory()->create()->id,
                Entry::STATUS => ENTRY_STATUS_REQUESTING,
                Entry::DISPLAY => 'on',
                Entry::ENTRY_CODE => rand(1000000000, 9999999999)
            ])->first();
            $this->user_login->post('api/interview', ['entry_id' => $entry->id]);
        }

        foreach ($this->types as $type) {
            $this->loginWith($type);
            $fields = ['id', 'code', 'interview_date', 'full_name', 'job_name', 'status_selection', 'status_interview_adjustment'];

            foreach ($fields as $field) {
                $response = $this->user_login->json('get', 'api/interview?field=' . $field . '&sort_by=desc');
                $listResult = $response->decodeResponseJson()['data']['result'];
                $count = count($listResult);
                foreach ($listResult as $key => $item) {
                    $keyItem = $count == $key + 1 ? $key : $key + 1;
                    $this->assertTrue($item[$field] >= $listResult[$keyItem][$field]);
                }
            }
        }
    }
}
