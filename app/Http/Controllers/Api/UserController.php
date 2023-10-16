<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\HrOrganization;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Repository\CompanyRepository;
use Repository\HrOrganizationRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends BaseController
{
    protected $repository;
    protected $companyRepository;
    protected $hrOrganizationRepository;

    public function __construct(
        UserRepository $repository,
        CompanyRepository $companyRepository,
        HrOrganizationRepository $hrOrganizationRepository
    )
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/user",
     *   tags={"User"},
     *   summary="List user",
     *   operationId="user_index",
     *   @OA\Response(
     *     response=200,
     *     description="response success",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       example={"code":200,"data":{{"id":1,"email":"test@gmail.com","username":"test","name":"abc","created_at":1604982690,"updated_at":1604982690, "role": {{"id": 1, "name": "general_affairs", "display_name": "display_name"}}},{"id":2,"email":"test1@gmail.com","username":"test1","name":"abcd","created_at":1604982690,"updated_at":1604982690, "role": {{"id": 1, "name": "general_affairs", "display_name": "display_name"}}},"pagination":{"display":1,"total_records":1,"per_page":15,"current_page":1,"total_pages":1}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="search",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="order_column",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="order_type",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"token not provided"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Từ chối quyền truy cập",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Từ chối quyền truy cập"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(UserRequest $request)
    {
        $user = $this->repository->getAll($request);
        return $this->responseJson(200, $user);
    }


    /**
     * @OA\Get(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Get detail user",
     *   operationId="user_show",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request Success",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       example={"code":200,"data":{"result":{{"id":1,"email":"example@domain.com","username":"NCC1","phone":null,"created_at":1604910110,"updated_at":1604910680, "role": {{"id": 1, "name": "general_affairs", "display_name": "display_name"}}}}}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"token not provided"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Access deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access deny permission"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $user = $this->repository->with('role')->find($id);
        return $this->responseJson(200, new BaseResource($user));
    }

    /**
     * @OA\Put(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Update user",
     *   operationId="user_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"username":"string","email":"string","name": "string", "password": "string", "role": "int"},
     *          @OA\Schema(
     *            required={"name"},
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
     *              property="password",
     *              format="string",
     *            ),
     *            @OA\Property(
     *              property="role",
     *              format="interge"
     *            ),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *       example={"code":200,"data":{"result":{{"id":1,"email":"example@domain.com","username":"NCC1", "role": {{"id": 1, "name": "general_affairs", "display_name": "display_name"}}, "created_at":1604910110,"updated_at":1604910680}}}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"token not provided"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Access deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access deny permission"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->repository->update($request->all(), $id);
        return $this->responseJson(200, new BaseResource($user));
    }

    /**
     * @OA\Post(
     *   path="/api/user",
     *   tags={"User"},
     *   summary="Add new User",
     *   operationId="User_create",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"username":"string","email":"string", "name": "string", "password": "string", "role": 1},
     *          @OA\Schema(
     *            required={"name"},
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
     *              property="password",
     *              format="string",
     *            ),
     *            @OA\Property(
     *              property="role",
     *              format="integer"
     *            ),
     *         )
     *      )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(UserRequest $request)
    {
        $user = $this->repository->create($request->all());
        return $this->responseJson(CODE_SUCCESS, new BaseResource($user));
    }

    /**
     * @OA\Delete(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Delete ..............",
     *   operationId="user_delete",
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request Success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Send request Success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->responseJson(CODE_SUCCESS, null, trans('messages.mes.delete_success'));
    }

    /**
     * @OA\DELETE(
     *   path="/api/user/destroyMany",
     *   tags={"User"},
     *   summary="Delete muntiple user",
     *   operationId="user_delete_many",
     *
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"id":{"0": "3","1": "4"}},
     *
     *          @OA\Schema(
     *            required={"id"},
     *            @OA\Property(
     *              property="id",
     *              format="string",
     *              example="'Bridge Balti','53.7094190','-1.9084720',1"
     *            ),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Delete success",
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"token not provided"}
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Access deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access deny permission"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroyMany(UserRequest $request)
    {

        $ids = $request->id;
        User::whereIn('id', explode(",", implode(",", $ids)))->delete();
        return $this->responseJson(CODE_SUCCESS, null, trans('messages.mes.delete_success'));
    }

    /**
     * @OA\Post(
     *   path="/api/update-status-company",
     *   tags={"User"},
     *   summary="Company register",
     *   operationId="company_register",
     *   @OA\Parameter(
     *     name="company_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="Status:
     *     - `1`: EXAMINATION_PENDING
     *     - `2`: CONFIRM
     *     - `3`: REJECT
     *     - `4`: STOP_USING",
     *     @OA\Schema(
     *      ref="#/components/schemas/UserStatus",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateStatusCompany(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->repository->updateStatusCompany($request);
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(),$e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *   path="/api/update-status-hr",
     *   tags={"User"},
     *   summary="Hr organization register",
     *   operationId="hr_register",
     *   @OA\Parameter(
     *     name="hr_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="Status:
     *     - `1`: EXAMINATION_PENDING
     *     - `2`: CONFIRM
     *     - `3`: REJECT
     *     - `4`: STOP_USING",
     *     @OA\Schema(
     *      ref="#/components/schemas/UserStatus",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateStatusHR(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        try{
            return $this->repository->updateStatusHR($request);
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *   path="/api/notify",
     *   tags={"User"},
     *   summary="Get notify",
     *   operationId="notify_list",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getNotify(Request $request)
    {
        try{
            return $this->repository->getNotify($request);
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(), $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *   path="/api/user/on-going-job",
     *   tags={"User"},
     *   summary="Get on going job",
     *   operationId="On going job_list",
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function onGoingJob(Request $request){
        try{
            $data =  $this->repository->onGoingJob($request);
            if ($data['status']!='success'){
                return $this->responseJsonError($data['code'],$data['message'],$data['message']);
            }
            return $this->responseJson($data['code'],isset($data['data'])?$data['data']:[]);

        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *   path="/api/user/unread-messages",
     *   tags={"User"},
     *   summary="Getunread-messages",
     *   operationId="On unread-messages",
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function unreadMessages(){
        $data =  $this->repository->unreadMessages();
        return $this->responseJson($data['code'],isset($data['data'])?$data['data']:[]);
    }
}
