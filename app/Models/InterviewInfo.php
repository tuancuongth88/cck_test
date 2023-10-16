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
    const INTERVIEW_TYPE = 'type';
    const INTERVIEW_NUMBER = 'number';
    const INTERVIEW_NUMBER_SELECTION = 'number_selection';
    const CALENDAR_INTERVIEW = 'calendar_interview';
    const CALENDAR_DATE_INTERVIEW = 'date_interview';
    const STATUS = 'status';
    const URL_ZOOM = 'url_zoom';
    const ID_ZOOM = 'id_zoom';
    const PASSWORD_ZOOM = 'password_zoom';

    protected $table = 'interview_infos';

    protected $fillable = [self::INTERVIEW_ID, self::INTERVIEW_TYPE, self::INTERVIEW_NUMBER, self::INTERVIEW_NUMBER_SELECTION, self::CALENDAR_INTERVIEW, self::CALENDAR_DATE_INTERVIEW, self::STATUS, self::URL_ZOOM, self::ID_ZOOM, self::PASSWORD_ZOOM];

    protected $dates = ['deleted_at'];

    protected $casts = [
        self::CALENDAR_INTERVIEW => 'array'
    ];

    public static $optionSelectRound = [
        1 => [
            'round_number' => INTERVIEW_INFO_NUMBER_FIRST,
            'round_text_ja' => '一次面接へ',
            'round_text_en' => 'Move to first interview',
            'round_name_current' => INTERVIEW_INFO_NUMBER_TEXT_JA[INTERVIEW_INFO_NUMBER_FIRST],
        ],
        2 => [
            'round_number' => INTERVIEW_INFO_NUMBER_SECOND,
            'round_text_ja' => '二次面接へ',
            'round_text_en' => 'Move to second interview',
            'round_name_current' => INTERVIEW_INFO_NUMBER_TEXT_JA[INTERVIEW_INFO_NUMBER_SECOND],

        ],
        3 => [
            'round_number' => INTERVIEW_INFO_NUMBER_THIRD,
            'round_text_ja' => '三次面接へ',
            'round_text_en' => 'Move to third interview',
            'round_name_current' => INTERVIEW_INFO_NUMBER_TEXT_JA[INTERVIEW_INFO_NUMBER_THIRD],
        ],
        4 => [
            'round_number' => INTERVIEW_INFO_NUMBER_FOURTH,
            'round_text_ja' => '四次合格へ',
            'round_text_en' => 'Move to fourth interview',
            'round_name_current' => INTERVIEW_INFO_NUMBER_TEXT_JA[INTERVIEW_INFO_NUMBER_FOURTH],

        ],
        5 => [
            'round_number' => INTERVIEW_INFO_NUMBER_FIFTH,
            'round_text_ja' => '最終面接へ',
            'round_text_en' => 'Move to final interview',
            'round_name_current' => INTERVIEW_INFO_NUMBER_TEXT_JA[INTERVIEW_INFO_NUMBER_FIFTH],
        ]
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'interview_id', 'id');
    }

}
