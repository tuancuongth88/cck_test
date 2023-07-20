<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InterviewRequest;
use App\Http\Resources\BaseResource;
use App\Http\Resources\InterviewResource;
use App\Repositories\Contracts\InterviewRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InterviewController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(InterviewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/interview",
     *   tags={"Interview"},
     *   summary="List Interview",
     *   operationId="interview_index",
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
     *      enum={"id","entry_code","interview_date","full_name","job_name","status_selection", "status_interview_adjustment"}
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
     *     description="2023-01",
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
     * @return JsonResponse
     */
    public function index(InterviewRequest $request)
    {
        $data = $this->repository->list($request);
        return $this->responseJson(200, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/interview",
     *   tags={"Interview"},
     *   summary="Add new Interview",
     *   operationId="interview_create",
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
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(InterviewRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(200, new InterviewResource($data));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/interview/{id}",
     *   tags={"Interview"},
     *   summary="Detail Interview",
     *   operationId="interview_show",
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
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $item = $this->repository->findOne($id);
            return $this->responseJson(200, new BaseResource($item));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/interview/{id}/setup-calendar",
     *   tags={"Interview"},
     *   summary="setup calendar",
     *   operationId="setup_calendar",
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
     *              "interview_type": 1,
     *              "times": {
     *                  {
     *                      "date": "2030-01-01",
     *                      "start_time": "9",
     *                      "start_time_at": "AM",
     *                      "expected_time": "60"
     *                  }
     *              },
     *          },
     *          @OA\Schema(
     *            required={"interview_type", "times"},
     *            @OA\Property(
     *              property="interview_type",
     *              type="integer",
     *              enum={1, 2}
     *            ),
     *            @OA\Property(
     *              property="times",
     *              type="array",
     *              items={"type": "object"}
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
     * @return JsonResponse
     */
    public function setupCalendar(InterviewRequest $request, $id)
    {
        if (!$this->repository->isOwner([$id])) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        return $this->repository->setupCalendar($request->all(), $id);
    }
    /**
     * @OA\Put(
     *   path="/api/interview/{id}/confirm-calendar",
     *   tags={"Interview"},
     *   summary="confirm calendar",
     *   operationId="confirm_calendar",
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
     *              "confirmed": "yes",
     *              "confirmed_time": "1"
     *          },
     *          @OA\Schema(
     *            required={"confirmed"},
     *            @OA\Property(
     *              property="confirmed",
     *              type="string",
     *              enum={"yes", "no"},
     *            ),
     *            @OA\Property(
     *              property="confirmed_time",
     *              type="integer",
     *              enum={1, 2, 3, 4, 5, 6},
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
     * @return JsonResponse
     */
    public function confirmedCalendar(InterviewRequest $request, $id)
    {
        if (!$this->repository->isOwner([$id])) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        return $this->repository->confirmedCalendar($request->all(), $id);
    }
    /**
     * @OA\Put(
     *   path="/api/interview/{id}/setup-zoom",
     *   tags={"Interview"},
     *   summary="setup zoom",
     *   operationId="setup_zoom",
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
     *              "url_zoom": "abc",
     *              "id_zoom": "123456",
     *              "password_zoom": "123456"
     *          },
     *          @OA\Schema(
     *            required={"url_zoom", "id_zoom", "password_zoom"},
     *            @OA\Property(
     *              property="url_zoom",
     *              type="string",
     *              maximum=50,
     *            ),
     *            @OA\Property(
     *              property="id_zoom",
     *              type="string",
     *              maximum=50,
     *            ),
     *            @OA\Property(
     *              property="password_zoom",
     *              type="string",
     *              maximum=20,
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
     * @return JsonResponse
     */
    public function setupZoom(InterviewRequest $request, $id)
    {
        if (!$this->repository->isOwner([$id])) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        return $this->repository->setupZoom($request->all(), $id);
    }
    /**
     * @OA\Put(
     *   path="/api/interview/{id}/review",
     *   tags={"Interview"},
     *   summary="review",
     *   operationId="review",
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
     *              "reviewed": "yes",
     *              "option": "1",
     *              "goto": "1"
     *          },
     *          @OA\Schema(
     *            required={"reviewed", "option"},
     *            @OA\Property(
     *              property="reviewed",
     *              type="string",
     *              enum={"yes", "no"},
     *            ),
     *            @OA\Property(
     *              property="option",
     *              type="integer",
     *              enum={1, 2, 3},
     *            ),
     *            @OA\Property(
     *              property="goto",
     *              type="integer",
     *              enum={1, 2},
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
     * @return JsonResponse
     */
    public function review(InterviewRequest $request, $id)
    {
        if (!$this->repository->isOwner([$id])) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        return $this->repository->review($request->all(), $id);
    }

    /**
     * @OA\Delete(
     *   path="/api/interview/{id}",
     *   tags={"Interview"},
     *   summary="Delete Interview",
     *   operationId="interview_delete",
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
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }

    /**
     * @OA\post(
     *   path="/api/interview/hide",
     *   tags={"Interview"},
     *   summary="Hide interview",
     *   operationId="interview_hide",
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
    public function hide(InterviewRequest $request)
    {
        $ids = $request->ids;
        if (!$this->repository->isOwner($ids)) {
            return $this->responseJsonError(CODE_NO_ACCESS, trans('messages.mes.permission'));
        }
        $this->repository->hide($ids);

        return $this->responseJson(200, null, trans('messages.mes.delete_success'));
    }
}
