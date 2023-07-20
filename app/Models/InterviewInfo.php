<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    const INTERVIEW_ID = 'interview_id';
    const CALENDAR_INTERVIEW = 'calendar_interview';
    const STATUS = 'status';
    const URL_ZOOM = 'url_zoom';
    const ID_ZOOM = 'id_zoom';
    const PASSWORD_ZOOM = 'password_zoom';

    protected $table = 'interview_infos';

    protected $fillable = [self::INTERVIEW_ID,self::CALENDAR_INTERVIEW,self::STATUS,self::URL_ZOOM,self::ID_ZOOM,self::PASSWORD_ZOOM];

    protected $dates = ['deleted_at'];

    protected $casts = [
        self::CALENDAR_INTERVIEW => 'array'
    ];

}
