<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passion extends Model
{
    use HasFactory, SoftDeletes;

    const NAME_EN = 'name_en';
    const NAME_JP = 'name_jp';

    protected $table = 'passions';

    protected $fillable = [self::NAME_EN, self::NAME_JP];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];
}
