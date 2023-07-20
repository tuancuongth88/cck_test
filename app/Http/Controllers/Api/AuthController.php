<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Repository\AuthRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    protected $authRepository;
    protected $userRepository;

    public function __construct(AuthRepositoryInterface $authRepository, UserRepositoryInterface $userRepository)
    {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Auth"},
     *   summary="User Login",
     *   operationId="user_login",
     *   @OA\Parameter(
     *     name="mail_address",
     *     in="query",
     *     description="Mail Address",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *     example="1okuridashi_hanoi@gmail.vn",
     *   ),
     *   @OA\Parameter(
     *     name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *     example="123456789CCk",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"access_token":"Bearer ...","profile":{"id":1,"login_id":111111,"type":1, "status":2}}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Đăng nhập thất bại",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Login_id or password not correct"}
     *     )
     *   ),
     *   security={},
     * )
     * Display a listing of the resource.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $loginResult = $this->authRepository->doLogin($request);
        if ($loginResult['attempt']) {
            $user = $loginResult['user'];
            return $this->responseJson(Response::HTTP_OK, [
                'access_token' => "Bearer " . $loginResult['attempt'],
                'profile' => new UserResource($user)
            ]);
        }
        return $this->responseJsonError(Response::HTTP_UNAUTHORIZED, $loginResult['error']);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/forget-password",
     *   tags={"Auth"},
     *   summary="Forget password",
     *   operationId="forget_password",
     *   @OA\Parameter(
     *     name="mail_address",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Password reset URL has been sent to your email address"}
     *     )
     *   ),
     *   security={},
     * )
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function forgetPassword(ResetPasswordRequest $request) {
        try {
            $this->authRepository->forgetPassword($request);
            return $this->responseJson(CODE_SUCCESS, trans('messages.mes.reset_password'));
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *   path="/api/auth/password-reset",
     *   tags={"Auth"},
     *   summary="Reset password",
     *   operationId="reset_password",
     *   @OA\Parameter(
     *     name="token",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="mail_address",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="new_password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="new_password_confirm",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Gửi yêu cầu thành công"}
     *     )
     *   ),
     *   security={},
     * )
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function resetPassword(ResetPasswordRequest $request) {
        $data = $this->authRepository->resetPassword($request->except(['new_password_confirm']));
        if($data) {
            return $this->responseJson(CODE_SUCCESS, trans('auth.change_password_successful'));
        }
    }

    /**
     * @OA\Post(
     *   path="/api/auth/refresh",
     *   tags={"Auth"},
     *   summary="User register",
     *   operationId="user_reset_token",
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"access_token":"...."}}
     *     )
     *   ),
     *   security={},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function refresh()
    {
        return $this->responseJson(200, ['access_token' => auth()->refresh()]);
    }

    /**
     * @OA\Get(
     *   path="/api/profile",
     *   tags={"Auth"},
     *   summary="Get Profile",
     *   operationId="user_profile",
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1, "name":"abc","email":"abc@gmail.com","phone":"0988737723","address":"Dia chi"}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Đăng nhập thất bại",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Sai tài khoản hoặc mật khẩu"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile(){
        return $this->responseJson(200, auth()->user());
    }



    /**
     * @OA\Put(
     *   path="/api/profile",
     *   tags={"Auth"},
     *   summary="Update Profile",
     *   operationId="update_user_profile",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"username":"string","email":"string","phone":"string", "name": "string", "password": "string", "fax": "string", "address": "string"},
     *          @OA\Schema(
     *            required={"username"},
     *            @OA\Property(
     *              property="username",
     *              format="string",
     *            ),
     *            @OA\Property(
     *              property="email",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="name",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="phone",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="password",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="fax",
     *              format="string",
     *            ),
     *           @OA\Property(
     *              property="address",
     *              format="string",
     *            ),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":  "............."}}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Từ chối quyền truy cập",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request){
        $data = $this->authRepository->update($request->all(), Auth::id());
        return $this->responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Get (
     *   path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="logout",
     *   operationId="user_logout",
     *   @OA\Response(
     *     response=200,
     *     description="send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"message":"success"}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="access denied  permissions",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        return $this->authRepository->logout();
    }

    /**
     * @OA\Post (
     *   path="/api/auth/confirm-password",
     *   tags={"Auth"},
     *   summary="Confirm password",
     *   operationId="confirm_password",
     *   @OA\Parameter(
     *     name="current_password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="current_password_confirm",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200, "data": ""}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="password not correct",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401, "data": "Password not correct."}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="access denied  permissions",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPassword(ChangePasswordRequest $request) {
        $data = $this->authRepository->checkPassword($request->current_password);
        if($data) {
            return $this->responseJson(CODE_SUCCESS, '');
        }
        return $this->responseJson(CODE_UNAUTHORIZED, trans('auth.confirm_password'));
    }

    /**
     * @OA\Put (
     *   path="/api/auth/change-password",
     *   tags={"Auth"},
     *   summary="Change password",
     *   operationId="change_password",
     *   @OA\Parameter(
     *     name="new_password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="new_password_confirm",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200, "data": "New password reset completed"}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="access denied  permissions",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request) {
        $data = $this->authRepository->changePassword($request->new_password);
        if($data) {
            return $this->responseJson(CODE_SUCCESS, trans('auth.change_password_successful'));
        }
    }
}

