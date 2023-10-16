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

class Result extends Model
{
    use HasFactory;
    use SoftDeletes;

    const INTERVIEW_ID = 'interview_id';
    const CODE = 'code';
    const HR_ID = 'hr_id';
    const WORK_ID = 'work_id';
    const STATUS_SELECTION = 'status_selection';
    const TIME = 'time';
    const NOTE = 'note';
    const DISPLAY = 'display';
    const HIRE_DATE = 'hire_date';
    const DECLINE_DATE = 'decline_date';

    protected $table = 'results';

    protected $fillable = [
        self::INTERVIEW_ID,
        self::CODE ,
        self::HR_ID,
        self::WORK_ID,
        self::STATUS_SELECTION,
        self::TIME,
        self::NOTE,
        self::DISPLAY,
        self::HIRE_DATE,
        self::DECLINE_DATE,
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function hr()
    {
        return $this->belongsTo(HR::class, 'hr_id');
    }

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'interview_id');
    }

    public function mainJobs()
    {
        return $this->hasMany(HRMainJobCareer::class, 'hrs_id', 'hr_id');
    }

    public static function isResult($hrId)
    {
        return (int) self::query()
            ->where('hr_id', $hrId)
            ->where('status_selection', RESULT_STATUS_SELECTION_OFFER_CONFIRM)->exists();
    }

}
