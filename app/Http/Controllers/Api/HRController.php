<?php

/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Exports\HrExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRRequest;
use App\Http\Requests\UploadFileRequest;
use App\Models\UploadFile;
use App\Models\UserFavorite;
use App\Repositories\Contracts\HRRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\HRResource;
use Carbon\Carbon;
use Helper\Common;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class HRController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(HRRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/hr",
     *   tags={"HR"},
     *   summary="List HR",
     *   operationId="h_r_index",
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
     *      enum={"id","full_name","date_of_birth","japanese_level","status","corporate_name_en"}
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
     *     description="1: university or more,2: aside from university",
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
    public function index(HRRequest $request)
    {
        $data = $this->repository->list($request);
        return $this->responseJson(200, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/hr",
     *   tags={"HR"},
     *   summary="Add new HR",
     *   operationId="h_r_create",
     *   @OA\RequestBody(
     *      ref="#/components/requestBodies/CreateHr",
     *  ),
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
    public function store(HRRequest $request)
    {
        try {
            return $this->repository->create($request->all());
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/hr/{id}",
     *   tags={"HR"},
     *   summary="Detail HR",
     *   operationId="h_r_show",
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
            $item = $this->repository->with(['entries', 'offers', 'interviews', 'results', 'fileCV', 'fileJob'])->find($id);
            $item['is_favorite'] = UserFavorite::isFavorite($item->id, FAVORITE_TYPE_HRS);
            $item->load('mainJobs');
            return $this->responseJson(CODE_SUCCESS, new BaseResource($item));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/hr/{id}",
     *   tags={"HR"},
     *   summary="Update HR",
     *   operationId="h_r_update",
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
     *          example={
     *               "country_id": "1",
     *               "hr_organization_id": "1",
     *               "full_name": "avb",
     *               "full_name_ja": "abb",
     *               "gender": "",
     *               "date_of_birth": "2000-09-09",
     *               "work_form": "1",
     *               "preferred_working_hours": "",
     *               "japanese_level": "1",
     *               "final_education_date": "2020-10",
     *               "final_education_classification": "1",
     *               "final_education_degree": "1",
     *               "major_classification_id": "1",
     *               "middle_classification_id": "1",
     *               "main_jobs": {
     *                   {
     *                       "main_job_career_date_from": "2021-01",
     *                       "to_now": "no",
     *                       "main_job_career_date_to": "2021-02",
     *                       "department_id": "1",
     *                       "job_id": "1",
     *                       "detail": "gsfdfg"
     *                   }
     *               },
     *               "personal_pr_special_notes": "",
     *               "remarks": "",
     *               "telephone_number": "",
     *               "mobile_phone_number": "",
     *               "mail_address": "",
     *               "address_city": "",
     *               "address_district": "",
     *               "address_number": "",
     *               "address_other": "",
     *               "hometown_city": "",
     *               "home_town_district": "",
     *               "home_town_number": "",
     *               "home_town_other": ""
     *           },
     *          @OA\Schema(
     *            required={"hr_organization_id"},
     *            @OA\Property(
     *              property="hr_organization_id",
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
    public function update(HRRequest $request, $id)
    {
        $attributes = $request->except([]);
        $item = $this->repository->find($id);
        $this->authorize('update', $item);
        $data = $this->repository->update($attributes, $id);
        return $this->responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Put(
     *   path="/api/hr/update-file/{id}",
     *   tags={"HR"},
     *   summary="Update File HR",
     *   operationId="h_r_update_file",
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
     *          example={
     *               "file_cv_id": "1",
     *               "file_job_id": "1",
     *               "file_others": {
     *                   {
     *                       "name": "File 1",
     *                       "file_id": "1",
     *                       "file_path": "20230620/8bb8841e588d38a7d2052a7e8337ffddcck.pdf",
     *                   },
     *                   {
     *                       "name": "File 2",
     *                       "file_id": "2",
 *                            "file_path": "20230620/8bb8841e588d38a7d2052a7e8337ffddcck.pdf",
     *                   },
     *               },
     *           },
     *          @OA\Schema(
     *            required={"hr_organization_id"},
     *            @OA\Property(
     *              property="hr_organization_id",
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
    public function updateFileHR(HRRequest $request, $id){
        $item = $this->repository->find($id);
        $this->authorize('updateFileHR', $item);
        $data = $this->repository->updateFileHR($request->toArray(), $id);
        return $this->responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Delete(
     *   path="/api/h-r/{id}",
     *   tags={"HR"},
     *   summary="Delete HR",
     *   operationId="h_r_delete",
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
     * @OA\Post(
     *   path="/api/hr/download",
     *   tags={"HR"},
     *   summary="Download File HR",
     *   operationId="hr_download_file",
     *   @OA\Response(
     *     response=200,
     *     description="Send request\ success",
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
     *      enum={"id","full_name","date_of_birth","japanese_level","status","corporate_name_en"}
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
     *     name="gender",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
     *      enum={1, 2}
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
    public function download(HRRequest $request)
    {
        return $this->repository->list($request, true, true);
    }

    /**
     * @OA\Post(
     *   path="/api/hr/check-file-import",
     *   tags={"HR"},
     *   summary="Check file import hr",
     *   operationId="hr_check_file_import",
     *   @OA\Parameter(
     *     name="file_id",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
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
    public function checkFileImport(HRRequest $request)
    {
        try {
            $data = $this->repository->checkFileImport($request);
            if ($data === false) {
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.something_error'));
            }
            if(isset($data['error_file'])) {
                return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, $data['error_file']);
            }
            return $this->responseJson(Response::HTTP_OK, $data);
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/hr/import",
     *   tags={"HR"},
     *   summary="Import hr",
     *   operationId="hr_import_file",
     *   @OA\Parameter(
     *     name="file_id",
     *     in="query",
     *     @OA\Schema(
     *      type="integer",
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
    public function importFile(HRRequest $request)
    {
        try {
            $result = $this->repository->importCSV($request);
            if ($result){
                return $this->responseJson(Response::HTTP_OK, $result, trans('messages.mes.import_success'));
            }
            return $this->responseJsonError(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.something_error'));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }
    /**
     * @OA\Post(
     *   path="/api/hr/hide",
     *   tags={"HR"},
     *   summary="Delete HR",
     *   operationId="HRr_delete",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          example={"ids":"[1,2,3,4,5,6]"},
     *          @OA\Schema(
     *            required={"name"},
     *            @OA\Property(
     *              property="ids",
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
     *      example={"code":200,"data":"Send request success"}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function hide(HRRequest $request)
    {
        $result = $this->repository->hide($request);
        if ($result){
            return $this->responseJson(200, null, trans('messages.mes.delete_success'));
        }else{
            return $this->responseJsonError(HTTP_UNPROCESSABLE_ENTITY, trans('messages.mes.delete_error'),'messages.mes.delete_error');
        }
    }
}
