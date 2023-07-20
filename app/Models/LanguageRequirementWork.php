<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageRequirementWork extends Model
{
    use HasFactory;
    const LANGUAGE_REQUIREMENT_ID = 'language_requirement_id';
    const WORK_ID = 'work_id';

    protected $table = 'language_requirement_works';

    protected $fillable = [self::LANGUAGE_REQUIREMENT_ID, self::WORK_ID];
}
