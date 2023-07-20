<?php

namespace Repository;


use App\Mail\ResetPasswordEmail;
use App\Mail\RemindPasswordEmail;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Http\Requests\LoginRequest;
use Helper\ResponseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository implements AuthRepositoryInterface
{
    protected $shopRepository;

    public function __construct()
    {
    }

    /**
     *
     * Handle action login of user.
     *
     * @param LoginRequest $request
     * @param null $guard
     * @return array
     */
    public function doLogin(LoginRequest $request, $guard = null)
    {
        $credentials = [
            'mail_address' => $request->mail_address,
            'password' => $request->password
        ];
        $user = User::with(['hrOrganization', 'company'])->where(User::MAIL_ADDRESS, $credentials['mail_address'])->first();
        if (!$user) {
            return [
                'error' => trans('auth.failed.mail_address'),
                'attempt' => false
            ];
        }
        if ($user->status == CONFIRM){
            $attempt = JWTAuth::attempt($credentials);
            if (!$attempt) {
                return [
                    'error' => trans('auth.failed.password'),
                    'attempt' => false
                ];
            }

            return [
                'user' => $user,
                'attempt' => $attempt
            ];
        }else{
            return [
                'error' => trans('auth.account_reject_or_stop'),
                'attempt' => false
            ];
        }
    }

    /**
     * @param array $params
     * @return bool|void
     */
    public function register(array $params)
    {
        $user = User::create($params);
        $this->grantRoleNewUser($user);

    }

    protected function grantRoleNewUser(User &$user)
    {
        $roleOwnerDefault = array_key_first(config('laratrust_seeder.roles_structure', []));
        $shopOwner = Role::where('name', $roleOwnerDefault)->first();
        $user->attachRole($shopOwner);
    }


    public function update(array $attributes, $id)
    {
        $user = User::find($id);
        if ($user->update($attributes)) {
            return $user;
        }
        return [];
    }

    public function logout()
    {
        try {
            Auth::logout();
            return ResponseService::responseJson(CODE_SUCCESS, null);
        } catch (\Exception $exception) {
            return ResponseService::responseJson(CODE_UNAUTHORIZED, $exception->getMessage());
        }
    }

    public function checkPassword($password)
    {
        $pass = Auth::user()->password;
        if (Hash::check($password, $pass)) {
            return true;
        }
        return false;
    }

    public function changePassword($password)
    {
        $user = Auth::user();
        $user->password = $password;

        if ($user->save()) {
            return $user;
        }
    }

    public function forgetPassword($request)
    {
        $user = User::query()->where(User::MAIL_ADDRESS, $request->mail_address)->firstOrFail();
        $token = Str::random(60);
        $data = [
            'email' => $user->mail_address,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()->format('Y-m-d H:s:i')
        ];
        $object = PasswordReset::query()->where(PasswordReset::EMAIL, $user->mail_address)->get();
        if(count($object) == 0) {
            PasswordReset::query()->create($data);
        } else {
            PasswordReset::query()->where(PasswordReset::EMAIL, $user->mail_address)->update($data);
        }
        Mail::to($user->mail_address)->send(new ResetPasswordEmail($data['token']));
    }

    public function resetPassword($request)
    {
        $email = $request['mail_address'];
        $user = User::query()->where(User::MAIL_ADDRESS, $email)->first();
        if($user) {
            $user->password = $request['new_password'];
            $user->save();
            PasswordReset::where(PasswordReset::EMAIL, $email)->delete();
            return $user;
        }
    }
}
