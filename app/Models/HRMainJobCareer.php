<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRMainJobCareer extends Model
{
    use HasFactory, SoftDeletes;
    const HRS_ID = 'hrs_id';
    const MAIN_JOB_CAREER_DATE_FROM = 'main_job_career_date_from';
    const MAIN_JOB_CAREER_DATE_TO = 'main_job_career_date_to';
    const DEPARTMENT_ID = 'department_id';
    const JOB_ID = 'job_id';
    const DETAIL = 'detail';
    const TO_NOW = 'to_now';
    protected $table = 'hrs_main_job_career';

    protected $fillable = [self::HRS_ID,self::MAIN_JOB_CAREER_DATE_FROM,self::MAIN_JOB_CAREER_DATE_TO,self::DEPARTMENT_ID,self::JOB_ID,self::DETAIL, self::TO_NOW];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
