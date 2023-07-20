<?php

namespace Tests\Feature;

use App\Models\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\Feature\Session;
use Faker\Factory as Faker;

class AuthTest extends BaseTest
{
    use RefreshDatabase;

    protected $faker;
    private $token;
    private $current;
    private $new;
    private $mail;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->faker = Faker::create();
        $this->current = '123456789CCk';
        $this->new = '123456789Ck1';
        $this->mail = '1okuridashi_hanoi@gmail.vn';
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
    public function testLoginSuccessfully()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => '1okuridashi_hanoi@gmail.vn',
            'password' => '123456789CCk',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testLoginNotHavePassword()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => '1okuridashi_hanoi@gmail.vn',
            'password' => '',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
    }

    public function testLoginNotHaveEmail()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => '',
            'password' => '123456789CCk',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
    }

    public function testLoginNotHaveParams()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => '',
            'password' => '',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->decodeResponseJson()['code']);
    }

    public function testLoginWrongPassword()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => '1okuridashi_hanoi@gmail.vn',
            'password' => '123456789',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->decodeResponseJson()['code']);
        $this->assertEquals(trans('auth.failed.password'), $response->getData()->message);
    }

    public function testLoginWrongTypeEmail()
    {
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => 'okuridashi_hanoi@gmail.vn',
            'password' => '123456789CCk',
            '_token' => csrf_token()
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->decodeResponseJson()['code']);
        $this->assertEquals(trans('auth.failed.mail_address'), $response->getData()->message);
    }

    public function testLogout()
    {
        $response = $this->user_admin->post('api/auth/logout');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testChangePasswordNotHaveParams()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', []);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testChangePasswordItemsNotHave()
    {
        $data = [
            'current_password',
            'current_password_confirm'
        ];
        for ($i = 0; $i < 2; $i++) {
            $response = $this->user_admin->post('api/auth/confirm-password', [$data[$i] => $this->current]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testChangePasswordItemsNotSame()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', [
                'current_password' => $this->current,
                'current_password_confirm' => $this->new
            ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testChangePasswordWrongPassword()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', [
                'current_password' => $this->new,
                'current_password_confirm' => $this->new
            ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getData()->code);
    }

    public function testChangePasswordRepeat()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', [
                'current_password' => $this->current,
                'current_password_confirm' => $this->current
            ]);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);

        $response = $this->user_admin->put('api/auth/change-password', [
                'new_password' => $this->current,
                'new_password_confirm' => $this->current
            ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        $this->assertEquals(trans('auth.repeat_password'), $response->getData()->message);
    }

    public function testChangePasswordNotHaveNewParam()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', [
                'current_password' => $this->current,
                'current_password_confirm' => $this->current
            ]);
        $this->assertEquals(Response::HTTP_OK, $response->getData()->code);

        $response = $this->user_admin->put('api/auth/change-password');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testChangePasswordNotHaveNewItems()
    {
        $data = [
            'new_password',
            'new_password_confirm'
        ];
        for ($i = 0; $i < 2; $i++) {
            $response = $this->user_admin->post('api/auth/confirm-password', [
                    'current_password' => $this->current,
                    'current_password_confirm' => $this->current
                ]);
            $this->assertEquals(Response::HTTP_OK, $response->getData()->code);

            $response = $this->user_admin->put('api/auth/change-password', [
                    $data[$i] => $this->new,
                ]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testChangePasswordSuccess()
    {
        $response = $this->user_admin->post('api/auth/confirm-password', [
                'current_password' => $this->current,
                'current_password_confirm' => $this->current
            ]);
        $response->assertStatus(Response::HTTP_OK);

        $response = $this->user_admin->put('api/auth/change-password', [
                'new_password' => $this->new,
                'new_password_confirm' => $this->new
            ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('auth.change_password_successful'), $response->getData()->data);
$response = $this->call('POST','api/auth/login', [
            'mail_address' => $this->mail,
            'password' => $this->new,
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testResetPasswordNotHaveEmail()
    {
        $response = $this->user_admin->post('/api/auth/forget-password');
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testResetPasswordWrongEmail()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->faker->email]);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getData()->code);
    }

    public function testResetPasswordEmailInvalid()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => Str::random(30)]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testResetPasswordItemsNull()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->mail]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('messages.mes.reset_password'), $response->getData()->data);

        $new = '123456789Ut1';
        $token = PasswordReset::query()->where(PasswordReset::EMAIL, $this->mail)->first()->token;
        $data = [
            'mail_address' => $this->mail,
            'token' => $token,
            'new_password' => $new,
            'new_password_confirm' => $new
        ];
        foreach ($data as $key => $value) {
            $response = $this->user_admin->put('/api/auth/password-reset', [$key => $value]);
            $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        }
    }

    public function testResetPasswordWrongToken()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->mail]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('messages.mes.reset_password'), $response->getData()->data);

        $response = $this->user_admin->put('/api/auth/password-reset', [
            'mail_address' => $this->mail,
            'token' => Str::random(50),
            'new_password' => $this->new,
            'new_password_confirm' => $this->new
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testResetPasswordNotSame()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->mail]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('messages.mes.reset_password'), $response->getData()->data);
        $token = PasswordReset::query()->where(PasswordReset::EMAIL, $this->mail)->first()->token;

        $response = $this->user_admin->put('/api/auth/password-reset', [
            'mail_address' => $this->mail,
            'token' => $token,
            'new_password' => $this->new,
            'new_password_confirm' => $this->current
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
    }

    public function testResetPasswordRepeat()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->mail]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('messages.mes.reset_password'), $response->getData()->data);
        $token = PasswordReset::query()->where(PasswordReset::EMAIL, $this->mail)->first()->token;

        $response = $this->withHeaders(['Content-Type' => 'multipart/form-data'])->put('/api/auth/password-reset', [
            'mail_address' => $this->mail,
            'token' => $token,
            'new_password' => $this->current,
            'new_password_confirm' => $this->current
        ]);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getData()->code);
        $this->assertEquals(trans('auth.repeat_password'), $response->getData()->message);
    }

    public function testResetPasswordSuccess()
    {
        $response = $this->user_admin->post('/api/auth/forget-password', ['mail_address' => $this->mail]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('messages.mes.reset_password'), $response->getData()->data);

        $token = PasswordReset::query()->where(PasswordReset::EMAIL, $this->mail)->first()->token;
        $response = $this->user_admin->put('/api/auth/password-reset', [
            'mail_address' => $this->mail,
            'token' => $token,
            'new_password' => $this->new,
            'new_password_confirm' => $this->new
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(trans('auth.change_password_successful'), $response->getData()->data);
        $response = $this->call('POST','api/auth/login', [
            'mail_address' => $this->mail,
            'password' => $this->new,
        ]);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
