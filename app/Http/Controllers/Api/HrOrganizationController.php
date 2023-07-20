<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\HrOrganizationRequest;
use App\Http\Resources\BaseResource;
use App\Models\HrOrganization;
use App\Models\User;
use App\Notifications\SendNotification;
use App\Repositories\Contracts\HrOrganizationRepositoryInterface;
use App\Repositories\Contracts\UploadFileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Repository\UploadFileRepository;

class HrOrganizationController extends Controller
{
    protected $repository;
    protected $uploadFileRepository;

    public function __construct(HrOrganizationRepositoryInterface $repository, UploadFileRepositoryInterface $uploadFileRepository)
    {
        $this->repository = $repository;
        $this->uploadFileRepository = $uploadFileRepository;
    }

    /**
     * @OA\Get(
     *   path="/api/hr-organization",
     *   tags={"Hr-organization"},
     *   summary="List all hr-organization",
     *   operationId="hr_organization_index_all",
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
     *     description="corporate_name_en,corporate_name_ja,license_no,account_classification,country,
                representative_full_name,representative_full_name_furigana,
                representative_contact,assignee_contact,post_code,prefectures,municipality,number,other,
                mail_address,url,certificate_file_id,status,updated_at",
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
     *   path="/api/hr-organization/{id}",
     *   tags={"Hr-organization"},
     *   summary="Detail Hr-organization",
     *   operationId="hr-organization_show",
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
     *          "id": 1,
     *          "user_id": null,
     *          "corporate_name_en": "Công ty Cổ phần phát triển nguồn nhân lực LOD",
     *          "corporate_name_ja": "LOD人材開発株式会社",
     *          "license_no": "12345678",
     *          "account_classification": "1",
     *          "country": "1",
     *          "representative_full_name": "鈴木　一郎",
     *          "representative_full_name_furigana": "スズキ　イチロウ",
     *          "representative_contact": "+84 0112345678",
     *          "assignee_contact": "+84 0187654321",
     *          "post_code": "100-001",
     *          "prefectures": "Hanoi",
     *          "municipality": "Ba Dinh",
     *          "number": "266 Lieu Giay Doi Can",
     *          "other": "鈴木　太郎",
     *          "mail_address": "vu1hoa11052000@gmail.com1",
     *          "url": "https://okuridashi_hanoi.com",
     *          "certificate_file_id": 1,
     *          "status": 1,
     *          "created_at": "2023-06-08T02:58:24.000000Z",
     *          "updated_at": "2023-06-08T02:58:24.000000Z",
     *          "country_info": {
     *              "1": "ベトナム"
     *           }
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
            $hrOrganization = $this->repository->getDetail($id);
            return $this->responseJson(CODE_SUCCESS, $hrOrganization);
        } catch (\Exception $e) {
            return $this->responseJsonEx($e);
        }
    }

    /**
     * @OA\Post(
     *   path="/api/hr-organization",
     *   tags={"Hr-organization"},
     *   operationId="create_hr_organization",
     *   summary="Create Hr",
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *      @OA\Schema(
     *        required={
     *           "corporate_name_en",
     *           "corporate_name_ja",
     *           "license_no",
     *           "account_classification",
     *           "country",
     *           "representative_full_name",
     *           "representative_full_name_furigana",
     *           "assignee_contact",
     *           "post_code",
     *           "prefectures",
     *           "municipality",
     *           "number",
     *           "mail_address",
     *           "url",
     *           "certificate_file_id",
     *           "is_create"
     *      },
     *       @OA\Property(property="corporate_name_en", format="string", example="Công ty Cổ phần phát triển nguồn nhân lực LOD"),
     *       @OA\Property(property="corporate_name_ja", format="string", example="LOD人材開発株式会社"),
     *       @OA\Property(property="license_no", format="string", example="12345678"),
     *       @OA\Property(property="account_classification", format="integer", enum={1, 2, 3, 4}, example=1, description="1:自社プラットフォーム, 2:送り出し期間, 3:派遣会社, 4:学校"),
     *       @OA\Property(property="country", format="integer", example="1", enum={1, 2, 3, 4, 5, 6, 7}, example=1, description="1:ベトナム, 2:ミャンマー, 3:フィリピン, 4:バングラデシュ, 5:ネパール, 6:カンボジア, 7:タイ"),
     *       @OA\Property(property="representative_full_name", format="string", example="鈴木　一郎"),
     *       @OA\Property(property="representative_full_name_furigana", format="string", example="スズキ　イチロウ"),
     *       @OA\Property(property="representative_contact", format="string", example="+84 0112345678"),
     *       @OA\Property(property="assignee_contact", format="string", example="+84 0187654321"),
     *       @OA\Property(property="post_code", format="string", example="1000000"),
     *       @OA\Property(property="prefectures", format="string", example="Hanoi"),
     *       @OA\Property(property="municipality", format="string", example="Ba Dinh"),
     *       @OA\Property(property="number", format="string", example="266 Lieu Giay Doi Can "),
     *       @OA\Property(property="other", format="string", example="鈴木　太郎"),
     *       @OA\Property(property="mail_address", format="string", example="okuridashi_hanoi@gmail.vn"),
     *       @OA\Property(property="url", format="string", example="https://okuridashi_hanoi.com"),
     *       @OA\Property(property="certificate_file_id", type="integer", example="1"),
     *       @OA\Property(property="is_create", type="integer", example="0", description="0:validate,1:create", enum={0,1}),
     *    )
     *  )
     * ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{
     *           "corporate_name_en": "Công ty Cổ phần phát triển nguồn nhân lực LOD",
     *           "corporate_name_ja": "LOD人材開発株式会社",
     *           "license_no": 12345678,
     *           "account_classification": "送り出し機関",
     *           "country": "1",
     *           "representative_full_name": "鈴木　一郎",
     *           "representative_full_name_furigana": "スズキ　イチロウ",
     *           "representative_contact": "+84 0112345678",
     *           "assignee_contact": "+84 0187654321",
     *           "post_code": "1000000",
     *           "prefectures": "Hanoi",
     *           "municipality": "Ba Dinh",
     *           "number": "266 Lieu Giay Doi Can ",
     *           "other": "鈴木　太郎",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "certificate_file_id": "1",
     *       }}
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
    public function store(HrOrganizationRequest $request)
    {
        try {
            $data = $this->repository->create($request->all());
            return $this->responseJson(CODE_SUCCESS, new BaseResource($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @OA\Put(
     *   path="/api/hr-organization/{id}",
     *   tags={"Hr-organization"},
     *   summary="Update Hr-organization",
     *   operationId="update_hr_organization",
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
     *          @OA\Schema(
     *            required={
     *               "corporate_name_en",
     *               "corporate_name_ja",
     *               "license_no",
     *               "account_classification",
     *               "country",
     *               "representative_full_name",
     *               "representative_full_name_furigana",
     *               "assignee_contact",
     *               "post_code",
     *               "prefectures",
     *               "municipality",
     *               "number",
     *               "mail_address",
     *               "url",
     *               "certificate_file_id",
     *            },
     *          @OA\Property(property="corporate_name_en", format="string", example="Công ty Cổ phần phát triển nguồn nhân lực LOD"),
     *          @OA\Property(property="corporate_name_ja", format="string", example="LOD人材開発株式会社"),
     *          @OA\Property(property="license_no", format="string", example="12345678"),
     *          @OA\Property(property="account_classification", format="integer", enum={1, 2, 3, 4}, example=1, description="1:自社プラットフォーム, 2:送り出し期間, 3:派遣会社, 4:学校"),
     *          @OA\Property(property="country", format="integer", example="1", enum={1, 2, 3, 4, 5, 6, 7}, example=1, description="1:ベトナム, 2:ミャンマー, 3:フィリピン, 4:バングラデシュ, 5:ネパール, 6:カンボジア, 7:タイ"),
     *          @OA\Property(property="representative_full_name", format="string", example="鈴木　一郎"),
     *          @OA\Property(property="representative_full_name_furigana", format="string", example="スズキ　イチロウ"),
     *          @OA\Property(property="representative_contact", format="string", example="+84 0112345678"),
     *          @OA\Property(property="assignee_contact", format="string", example="+84 0187654321"),
     *          @OA\Property(property="post_code", format="string", example="1000000"),
     *          @OA\Property(property="prefectures", format="string", example="Hanoi"),
     *          @OA\Property(property="municipality", format="string", example="Ba Dinh"),
     *          @OA\Property(property="number", format="string", example="266 Lieu Giay Doi Can "),
     *          @OA\Property(property="other", format="string", example="鈴木　太郎"),
     *          @OA\Property(property="mail_address", format="string", example="okuridashi_hanoi@gmail.vn"),
     *          @OA\Property(property="url", format="string", example="https://okuridashi_hanoi.com"),
     *          @OA\Property(property="certificate_file_id", type="integer", example="1"),
     *         )
     *      )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Gửi yêu cầu thành công",
     *     @OA\MediaType(
     *      mediaType="application/json",
     *      example={"code":200,"data":{
     *           "id": 1,
     *           "user_id": null,
     *           "corporate_name_en": "Công ty Cổ phần phát triển nguồn nhân lực LOD",
     *           "corporate_name_ja": "LOD人材開発株式会社",
     *           "license_no": 12345678,
     *           "account_classification": "送り出し機関",
     *           "country": 1,
     *           "representative_full_name": "鈴木　一郎",
     *           "representative_full_name_furigana": "スズキ　イチロウ",
     *           "representative_contact": "+84 0112345678",
     *           "assignee_contact": "+84 0187654321",
     *           "post_code": 1000000,
     *           "prefectures": "Hanoi",
     *           "municipality": "Ba Dinh",
     *           "number": "266 Lieu Giay Doi Can",
     *           "other": "鈴木　太郎",
     *           "mail_address": "okuridashi_hanoi@gmail.vn",
     *           "url": "https://okuridashi_hanoi.com",
     *           "certificate_file_id": 1,
     *           "status": 1,
     *           "created_at": "2023-06-08T02:58:24.000000Z",
     *           "updated_at": "2023-06-09T07:16:02.000000Z"
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
    public function update(HrOrganizationRequest $request, $id){
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
