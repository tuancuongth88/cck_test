<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageRequirement extends Model
{
    use HasFactory;

    const NAME = 'name';

    protected $table = 'language_requirements';

    protected $fillable = [self::NAME];
}
