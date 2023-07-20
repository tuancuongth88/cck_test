<?php

namespace Tests\Feature;

use App\Models\HR;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FavoriteTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    protected $types;
    protected $hr_id;
    protected $work_id;

    public function setUp(): void
    {
        parent::setUp();
        $this->types = [HR_MANAGER, COMPANY_MANAGER, HR, COMPANY];
        $this->work_id = Work::factory()->create()->id;
        $this->loginWith(\HR);
        $this->hr_id = HR::factory()->create([HR::CREATED_BY => Auth::id()])->id;
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFavoriteCreateWhenAllEmpty()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->post('api/user-favorite', []);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testFavoriteCreateWithTypeItemNotExist()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == HR)
                $data = [
                    'relation_id' => $this->work_id,
                    'type' => 3
                ];
            else
                $data = [
                    'relation_id' => $this->hr_id,
                    'type' => 3
                ];

            $response = $this->user_login->post('api/user-favorite', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testFavoriteCreateWithTypeItemFail()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == \HR) {
                $data = [
                    'relation_id' => $this->work_id,
                    'type' => FAVORITE_TYPE_HRS
                ];
            } else {
                $data = [
                    'relation_id' => $this->hr_id,
                    'type' => FAVORITE_TYPE_WORK
                ];
            }

            $response = $this->user_login->post('api/user-favorite', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testFavoriteCreateWithRelationIdItemNotExist()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == \HR) {
                $data = [
                    'relation_id' => 9999,
                    'type' => FAVORITE_TYPE_WORK
                ];
                $msg = trans('api.favorite.checkFavoriteJob');
            } else {
                $data = [
                    'relation_id' => 9999,
                    'type' => FAVORITE_TYPE_HRS
                ];
                $msg = trans('api.favorite.checkFavoriteHr');
            }

            $response = $this->user_login->post('api/user-favorite', $data);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
            $this->assertEquals($msg, $response->decodeResponseJson()['message']);
        }
    }

    public function testFavoriteCreateSuccess()
    {
        array_push($this->types, SUPER_ADMIN);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == \HR) {
                $data = [
                    'relation_id' => $this->work_id,
                    'type' => FAVORITE_TYPE_WORK
                ];
            } else {
                $data = [
                    'relation_id' => $this->hr_id,
                    'type' => FAVORITE_TYPE_HRS
                ];
            }

            $response = $this->user_login->post('api/user-favorite', $data);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertDatabaseHas('user_favorites', [
                'user_id' => Auth::id(),
                'relation_id' => $data['relation_id'],
                'type' => $data['type']
            ]);
        }
    }

    public function testFavoriteDeleteAllEmpty()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->delete('api/user-favorite/delete', []);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
        }
    }

    public function testFavoriteDeleteFail()
    {
        $this->loginWith(\HR);
        $data = [
            'relation_id' => $this->work_id,
            'type' => FAVORITE_TYPE_WORK
        ];
        $this->user_login->post('api/user-favorite', $data);
        array_push($this->types, SUPER_ADMIN);
        $roles = [SUPER_ADMIN, HR_MANAGER];
        foreach ($roles as $role) {
            $this->loginWith($role);
            $response = $this->user_login->delete('api/user-favorite/delete', $data);
            $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->decodeResponseJson()['code']);
            $this->assertEquals('This action is unauthorized.', $response->decodeResponseJson()['message_content']);
        }

        $this->loginWith(COMPANY);
        $data = [
            'relation_id' => $this->hr_id,
            'type' => FAVORITE_TYPE_HRS
        ];
        $this->user_login->post('api/user-favorite', $data);
        $roles = [SUPER_ADMIN, COMPANY_MANAGER];
        foreach ($roles as $role) {
            $this->loginWith($role);
            $response = $this->user_login->delete('api/user-favorite/delete', $data);
            $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->decodeResponseJson()['code']);
            $this->assertEquals('This action is unauthorized.', $response->decodeResponseJson()['message_content']);
        }
    }

    public function testFavoriteDeleteSuccess()
    {
        array_push($this->types, SUPER_ADMIN);
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == \HR) {
                $data = [
                    'relation_id' => $this->work_id,
                    'type' => FAVORITE_TYPE_WORK
                ];
            } else {
                $data = [
                    'relation_id' => $this->hr_id,
                    'type' => FAVORITE_TYPE_HRS
                ];
            }

            $response = $this->user_login->post('api/user-favorite', $data);

            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);

            $response = $this->user_login->delete('api/user-favorite/delete', $data);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testIndexSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/user-favorite');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        }
    }

    public function testShowPaginateSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/user-favorite');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertContains($response->decodeResponseJson()['data']['pagination'], $response->decodeResponseJson()['data']);
        }
    }

    public function testNextPageSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/user-favorite?page=2&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(2, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testPaginateFirstPage()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            $response = $this->user_login->get('api/user-favorite?page=1&per_page=1');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertEquals(1, $response->decodeResponseJson()['data']['pagination']['current_page']);
        }
    }

    public function testListWithSuperAdminFail()
    {
        $this->loginWith(SUPER_ADMIN);
        $response = $this->user_login->get('api/user-favorite');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
    }

    public function testListWithSuperAdminSuccess()
    {
        $this->loginWith(SUPER_ADMIN);
        $this->user_login->post('api/user-favorite', [
            'relation_id' => $this->work_id,
            'type' => FAVORITE_TYPE_WORK
        ]);
        $this->user_login->post('api/user-favorite', [
            'relation_id' => $this->hr_id,
            'type' => FAVORITE_TYPE_HRS
        ]);

        $response = $this->user_login->get('api/user-favorite?type=1');
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $result = $response->decodeResponseJson()['data']['result'];
        $this->assertCount(1, $result);

        $response = $this->user_login->get('api/user-favorite?type=2');
        $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
        $result = $response->decodeResponseJson()['data']['result'];
        $this->assertCount(1, $result);
    }

    public function testListSuccess()
    {
        foreach ($this->types as $type) {
            $this->loginWith($type);
            if ($type == HR_MANAGER || $type == \HR) {
                $data = [
                    'relation_id' => $this->work_id,
                    'type' => FAVORITE_TYPE_WORK
                ];
            } else {
                $data = [
                    'relation_id' => $this->hr_id,
                    'type' => FAVORITE_TYPE_HRS
                ];
            }

            $response = $this->user_login->post('api/user-favorite', $data);
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);

            $response = $this->user_login->get('api/user-favorite');
            $this->assertEquals(Response::HTTP_OK, $response->decodeResponseJson()['code']);
            $this->assertCount(1, $response->decodeResponseJson()['data']['result']);
        }
    }
}
