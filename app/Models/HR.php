<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * @OA\Schema(
 *     type="object",
 *     title="HR",
 *     required={
 *          "country_id",
 *          "hr_organization_id",
 *          "full_name",
 *          "full_name_ja",
 *          "date_of_birth",
 *          "preferred_working_hours",
 *          "japanese_level",
 *          "final_education_date",
 *          "final_education_classification",
 *          "final_education_degree",
 *          "major_classification_id",
 *          "middle_classification_id",
 *          "mail_address"
 *     },
 *     @OA\Property(
 *      property="country_id",
 *      format="integer",
 *      enum={1, 2, 3, 4, 6, 7},
 *     ),
 *     @OA\Property(
 *      property="hr_organization_id",
 *      format="integer"
 *     ),
 *     @OA\Property(
 *      property="full_name",
 *      format="string"
 *     ),
 *     @OA\Property(
 *      property="full_name_ja",
 *      format="string"
 *     ),
 *     @OA\Property(
 *      property="gender",
 *      type="string",
 *      enum={"1", "2"}
 *     ),
 *     @OA\Property(
 *      property="date_of_birth",
 *      type="date",
 *      description="YYYY-mm-dd",
 *     ),
 *     @OA\Property(
 *      property="work_form",
 *      format="integer",
 *      enum={1, 2, 3, 4},
 *     ),
 *     @OA\Property(
 *      property="preferred_working_hours",
 *      format="integer",
 *      default=8,
 *     ),
 *     @OA\Property(
 *      property="japanese_level",
 *      type="string",
 *      enum={"1", "2", "3", "4", "5", "6"}
 *     ),
 *     @OA\Property(
 *      property="final_education_date",
 *      type="string",
 *      description="YYYY-m",
 *     ),
 *     @OA\Property(
 *      property="final_education_classification",
 *      type="integer",
 *      enum={1, 2, 3}
 *     ),
 *     @OA\Property(
 *      property="final_education_degree",
 *      type="integer",
 *      enum={1, 2, 3, 4, 5, 6}
 *     ),
 *     @OA\Property(
 *      property="major_classification_id",
 *      type="integer"
 *     ),
 *     @OA\Property(
 *      property="middle_classification_id",
 *      type="integer"
 *     ),
 *     @OA\Property(
 *      property="main_jobs",
 *      type="object",
 *          example={
 *              {
 *               "main_job_career_date_from": "2021-1",
 *               "to_now": "no",
 *               "main_job_career_date_to": "2021-2",
 *               "department_id": "1",
 *               "job_id": "1",
 *               "detail": "gsfdfg"
 *              }
 *           }
 *     ),
 *     @OA\Property(
 *      property="personal_pr_special_notes",
 *      type="integer"
 *     ),
 *     @OA\Property(
 *      property="remarks",
 *      type="string",
 *      maximum=2000
 *     ),
 *     @OA\Property(
 *      property="telephone_number",
 *      type="string"
 *     ),
 *     @OA\Property(
 *      property="mobile_phone_number",
 *      type="string"
 *     ),
 *     @OA\Property(
 *      property="mail_address",
 *      type="string"
 *     ),
 *     @OA\Property(
 *      property="address_city",
 *      type="string"
 *     ),
 *     @OA\Property(
 *      property="address_district",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="address_number",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="address_other",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="hometown_city",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="home_town_district",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="home_town_number",
 *      type="string",
 *      maximum=50
 *     ),
 *     @OA\Property(
 *      property="home_town_other",
 *      type="string",
 *      maximum=50
 *     ),
 *
 * )
 *
 * @OA\RequestBody(
 *   request="CreateHr",
 *   required=true,
 *   description="hr_request",
 *   @OA\MediaType(
 *      mediaType="multipart/form-data",
 *      @OA\Schema(
 *          allOf={
*               @OA\Schema(ref="#/components/schemas/HR"),
 *          }
 *      ),
 *   ),
 * )
*/
class HR extends Model
{
    use HasFactory, Searchable;
    use SoftDeletes;
    const COUNTRY_ID = 'country_id';
    const COUNTRY_NAME = 'country_name';
    const HR_ORGANIZATION_ID = 'hr_organization_id';
    const USER_ID = 'user_id';
    const FULL_NAME = 'full_name';
    const FULL_NAME_JA = 'full_name_ja';
    const GENDER = 'gender';
    const DATE_OF_BIRTH = 'date_of_birth';
    const WORK_FORM = 'work_form';
    const PREFERRED_WORKING_HOURS = 'preferred_working_hours';
    const JAPANESE_LEVEL = 'japanese_level';
    const FINAL_EDUCATION_DATE = 'final_education_date';
    const FINAL_EDUCATION_CLASSIFICATION = 'final_education_classification';
    const FINAL_EDUCATION_DEGREE = 'final_education_degree';
    const MAJOR_CLASSIFICATION_ID = 'major_classification_id';
    const MIDDLE_CLASSIFICATION_ID = 'middle_classification_id';
    const PERSONAL_PR_SPECIAL_NOTES = 'personal_pr_special_notes';
    const REMARKS = 'remarks';
    const TELEPHONE_NUMBER = 'telephone_number';
    const MOBILE_PHONE_NUMBER = 'mobile_phone_number';
    const MAIL_ADDRESS = 'mail_address';
    const ADDRESS_CITY = 'address_city';
    const ADDRESS_DISTRICT = 'address_district';
    const ADDRESS_NUMBER = 'address_number';
    const ADDRESS_OTHER = 'address_other';
    const HOMETOWN_CITY = 'hometown_city';
    const HOME_TOWN_DISTRICT = 'home_town_district';
    const HOME_TOWN_NUMBER = 'home_town_number';
    const HOME_TOWN_OTHER = 'home_town_other';
    const FILE_CV_ID = 'file_cv_id';
    const FILE_JOB_ID = 'file_job_id';
    const FILE_OTHERS = 'file_others';
    const STATUS = 'status';
    const CREATED_BY = 'created_by';
    const UPDATED_BY = 'updated_by';

    protected $table = 'hrs';

    protected $fillable = [
        self::COUNTRY_ID,
        self::COUNTRY_NAME,
        self::HR_ORGANIZATION_ID,
        self::USER_ID,
        self::FULL_NAME,
        self::FULL_NAME_JA,
        self::GENDER,
        self::DATE_OF_BIRTH,
        self::WORK_FORM,
        self::PREFERRED_WORKING_HOURS,
        self::JAPANESE_LEVEL,
        self::FINAL_EDUCATION_DATE,
        self::FINAL_EDUCATION_CLASSIFICATION,
        self::FINAL_EDUCATION_DEGREE,
        self::MAJOR_CLASSIFICATION_ID,
        self::MIDDLE_CLASSIFICATION_ID,
        self::PERSONAL_PR_SPECIAL_NOTES,
        self::REMARKS,
        self::TELEPHONE_NUMBER,
        self::MOBILE_PHONE_NUMBER,
        self::MAIL_ADDRESS,
        self::ADDRESS_CITY,
        self::ADDRESS_DISTRICT,
        self::ADDRESS_NUMBER,
        self::ADDRESS_OTHER,
        self::HOMETOWN_CITY,
        self::HOME_TOWN_DISTRICT,
        self::HOME_TOWN_NUMBER,
        self::HOME_TOWN_OTHER,
        self::FILE_CV_ID,
        self::FILE_JOB_ID,
        self::FILE_OTHERS,
        self::STATUS,
        self::CREATED_BY,
        self::UPDATED_BY
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function toSearchableArray()
    {
        return [
            'full_name' => $this->full_name,
            'full_name_ja' => $this->full_name_ja,
            'final_education_date' => $this->final_education_date,
            'personal_pr_special_notes' => $this->personal_pr_special_notes,
            'remarks' => $this->remarks,
            'mobile_phone_number' => $this->mobile_phone_number,
            'mail_address' => $this->mail_address,
            'address_city' => $this->address_city,
            'address_district' => $this->address_district,
            'address_number' => $this->address_number,
            'address_other' => $this->address_other,
            'hometown_city' => $this->hometown_city,
            'home_town_district' => $this->home_town_district,
            'home_town_number' => $this->home_town_number,
            'home_town_other' => $this->home_town_other,
            'country_name' => $this->country_name,
        ];
    }

    public function hrOrg()
    {
        return $this->belongsTo(HrOrganization::class, 'hr_organization_id');
    }

    public function languageRequirement()
    {
        return $this->belongsTo(LanguageRequirement::class, 'japanese_level');
    }

    public function mainJobs()
    {
        return $this->hasMany(HRMainJobCareer::class, 'hrs_id');
    }

    public function entries(){
        return $this->hasMany(Entry::class, 'hr_id');
    }

    public function offers(){
        return $this->hasMany(Offer::class, 'hr_id');
    }

    public function interviews(){
        return $this->hasMany(Interview::class, 'hr_id');
    }
    public function results(){
        return $this->hasMany(Result::class, 'hr_id');
    }

    public function fileCV(){
        return $this->belongsTo(UploadFile::class, 'file_cv_id');
    }

    public function fileJob(){
        return $this->belongsTo(UploadFile::class, 'file_job_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
