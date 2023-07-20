<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="AccountClassification",
 *     title="AccountClassification",
 *     description="Account Classification:
 *     - `1`: 自社プラットフォーム
 *     - `2`: 送り出し期間
 *     - `3`: 派遣会社
 *     - `4`: 学校",
 *     type="int",
 *     enum={1,2,3,4},
 * )
 **/
class HrOrganization extends Model
{
    use HasFactory;

    protected $table = 'hr_organization';

    const USER_ID = 'user_id';
    const CORPORATE_NAME_EN = 'corporate_name_en';
    const CORPORATE_NAME_JA = 'corporate_name_ja';
    const LICENSE_NO = 'license_no';
    const ACCOUNT_CLASSIFICATION = 'account_classification';
    const COUNTRY = 'country';
    const REPRESENTATIVE_FULL_NAME = 'representative_full_name';
    const REPRESENTATIVE_FULL_NAME_FURIGANA = 'representative_full_name_furigana';
    const REPRESENTATIVE_CONTACT = 'representative_contact';
    const ASSIGNEE_CONTACT = 'assignee_contact';
    const POST_CODE = 'post_code';
    const PREFECTURES = 'prefectures';
    const MUNICIPALITY = 'municipality';
    const NUMBER = 'number';
    const OTHER = 'other';
    const MAIL_ADDRESS = 'mail_address';
    const URL = 'url';
    const CERTIFICATE_FILE = 'certificate_file_id';
    const STATUS = 'status';

    protected $fillable = [
        self::USER_ID,
        self::CORPORATE_NAME_EN,
        self::CORPORATE_NAME_JA,
        self::LICENSE_NO,
        self::ACCOUNT_CLASSIFICATION,
        self::COUNTRY,
        self::REPRESENTATIVE_FULL_NAME,
        self::REPRESENTATIVE_FULL_NAME_FURIGANA,
        self::REPRESENTATIVE_CONTACT,
        self::ASSIGNEE_CONTACT,
        self::POST_CODE,
        self::PREFECTURES,
        self::MUNICIPALITY,
        self::NUMBER,
        self::OTHER,
        self::MAIL_ADDRESS,
        self::URL,
        self::CERTIFICATE_FILE,
        self::STATUS,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function file()
    {
        return $this->belongsTo(UploadFile::class, self::CERTIFICATE_FILE, 'id');
    }

    public static $listAccountClassification = [
        ACC_CLASS_OWN_PLATFORM => '自社プラットフォーム',
        ACC_CLASS_SEND_AGENCY => '送り出し期間',
        ACC_CLASS_DISPATCH_AGENCY => '派遣会社',
        ACC_CLASS_SCHOOL => '学校'
    ];
}
