<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\BaseResource;
use App\Models\Company;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

class CompanyController extends Controller
{
    protected $repository;
    public function __construct(CompanyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *   path="/api/company",
     *   tags={"Company"},
     *   summary="List all company",
     *   operationId="company_index_all",
     *   @OA\Response(
     *     response=200,
     *     description="Send request success",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,
     *     "data":{
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": "18",
     *           "middle_classification": "79",
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "status": 1,
     *           "updated_at": 1684925396,
     *           "created_at": 1684925396,
     *           "id": 1
     *     }},
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="field",
     *     in="query",
     *     description="id, company_name, field, updated_at, status",
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
    public function index(Request $request)
    {
        $data = $this->repository->getAll($request);
        return $this->responseJson(CODE_SUCCESS, BaseResource::collection($data));
    }

    /**
     * @OA\Get(
     *   path="/api/company/{id}",
     *   tags={"Company"},
     *   summary="Detail Company",
     *   operationId="company_show",
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
     *      example={"code":200,"data":{
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": "18",
     *           "middle_classification": "79",
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "status": 1,
     *           "updated_at": 1684925396,
     *           "created_at": 1684925396,
     *           "id": 1
     *      }}
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
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show($id)
    {
        try {
            $company = $this->repository->find($id);
            return $this->responseJson(CODE_SUCCESS, new BaseResource($company));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/company",
     *   tags={"Company"},
     *   summary="Create Company",
     *   operationId="create_company",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       example={
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": 18,
     *           "middle_classification": 79,
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "is_create":0,
     *        },
     *      @OA\Schema(
     *        required={
     *           "company_name",
     *           "company_name_jp",
     *           "major_classification",
     *           "middle_classification",
     *           "post_code",
     *           "prefectures",
     *           "municipality",
     *           "number",
     *           "other",
     *           "telephone_number",
     *           "mail_address",
     *           "url",
     *           "job_title",
     *           "full_name",
     *           "full_name_furigana",
     *           "assignee_contact",
     *           "is_create",
     *      },
     *       @OA\Property(property="company_name", format="string"),
     *       @OA\Property(property="company_name_jp", format="string"),
     *       @OA\Property(property="major_classification", format="integer"),
     *       @OA\Property(property="middle_classification", format="integer"),
     *       @OA\Property(property="prefectures", format="string"),
     *       @OA\Property(property="municipality", format="string"),
     *       @OA\Property(property="number", format="string"),
     *       @OA\Property(property="other", format="string"),
     *       @OA\Property(property="telephone_number", format="string"),
     *       @OA\Property(property="mail_address", format="string"),
     *       @OA\Property(property="url", format="string"),
     *       @OA\Property(property="job_title", format="string"),
     *       @OA\Property(property="full_name", format="string"),
     *       @OA\Property(property="full_name_furigana", format="string"),
     *       @OA\Property(property="representative_contact", format="string"),
     *       @OA\Property(property="assignee_contact", format="string"),
     *       @OA\Property(property="is_create", format="integer", enum={0,1}, description="0:validate,1:create"),
     *    )
     *  )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": 18,
     *           "middle_classification": 79,
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "status": 1,
     *           "updated_at": 1685088968,
     *           "created_at": 1685088968,
     *           "id": 1
     *     }}
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":401,"message":"Chưa đăng nhập"}
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CompanyRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(CODE_SUCCESS, new BaseResource($data));
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/company/{id}",
     *   tags={"Company"},
     *   summary="Update Company",
     *   operationId="update_company",
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
     *       example={
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": 18,
     *           "middle_classification": 79,
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "establishment_year" : "2010年",
     *           "startup_year" : "2009年",
     *           "capital" : "1千万円",
     *           "proceeds" : "815億円",
     *           "number_of_staffs" : "7,218名",
     *           "number_of_operations" : "20",
     *           "number_of_shops" : "50",
     *           "number_of_factory" : "50",
     *           "fiscal" : "12月",
     *        },
     *          @OA\Schema(
     *            required={
     *                "company_name",
     *                "company_name_jp",
     *                "major_classification",
     *                "middle_classification",
     *                "post_code",
     *                "prefectures",
     *                "municipality",
     *                "number",
     *                "other",
     *                "telephone_number",
     *                "mail_address",
     *                "url",
     *                "job_title",
     *                "full_name",
     *                "full_name_furigana",
     *                "assignee_contact",
     *            },
     *          @OA\Property(property="company_name", format="string"),
     *          @OA\Property(property="company_name_jp", format="string"),
     *          @OA\Property(property="major_classification", format="integer"),
     *          @OA\Property(property="middle_classification", format="integer"),
     *          @OA\Property(property="prefectures", format="string"),
     *          @OA\Property(property="municipality", format="string"),
     *          @OA\Property(property="number", format="string"),
     *          @OA\Property(property="other", format="string"),
     *          @OA\Property(property="telephone_number", format="string"),
     *          @OA\Property(property="mail_address", format="string"),
     *          @OA\Property(property="url", format="string"),
     *          @OA\Property(property="job_title", format="string"),
     *          @OA\Property(property="full_name", format="string"),
     *          @OA\Property(property="full_name_furigana", format="string"),
     *          @OA\Property(property="representative_contact", format="string"),
     *          @OA\Property(property="assignee_contact", format="string"),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{
     *           "company_name": "City Computer Co., Ltd.",
     *           "company_name_jp": "シティコンピュータ株式会社",
     *           "major_classification": 18,
     *           "middle_classification": 79,
     *           "post_code": "1020093",
     *           "prefectures": "東京都",
     *           "municipality": "千代田区平河町",
     *           "number": "1-7-10",
     *           "other": "大盛丸平河町ビル2階",
     *           "telephone_number": "+84 0312345678",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "job_title": "代表取締役会長",
     *           "full_name": "鈴木　太郎",
     *           "full_name_furigana": "スズキ　タロウ",
     *           "representative_contact": "+84 0312345678",
     *           "assignee_contact": "+84 0312345678",
     *           "status": 1,
     *           "updated_at": 1685088968,
     *           "created_at": 1685088968,
     *           "id": 1
     *     }}
     *     )
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
    public function update(CompanyRequest $request, $id){
        try {
            $request->except(['status', 'mail_address']);
            $data = $this->repository->update($request->toArray(), $id);
            if (!$data){
                return $this->responseJsonError(Response::HTTP_NO_CONTENT, trans('messages.data_does_not_exist'), trans('messages.data_does_not_exist'));
            }
            return $this->responseJson(CODE_SUCCESS, $data);
        }catch (\Exception $e){
            return $this->responseJsonEx($e);
        }
    }

}
