<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    use HasFactory;

    protected $table = 'job_type';
    const NAME_JA = 'name_ja';
    const NAME_EN = 'name_en';
    const TYPE = 'type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::NAME_JA,
        self::NAME_EN,
        self::TYPE
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function jobInfo()
    {
        return $this->hasMany(JobInfo::class, 'job_type_id', 'id');
    }
}
