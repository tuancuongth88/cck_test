<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\BaseResource;
use App\Models\DataConnection;
use App\Repositories\Contracts\UploadFileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Repository\UploadFileRepository;

class UploadFileController extends Controller
{
    protected $repository;
    public function __construct(UploadFileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     *   path="/api/upload",
     *   tags={"Upload File"},
     *   summary="Add new file",
     *   operationId="upload_file_create",
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"file"},
     *              @OA\Property(
     *                   description="file to upload",
     *                   property="file",
     *                   type="string",
     *                   format="binary",
     *               ),
     *              @OA\Property(
     *                   description="model file",
     *                   property="model_file",
     *                   type="string",
     *               ),
     *              @OA\Property(
     *                   description="item type",
     *                   property="type",
     *                   type="integer",
     *                   enum={1,2,3}
     *               ),
     *           )
     *       )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": 1,"file_name": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @param UploadFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UploadFileRequest $request)
    {
        try {
            $data = $this->repository->upload($request);
            return $this->responseJson(200, new BaseResource($data));
        } catch (\Exception $e) {
            return $this->responseJsonError(500, $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFidelity($id){
        try {
            return $this->repository->downloadFidelity($id);
        } catch (\Exception $e) {
            return $this->responseJsonError(500, $e->getMessage());
        }
    }
}
