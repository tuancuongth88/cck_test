<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;

class CityController extends Controller
{
    /**
     * var Repository
     */
    protected $repository;

    public function __construct(CityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/city",
     *   tags={"City"},
     *   summary="List City",
     *   operationId="city_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name_en": "Tokyo", "name_ja": "Tokyo"}}}
     *     )
     *   ),
     *   security={},
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->repository->getAll();
        return $this->responseJson(CODE_SUCCESS, new BaseResource($data));
    }
}
