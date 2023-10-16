<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

/**
 * @OA\Schema(
 *     type="object",
 *     title="Work",
 *     required={"title","major_classification_id", "middle_classification_id", "application_period_from", "application_period_to",
 *     "job_description","application_condition","application_requirement","other_skill","form_of_employment","working_time_from",
 *     "working_time_to","vacation","expected_income","city_id","treatment_welfare","bonus_pay_rise","over_time","transfer",
 *     "interview_follow"},
 *     @OA\Property(
 *      property="title",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="major_classification_id",
 *      format="integer",
 *     ),
 *     @OA\Property(
 *      property="middle_classification_id",
 *      format="integer",
 *     ),
 *     @OA\Property(
 *      property="company_id",
 *      format="integer",
 *     ),
 *     @OA\Property(
 *      property="application_period_from",
 *      description="2020-01-01",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="application_period_to",
 *      description="2020-01-01",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="job_description",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="application_condition",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="application_requirement",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="language_requirements",
 *      description="Language Requirement",
 *      type="object",
 *      example={1, 2, 3},
 *     ),
 *     @OA\Property(
 *      property="other_skill",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="preferred_skill",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="form_of_employment",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="working_time_from",
 *      format="string",
 *      description="0:00",
 *     ),
 *     @OA\Property(
 *      property="working_time_to",
 *      format="string",
 *      description="23:30",
 *     ),
 *     @OA\Property(
 *      property="vacation",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="expected_income",
 *      format="interge",
 *     ),
 *     @OA\Property(
 *      property="city_id",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="working_palace_detail",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="treatment_welfare",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="company_pr_appeal",
 *      format="string",
 *     ),
 *     @OA\Property(
 *      property="bonus_pay_rise",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="over_time",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="transfer",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="passive_smoking",
 *      format="integer",
 *      default=1,
 *     ),
 *     @OA\Property(
 *      property="passions",
 *      description="Passion",
 *      type="object",
 *      example={1, 2, 3},
 *     ),
 *     @OA\Property(
 *      property="interview_follow",
 *      format="integer",
 *      default=1,
 *     ),
 * )
 *
 * @OA\RequestBody(
 *   request="CreateWork",
 *   required=true,
 *   description="work_request",
 *   @OA\MediaType(
 *      mediaType="multipart/form-data",
 *      @OA\Schema(
 *          allOf={
*               @OA\Schema(ref="#/components/schemas/Work"),
 *          }
 *      ),
 *   ),
 * )
*/

class Work extends Model
{
    use HasFactory, Searchable;
    use SoftDeletes;

    const USER_ID = 'user_id';
    const COMPANY_ID = 'company_id';
    const TITLE = 'title';
    const MAJOR_CLASSIFICATION_ID = 'major_classification_id';
    const MIDDLE_CLASSIFICATION_ID = 'middle_classification_id';
    const APPLICATION_PERIOD_FROM = 'application_period_from';
    const APPLICATION_PERIOD_TO = 'application_period_to';
    const JOB_DESCRIPTION = 'job_description';
    const APPLICATION_CONDITION = 'application_condition';
    const APPLICATION_REQUIREMENT = 'application_requirement';
    const OTHER_SKILL = 'other_skill';
    const PREFERRED_SKILL = 'preferred_skill';
    const FORM_OF_EMPLOYMENT = 'form_of_employment';
    const WORKING_TIME_FROM = 'working_time_from';
    const WORKING_TIME_TO = 'working_time_to';
    const VACATION = 'vacation';
    const EXPECTED_INCOME = 'expected_income';
    const ASSUMED_ANNUAL_INCOME = 'assumed_annual_income';
    const CITY_ID = 'city_id';
    const WORKING_PALACE_DETAIL = 'working_palace_detail';
    const TREATMENT_WELFARE = 'treatment_welfare';
    const COMPANY_PR_APPEAL = 'company_pr_appeal';
    const BONUS_PAY_RISE = 'bonus_pay_rise';
    const OVER_TIME = 'over_time';
    const TRANSFER = 'transfer';
    const PASSIVE_SMOKING = 'passive_smoking';
    const INTERVIEW_FOLLOW = 'interview_follow';
    const STATUS = 'status';
    const COUNTRY_ID = 'country_id';
    const COUNTRY_NAME = 'country_name';
    const CREATED_BY = 'created_by';
    const UPDATED_BY = 'updated_by';


    protected $table = 'works';

    protected $fillable = [self::USER_ID,
        self::COMPANY_ID,
        self::TITLE,
        self::MAJOR_CLASSIFICATION_ID,
        self::MIDDLE_CLASSIFICATION_ID,
        self::APPLICATION_PERIOD_FROM,
        self::APPLICATION_PERIOD_TO,
        self::JOB_DESCRIPTION,
        self::APPLICATION_CONDITION,
        self::APPLICATION_REQUIREMENT,
        self::OTHER_SKILL,
        self::PREFERRED_SKILL,
        self::FORM_OF_EMPLOYMENT,
        self::WORKING_TIME_FROM,
        self::WORKING_TIME_TO,
        self::VACATION,
        self::EXPECTED_INCOME,
        self::ASSUMED_ANNUAL_INCOME,
        self::CITY_ID,
        self::WORKING_PALACE_DETAIL,
        self::TREATMENT_WELFARE,
        self::COMPANY_PR_APPEAL,
        self::BONUS_PAY_RISE,
        self::OVER_TIME,
        self::TRANSFER,
        self::PASSIVE_SMOKING,
        self::INTERVIEW_FOLLOW,
        self::STATUS,
        self::COMPANY_ID,
        self::COUNTRY_NAME,
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
            'title' => $this->title,
            'application_period_from' => $this->application_period_from,
            'application_period_to' => $this->application_period_to,
            'job_description' => $this->job_description,
            'application_condition' => $this->application_condition,
            'application_requirement' => $this->application_requirement,
            'other_skill' => $this->other_skill,
            'preferred_skill' => $this->preferred_skill,
            'vacation' => $this->vacation,
            'expected_income' => $this->expected_income,
            'assumed_annual_income' => $this->assumed_annual_income,
            'working_palace_detail' => $this->working_palace_detail,
            'treatment_welfare' => $this->treatment_welfare,
            'company_pr_appeal' => $this->company_pr_appeal,
        ];
    }


    public function languageRequirements(){
        return $this->belongsToMany(LanguageRequirement::class, 'language_requirement_works');
    }

    public function passion(){
        return $this->belongsToMany(Passion::class, 'passion_works');
    }

    public function favorite(){
        return $this->belongsToMany( User::class, 'user_favorites', 'id', 'relation_id')
            ->where(UserFavorite::TYPE, FAVORITE_TYPE_WORK);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function middleClassification(){
        return $this->belongsTo(JobInfo::class, 'middle_classification_id');
    }
    public function majorClassification(){
        return $this->belongsTo(JobType::class, 'major_classification_id');
    }

    public static $interviewFlow = [
        WORK_FIRST_INTERVIEW    => '一次面接まで',
        WORK_SECOND_INTERVIEW   => '二次面接まで',
        WORK_THIRD_INTERVIEW    => '三次面接まで',
        WORK_FOURTH_INTERVIEW   => '四次面接まで',
        WORK_FIFTH_INTERVIEW    => '五次面接まで',
    ];

    public static function checkDataBeforeDelete($id): bool
    {
        $entry = Entry::query()->where(Entry::WORK_ID, $id)->first();
        $offer = Offer::query()->where(Offer::WORK_ID, $id)->first();
        $interview = Interview::query()->where(Interview::WORK_ID, $id)->first();
        $result = Result::query()->where(Result::WORK_ID, $id)->first();
        if ($entry || $offer || $interview || $result){
            return false;
        }
        return true;
    }

}
