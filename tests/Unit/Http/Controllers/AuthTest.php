<?php

namespace Tests\Unit\Http\Controllers;
use App\Helpers\Response;
use App\Helpers\ResponseCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;
use Faker\Factory as Faker;
use Repository\AuthRepository;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
class AuthTest extends TestCase
{

    protected $request;
    protected $authRepository;
    protected $loginRequest;
    protected $user;
    protected $param;

    public function setUp() : void
    {

        parent::setUp();
        Facade::clearResolvedInstances();

        $this->faker = Faker::create();
        // chuẩn bị dữ liệu test
        $this->user = [
            'department_id '=> $this->faker->name,
            'name'=> $this->faker->name,
            'email'=> $this->faker->email,
            'phone'=> $this->faker->phoneNumber ,
            'password'=> Hash::make('123456789'),
            'password_confirmation' => Hash::make('123456789'),
            'user_name'=> $this->faker->name,
            'fax'=> $this->faker->name,
            'address'=> $this->faker->name,
            'avatar'=> $this->faker->name,
        ];
        $this->request = new LoginRequest();
        $this->authRepository = new AuthRepository();
        $this->loginRequest  = new LoginRequest();
    }

    public function tearDown() : void
    {
        parent::tearDown();
    }

    public function testLogin()
    {
        $user = User::factory()->count(1)->create();
        // Gọi hàm tạo
        $param = [
            'user_name'=> $user->first()->email,
            'password'=> '123456789',
        ];
        $this->request->merge($param);
         $response=$this->authRepository->doLogin( $this->request, $guard = null);
         //kiem tra co tra ve object khong
         $this->assertInstanceOf(User::class, $response['user']);
         //kiem tra co tra ve du lieu hay khong
         $this->assertEquals($param['user_name'], $response['user']->email);
    }

    public function test_login_wrong_user_or_pass(){
        $param = [
            'user_name'=> 'test@gmail.com',
            'password'=> '1234567',
        ];
        $this->request->merge($param);
        $response=$this->authRepository->doLogin( $this->request, $guard = null);
        $this->assertEquals(false, $response['attempt']);
    }
    public function testLoginNotHavePassword()
    {
        $param = [
            'user_name'=> 'test@gmail.com',
            'password'=> '',
        ];
        $this->request->merge($param);
        $response=$this->authRepository->doLogin( $this->request, $guard = null);
        // dd($response);
        $this->assertEquals(false, $response['attempt']);
    }
    public function testLoginNotHaveParams()
    {
        $param = [
            'user_name'=> '',
            'password'=> '',
        ];
        $this->request->merge($param);
        $response=$this->authRepository->doLogin( $this->request, $guard = null);
        // dd($response);
        $this->assertEquals(false, $response['attempt']);
    }
    public function testLoginWrongTypeEmail()
    {
        $param = [
            'user_name'=> 'testgmail.com',
            'password'=> '',
        ];
        $this->request->merge($param);
        $response=$this->authRepository->doLogin( $this->request, $guard = null);
        // dd($response);
        $this->assertEquals(false, $response['attempt']);
    }
    public function testLoginNotHaveEmailOrPhoneNumber()
    {
        $param = [
            'user_name'=> '',
            'password'=> '123456789',
        ];
        $this->request->merge($param);
        $response=$this->authRepository->doLogin( $this->request, $guard = null);
        $this->assertEquals(false, $response['attempt']);
    }

    public function testLogout(){
        $user = User::factory()->count(1)->create();
        $response = $this->call('POST','api/auth/login', [
            'user_name' => $user->first()->email,
            'password' => '123456789',
            '_token' => csrf_token()
        ]);

        $responseUser = $this->get('api/profile', ['Authorization' => $response->decodeResponseJson()['data']['access_token']]); // Added this line
        if($responseUser->getStatusCode() == ResponseCode::HTTP_OK){
            $token = $response->decodeResponseJson()['data']['access_token'];
            $this->get('api/auth/logout', ['Authorization' => $token]); // Logout user
            $result = $this->get('api/profile', ['Authorization' => $token]);
            $this->assertEquals(ResponseCode::HTTP_OK, $result->decodeResponseJson()['code']);
        }
    }
}
