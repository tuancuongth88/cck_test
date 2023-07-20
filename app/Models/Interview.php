<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasFactory;
    use SoftDeletes;

    const HR_ID = 'hr_id';
    const WORK_ID = 'work_id';
    const INTERVIEW_CODE = 'code';
    const INTERVIEW_DATE = 'interview_date';
    const TYPE = 'type';
    const STATUS_SELECTION = 'status_selection';
    const INTERVIEW_ADJUSTMENT = 'status_interview_adjustment';
    const REMARKS = 'remarks';
    const DISPLAY = 'display';

    protected $table = 'interviews';

    protected $fillable = [
        self::HR_ID,
        self::WORK_ID,
        self::INTERVIEW_CODE,
        self::INTERVIEW_DATE,
        self::TYPE,
        self::STATUS_SELECTION,
        self::INTERVIEW_ADJUSTMENT,
        self::REMARKS,
        self::DISPLAY
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array',
    ];

    public function work(){
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function hr(){
        return $this->belongsTo(HR::class, 'hr_id');
    }

    public function mainJobs()
    {
        return $this->hasMany(HRMainJobCareer::class, 'hrs_id', 'hr_id');
    }

    public function infors(){
        return $this->hasMany(InterviewInfo::class, 'interview_id');
    }

    public function people()
    {
        return $this->hasMany(Interview::class, 'code', 'code')
            ->where('type', $this->type)
            ->where('status_selection', $this->status_selection)
            ->where('status_interview_adjustment', $this->status_interview_adjustment)
            ;
    }
}
