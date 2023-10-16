<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ENTRY_CODE = 'code';
    const REQUEST_DATE = 'request_date';
    const HR_ID = 'hr_id';
    const WORK_ID = 'work_id';
    const STATUS = 'status';
    const REMARKS = 'remarks';
    const NOTE = 'note';
    const DISPLAY = 'display';

    protected $table = 'entries';

    protected $fillable = [
        self::ENTRY_CODE,
        self::REQUEST_DATE,
        self::HR_ID,
        self::WORK_ID,
        self::STATUS,
        self::REMARKS,
        self::NOTE,
        self::DISPLAY
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function files()
    {
        return $this->hasMany(UploadFile::class, UploadFile::FILE_ITEM_ID, 'id')
            ->whereNotNull('item_id');
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
