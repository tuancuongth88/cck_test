<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFavoriteRequest;
use App\Models\UserFavorite;
use App\Repositories\Contracts\UserFavoriteRepositoryInterface;
use App\Http\Resources\BaseResource;
use App\Http\Resources\UserFavoriteResource;
use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{

     /**
     * var Repository
     */
    protected $repository;

    public function __construct(UserFavoriteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/user-favorite",
     *   tags={"UserFavorite"},
     *   summary="List UserFavorite",
     *   operationId="user_favorite_index",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{{"id": 1,"name": "..........."}}}
     *     )
     *   ),
     *     @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="1:hrs, 2:job (required when user is admin)",
     *     @OA\Schema(
     *      type="integer", enum={1, 2}
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
     * @throws \Exception
     */
    public function index(UserFavoriteRequest $request)
    {
        $data = $this->repository->getAll($request);
        return $this->responseJson(200, BaseResource::collection($data));
    }

    /**
     * @OA\Post(
     *   path="/api/user-favorite",
     *   tags={"UserFavorite"},
     *   summary="Add new UserFavorite",
     *   operationId="user_favorite_create",
     *   @OA\Parameter(name="relation_id", description="id of hrs or job table", in="query", required=true,
     *     @OA\Schema(type="integer"),
     *   ),
     *
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="1:hrs, 2:job",
     *     required=true,
     *     @OA\Schema(
     *      type="integer", enum={1, 2}
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{"id": "1","relation_id": "......"}}
     *     )
     *   ),
     *   security={{"auth": {}}},
     * )
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(UserFavoriteRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(200, new UserFavoriteResource($data));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/user-favorite/{id}",
     *   tags={"UserFavorite"},
     *   summary="Detail UserFavorite",
     *   operationId="user_favorite_show",
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
            $department = $this->repository->find($id);
            return $this->responseJson(200, new BaseResource($department));
        } catch (\Exception $e) {
             return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/user-favorite/{id}",
     *   tags={"UserFavorite"},
     *   summary="Update UserFavorite",
     *   operationId="user_favorite_update",
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
    public function update(UserFavoriteRequest $request, $id)
    {
        $attributes = $request->except([]);
        $data = $this->repository->update($attributes, $id);
        return $this->responseJson(200, new BaseResource($data));
    }

    /**
     * @OA\Delete(
     *   path="/api/user-favorite/delete",
     *   tags={"UserFavorite"},
     *   summary="Delete UserFavorite",
     *   operationId="user_favorite_delete",
     *   @OA\Parameter(name="relation_id", in="query", required=true,
     *     @OA\Schema(type="integer"),
     *   ),
     *
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="1:hrs, 2:job",
     *     required=true,
     *     @OA\Schema(
     *      type="integer", enum={1, 2}
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
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(UserFavoriteRequest $request)
    {
        $favorite = UserFavorite::query()
            ->where(UserFavorite::RELATION_ID, $request->relation_id)
            ->where(UserFavorite::USER_ID, auth()->id())
            ->where(UserFavorite::TYPE, $request->type)->first();
        if ($favorite){
            $this->authorize('delete', $favorite);
            $this->repository->delete($favorite->id);
            return $this->responseJson(200, null, trans('messages.mes.delete_success'));
        }
        return $this->responseJsonError(404, trans('errors.id_not_found'), trans('errors.id_not_found'));
    }
}
