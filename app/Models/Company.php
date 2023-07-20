<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    const USER_ID = 'user_id';
    const COMPANY_NAME = 'company_name';
    const COMPANY_NAME_JP = 'company_name_jp';
    const MAJOR_CLASSIFICATION = 'major_classification';
    const MIDDLE_CLASSIFICATION = 'middle_classification';
    const POST_CODE = 'post_code';
    const PREFECTURES = 'prefectures';
    const MUNICIPALITY = 'municipality';
    const NUMBER = 'number';
    const OTHER = 'other';
    const TELEPHONE_NUMBER = 'telephone_number';
    const MAIL_ADDRESS = 'mail_address';
    const URL = 'url';
    const JOB_TITLE = 'job_title';
    const FULL_NAME = 'full_name';
    const FULL_NAME_FURIGANA = 'full_name_furigana';
    const REPRESENTATIVE_CONTACT = 'representative_contact';
    const ASSIGNEE_CONTACT = 'assignee_contact';
    const ESTABLISHMENT_YEAR = 'establishment_year';
    const STARTUP_YEAR = 'startup_year';
    const CAPITAL = 'capital';
    const PROCEEDS = 'proceeds';
    const NUMBER_OF_STAFFS = 'number_of_staffs';
    const NUMBER_OF_OPERATIONS = 'number_of_operations';
    const NUMBER_OF_SHOPS = 'number_of_shops';
    const NUMBER_OF_FACTORY = 'number_of_factory';
    const FISCAL     = 'fiscal';
    const STATUS = 'status';

    protected $fillable = [
        self::USER_ID,
        self::COMPANY_NAME,
        self::COMPANY_NAME_JP,
        self::MAJOR_CLASSIFICATION,
        self::MIDDLE_CLASSIFICATION,
        self::POST_CODE,
        self::PREFECTURES,
        self::MUNICIPALITY,
        self::NUMBER,
        self::OTHER,
        self::TELEPHONE_NUMBER,
        self::MAIL_ADDRESS,
        self::URL,
        self::JOB_TITLE,
        self::FULL_NAME,
        self::FULL_NAME_FURIGANA,
        self::REPRESENTATIVE_CONTACT,
        self::ASSIGNEE_CONTACT,
        self::ESTABLISHMENT_YEAR,
        self::STARTUP_YEAR,
        self::CAPITAL,
        self::PROCEEDS,
        self::NUMBER_OF_STAFFS,
        self::NUMBER_OF_OPERATIONS,
        self::NUMBER_OF_SHOPS,
        self::NUMBER_OF_FACTORY,
        self::FISCAL,
        self::STATUS,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jobType(){
        return $this->belongsTo(JobType::class, 'major_classification');
    }

    public function job(){
        return $this->belongsTo(JobInfo::class, 'middle_classification');
    }

}
