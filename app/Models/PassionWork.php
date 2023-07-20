<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassionWork extends Model
{
    use HasFactory;

    const WORK_ID = 'work_id';
    const PASSION_ID = 'passion_id';

    protected $table = 'passion_works';

    protected $fillable = [self::WORK_ID, self::PASSION_ID];

    protected $casts = [
        'data' => 'array'
    ];
}
