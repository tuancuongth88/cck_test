<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntryRequest;
use App\Repositories\Contracts\EntryRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\EntryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{

    /**
     * var Repository
     */
    protected $repository;

    public function __construct(EntryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/entries",
     *   tags={"Entry"},
     *   summary="List Entry",
     *   operationId="entry_index",
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
    public function index(EntryRequest $request)
    {
        $data = $this->repository->list($request);
        return $this->responseJson(200, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/entry",
     *   tags={"Entry"},
     *   summary="Add new Entry",
     *   operationId="entry_create",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"items"},
     *              @OA\Property(
     *                   description="item type",
     *                   property="items",
     *                   type="object",
     *                   example={
     *                      {
     *                          "work_id": 1,
     *                          "hr_id": 1,
     *                          "remarks": "string",
     *                          "motivation_file_id": 1,
     *                          "recommendation_file_id": "",
     *                          "other_files": {}
     *                      },
     *                      {
     *                          "work_id": 1,
     *                          "hr_id": 1,
     *                          "remarks": "string",
     *                          "motivation_file_id": 5,
     *                          "recommendation_file_id": "",
     *                          "other_files": {}
     *                      },
     *                   }
     *               ),
     *           )
     *       )
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
    public function store(EntryRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(200, new EntryResource($data));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/entry/{id}",
     *   tags={"Entry"},
     *   summary="Detail Entry",
     *   operationId="entry_show",
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
            $department = $this->repository->findOne($id);
            if ($department['status'] != 'success'){
                return $this->responseJsonError($department['code'],$department['message'],$department['message']);
            }
            return $this->responseJson(200, new BaseResource(isset($department['data'])?$department['data']:[]));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/entry/{id}",
     *   tags={"Entry"},
     *   summary="Update Entry",
     *   operationId="entry_update",
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
     *          example={"name":"string"},
     *          @OA\Schema(
     *            required={"name"},
     *            @OA\Property(
     *              property="name",
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
    public function update(EntryRequest $request, $id)
    {
        $attributes = $request->except([]);
        $data = $this->repository->update($attributes, $id);
        return $this->responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Delete(
     *   path="/api/entry/{id}",
     *   tags={"Entry"},
     *   summary="Delete Entry",
     *   operationId="entry_delete",
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
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }

    /**
     * @OA\post(
     *   path="/api/entry/hide",
     *   tags={"Entry"},
     *   summary="Hide Entry",
     *   operationId="entry_hide",
     *     @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"ids"},
     *              @OA\Property(
     *                   property="ids",
     *                   type="array",
     *                   items={"type": "integer"}
     *               ),
     *           )
     *       )
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
    public function hide(EntryRequest $request)
    {
        $result = $this->repository->hide($request->ids);
        if ($result) {
            return $this->responseJson(200, null, trans('messages.mes.delete_success'));
        } else {
            return $this->responseJsonError(HTTP_UNPROCESSABLE_ENTITY, trans('messages.mes.delete_error'), 'messages.mes.delete_error');
        }
    }

    /**
     * @OA\Post(
     *   path="/api/entry/update-status/{id}",
     *   tags={"Entry"},
     *   summary="Update Entry status",
     *   operationId="entry_update_status",
     *       description="status:1[Requesting],2[Reject],3[Decline],4[Confirm]",
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
     *           example={"status":"1","note":"Search Offer"},
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
    public function updateStatus(EntryRequest $request, $id)
    {
        return $this->repository->updateStatus($request->all(), $id);
    }
}
