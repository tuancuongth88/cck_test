<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Api\UserController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Repositories\UserRepository;
use Mockery as m;
use Illuminate\Foundation\Testing\WithFaker;


class UserTest extends TestCase
{

    protected $user;
    protected $userRepository;
    /**
     * @var UserController
     */
    protected $userController;
    protected $repository;
    protected $userRequest;
    // create false
    protected $user_create_email_in_validate;
    protected $user_create_username_not_null;
    protected $user_create_password_not_null;
    protected $user_create_email_not_null;
    protected $user_create_email_username_password_not_null;
    // update false
    protected $user_update_email_in_validate;
    protected $user_update_email_not_null;
    protected $user_update_username_not_null;
    protected $user_update_username_email_not_null;


    use WithFaker;

    public function setUp(): void
    {
        $app = new Application();
        $userRepository = new UserRepository($app);

        $this->afterApplicationCreated(function () use ($userRepository) {
            $this->userRepository = m::mock($userRepository)->makePartial();
            $this->userController = new UserController(
                $this->app->instance(UserRepositoryInterface::class, $this->userRepository)
            );
        });
        $this->userRequest = new UserRequest();
        $this->faker = Faker::create();

        // chuẩn bị dữ liệu test
        $this->user = [
            'username' => 'testuser' . Carbon::now()->timestamp,
            'email' => Carbon::now()->timestamp . 'testuser@gmail.com',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_create_email_in_validate = [
            'username' => 'testuser123',
            'email' => 'testusergmail.com',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_create_username_not_null = [
            'username' => '',
            'email' => 'testuser@gmail.com',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_create_password_not_null = [
            'username' => "testuser",
            'email' => Carbon::now()->timestamp .'testuser@gmail.com',
            'name' => "name user",
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_create_email_not_null = [
            'username' => 'testuser',
            'email' => '',
            'name' => 'name user',
            'password' => '',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_create_email_username_password_not_null = [
            'username' => '',
            'email' => '',
            'name' => 'name user',
            'password' => '',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_update_email_in_validate = [
            'username' => 'testuser123',
            'email' => 'testusergmail.com',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_update_email_not_null = [
            'username' => 'testuser123',
            'email' => '',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_update_username_not_null = [
            'username' => '',
            'email' => 'test@gmail.com',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];
        $this->user_update_username_email_not_null = [
            'username' => '',
            'email' => '',
            'name' => 'name user',
            'password' => '$2y$10$WNkRsh5.82Igk/r.MIsmHeUo6i2A7E4KsN54f02NTIEIMTdcBOGhO',
            'status' => 4,
            'role' => 'general_affairs',
        ];

        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * test User.
     * @param UserRequest $this ->param
     * @param null $guard
     * @return void
     */


    public function testUserCreateSuccess()
    {
        $this->userRequest->merge($this->user);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUserUpdateSuccess()
    {
        $this->userRequest->merge($this->user);
        $user = User::factory()->create();
        $response = $this->userController->update($this->userRequest, $user->id);
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testUserShowID()
    {
        // Gọi hàm tạo
        $this->userRequest->merge($this->user);
        $user = User::factory()->create();
        $response = $this->userController->show($user->id);
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testUserShowAll()
    {
        $this->userRequest->merge([]);
        User::factory()->create();
        $response = $this->userController->index($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }


    public function testUserShowAllWithParam()
    {
        $search = [
            'search' => 'user name | role name | ',
            'order_column' => 'order_column',
            'order_type' => 'ASC | DESC',
        ];

        $this->userRequest->merge($search);
        User::factory()->create();
        $response = $this->userController->index($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUserDestroy()
    {
        $this->userRequest->merge($this->user);
        $user = User::factory()->create();
        $response = $this->userController->destroy($user->id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUserDestroyMany()
    {
        $listId = [];
        for ($i = 0; $i < 10; $i++) {
            $userNew = $this->user;
            unset($userNew["role"]);
            $user = User::factory()->create($userNew);
            $listId[] = $user->id;
        }
        $listIdDel = [
            "id" => $listId
        ];
        $this->userRequest->merge($listIdDel);
        $response = $this->userController->destroyMany($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }
    public function testUserDestroyManyFalse()
    {
        $listId = [];
        $listIdDel = [
            "id" => $listId
        ];
        $this->userRequest->merge($listIdDel);
        $response = $this->userController->destroyMany($this->userRequest);
        $this->assertEquals(200,  get_object_vars($response)['original']['code']);
    }

    // case false
    // create email in validate
    public function testUserNotCreateEmailInValidate()
    {
        $this->userRequest->merge($this->user_create_email_in_validate);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // create User name not null
    public function testUserNotCreateUserNameNotNull()
    {
        $this->userRequest->merge($this->user_create_username_not_null);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // update user email email invalidate
    public function testUserNotUpdateEmailInValidate()
    {
        $this->userRequest->merge($this->user_update_email_in_validate);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // update user email not null
    public function testUserNotUpdateEmailNotNull()
    {
        $this->userRequest->merge($this->user_update_email_not_null);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // user update username not null
    public function testUserNotUpdateUsernameNotNull()
    {
        $this->userRequest->merge($this->user_update_username_not_null);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // user update username and email not null
    public function testUserNotUpdateUsernameEmailNotNull()
    {
        $this->userRequest->merge($this->user_update_username_email_not_null);
        $response = $this->userController->store($this->userRequest);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
