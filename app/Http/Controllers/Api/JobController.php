<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Http\Resources\BaseResource;
use App\Repositories\Contracts\JobTypeRepositoryInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(JobTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/job-type",
     *   tags={"Job"},
     *   summary="List required for search for job & HR",
     *   operationId="job_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name_en": "Tokyo", "name_ja": "Tokyo"}}}
     *     )
     *   ),
     *    @OA\Parameter(
     *     name="type",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *      type="integer",
     *      enum={1, 2}
     *     ),
     *   ),
     *   security={},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(JobRequest $request)
    {
        $data = $this->repository->getAll($request->type);
        return $this->responseJson(CODE_SUCCESS, new BaseResource($data));
    }
}
