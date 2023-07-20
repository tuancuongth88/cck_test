<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;
    const HR_ID = 'hr_id';
    const WORK_ID = 'work_id';
    const STATUS = 'status';
    const REMARKS = 'remarks';
    const REQUEST_DATE = 'request_date';
    const NOTE = 'note';
    const DISPLAY = 'display';
    protected $table = 'offers';

    protected $fillable = [
        self::HR_ID,
        self::WORK_ID,
        self::STATUS,
        self::REMARKS,
        self::REQUEST_DATE,
        self::NOTE,
        self::DISPLAY
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function hr()
    {
        return $this->belongsTo(HR::class, 'hr_id');
    }

    public function mainJobs()
    {
        return $this->hasMany(HRMainJobCareer::class, 'hrs_id', 'hr_id');
    }
}
