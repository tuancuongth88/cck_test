<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResultRequest;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ResultResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(ResultRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/result",
     *   tags={"Result"},
     *   summary="List Result",
     *   operationId="result_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *      enum={"id","entry_code","full_name","work_title","request_date","updated_at","status"}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="sort_by",
     *     in="query",
     *     @OA\Schema(
     *      type="string",
     *      enum={"asc", "desc"}
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
     *   @OA\Parameter(
     *     name="hr_org_id",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="gender[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="age_from",
     *     in="query",
     *     description="example:20",
     *     @OA\Schema(
     *      type="integer"
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="age_to",
     *     in="query",
     *     description="example:40",
     *     @OA\Schema(
     *      type="integer"
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="edu_date_from",
     *     in="query",
     *     description="example:2023-01",
     *     @OA\Schema(
     *      type="string"
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="edu_date_to",
     *     in="query",
     *     description="example:2023-01",
     *     @OA\Schema(
     *      type="string"
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="edu_class[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="edu_degree[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="work_forms[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="work_hour",
     *     in="query",
     *     @OA\Schema(
     *      type="boolean"
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="japan_levels[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer", "enum":{1, 2, 3, 4, 5, 6}}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="middle_class[]",
     *     in="query",
     *     description="job info ids",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer"},
     *      example={1}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="main_job_ids[]",
     *     in="query",
     *     @OA\Schema(
     *      type="array",
     *      items={"type":"integer"},
     *      example={1}
     *     ),
     *   ),
     *   @OA\Parameter(
     *     name="search",
     *     in="query",
     *     @OA\Schema(
     *      type="string"
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
    public function index(ResultRequest $request)
    {
        $data = $this->repository->list($request);
        return $this->responseJson(200, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/result",
     *   tags={"Result"},
     *   summary="Add new Result",
     *   operationId="result_create",
     *   @OA\Parameter(name="name", in="query", required=true,
     *     @OA\Schema(type="string"),
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
    public function store(ResultRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(200, new ResultResource($data));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/result/{id}",
     *   tags={"Result"},
     *   summary="Detail Result",
     *   operationId="result_show",
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
            $result = $this->repository->detail($id);
            if ($result['status'] != 'success'){
                return $this->responseJsonError($result['code'],$result['message'],$result['message']);
            }
            return $this->responseJson(CODE_SUCCESS, new BaseResource(isset($result['data'])?$result['data']:[]));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/result/{id}",
     *   tags={"Result"},
     *   summary="Update Result",
     *   operationId="result_update",
     *   description="status:1 Official offer,3 Official offer confirm,4 Official offer decline. ",
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
     *           example={"status":"1","note":"Note reason reject", "hire_date": "2023-07-17", "time": "20230720"},
     *          @OA\Schema(
     *            required={"name"},
     *            @OA\Property(
     *              property="status",
     *              format="string",
     *            ),
     *         )
     *      )
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
    public function update(ResultRequest $request, $id)
    {
        $attributes = $request->except([]);
        return $this->repository->update($attributes, $id);
    }

    /**
     * @OA\post(
     *   path="/api/result/hide",
     *   tags={"Result"},
     *   summary="Hide Result",
     *   operationId="result_hide",
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
     * @return JsonResponse
     * @throws \Exception
     */
    public function hide(ResultRequest $request)
    {
        $ids = $request->ids;
        if (!$this->repository->isOwner($ids)) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        $this->repository->hide($ids);

        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }
}
