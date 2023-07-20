<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobInfo extends Model
{
    use HasFactory;

    protected $table = 'job_info';
    const NAME_JA = 'name_ja';
    const NAME_EN = 'name_en';
    const JOB_TYPE_ID = 'job_type_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME_JA,
        self::NAME_EN,
        self::JOB_TYPE_ID
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function jobType()
    {
        return $this->belongsTo(JobType::class, 'job_type_id', 'id');
    }
}
