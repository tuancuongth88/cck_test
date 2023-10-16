<?php

/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Models\Work;
use App\Repositories\Contracts\WorkRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\WorkResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(WorkRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/work",
     *   tags={"Work"},
     *   summary="List Work",
     *   operationId="work_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="company_id",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="middle_classification_id[]",
     *     in="query",
     *     @OA\Schema(
     *      type="object",
     *      example={1, 2, 3},
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="income_from",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="income_to",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="city_id[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4, 5, 6}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="language_requirements[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4, 5, 6}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="form_of_employment[]",
     *     in="query",
     *     description="Status:
     *     - `1`: Full time
     *     - `2`: Contract employee
     *     - `3`: Temporary employee
     *     - `4`: others",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="passion_works[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4, 5, 6, 7, 8, 9, 10}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="key_search",
     *     in="query",
     *     description="search: title, company_name",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="display",
     *     in="query",
     *     description="home,offer,entry",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="hrs_id",
     *     in="query",
     *     description="id hrs offer",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     in="query",
     *     description="title,company_name,status, updated_at",
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sort_by",
     *     in="query",
     *     description="asc,desc",
     *     @OA\Schema(
     *      type="string",
     *      items={"type":"string", "enum":{"asc", "desc"}}
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
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(WorkRequest $request)
    {
        $data = $this->repository->list($request);
        return $this->responseJson(CODE_SUCCESS, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/work",
     *   tags={"Work"},
     *   summary="Add new Work",
     *   operationId="work_create",
     *   @OA\RequestBody(
     *       ref="#/components/requestBodies/CreateWork",
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
    public function store(WorkRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(CODE_SUCCESS, new WorkResource($data));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/work/{id}",
     *   tags={"Work"},
     *   summary="Detail Work",
     *   operationId="work_show",
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
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":"......"}}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Login false",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Username or password invalid"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $this->authorize('show', Work::find($id));
            $work = $this->repository->detail($id);
            if ($work['status'] != 'success') {
                   return $this->responseJsonError($work['code'],$work['message'],$work['message']);
            }
            return $this->responseJson(CODE_SUCCESS, new BaseResource($work));
        } catch (\Exception $e) {
            return $this->responseJsonError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @OA\Post (
     *   path="/api/work/{id}",
     *   tags={"Work"},
     *   summary="Update Work",
     *   operationId="work_update",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *       ref="#/components/requestBodies/CreateWork",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"name":  "............."}}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Access Deny permission",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":403,"message":"Access Deny permission"}
     *     ),
     *   ),
     *   security={{"auth": {}}},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(WorkRequest $request, $id)
    {
        $request->except('status');
        $work = $this->repository->find($id);
        $this->authorize('update', $work);
        $data = $this->repository->update($request->toArray(), $id);
        return $this->responseJson(CODE_SUCCESS, new BaseResource($data), trans('messages.mes.update_success'));
    }

    /**
     * @OA\Delete(
     *   path="/api/work",
     *   tags={"Work"},
     *   summary="Delete Work",
     *   operationId="work_delete",
     *   @OA\Parameter(
     *     name="ids[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer"},
     *      example={1}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":"Send request success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        foreach ($ids as $id) {
            $user = $this->repository->find($id);
            $this->authorize('delete', $user);
            $this->repository->delete($id);
        }
        return $this->responseJson(CODE_SUCCESS, null, trans('messages.mes.delete_success'));

    }

    /**
     * @OA\Post(
     *   path="/api/work/update-status-work/{id}",
     *   tags={"Work"},
     *   summary="Update status work",
     *   operationId="update_status_work",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *      type="string",
     *     ),
     *   ),
     *   @OA\RequestBody(
     *     request="CreateWork",
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *           @OA\Schema(
     *             type="object",
     *             title="WorkUpdateStatus",
     *             required={"status"},
     *             @OA\Property(
     *              property="status",
     *              format="interge",
     *              enum={1,2},
     *              description="choose status: 1: Recruiting, 2: Paused, 3: Out of Recruitment period",
     *             ),
     *           ),
     *     ),
     *   ),
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
    public function updateStatusWork(WorkRequest $request, $id)
    {
        try {
            $work = $this->repository->updateStatusWork($request, $id);
            if ($work){
                return $this->responseJson(CODE_SUCCESS, new BaseResource($work), trans('messages.mes.update_success'));
            }
            return $this->responseJsonError(HTTP_UNPROCESSABLE_ENTITY, trans('api.work.update.status'));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

}
