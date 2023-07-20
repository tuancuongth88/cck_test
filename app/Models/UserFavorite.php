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

class UserFavorite extends Model
{
    use HasFactory;

    const USER_ID = 'user_id';
    const RELATION_ID = 'relation_id';
    const TYPE = 'type';

    const TYPE_JOB = 1;
    const TYPE_HRS = 2;

    protected $table = 'user_favorites';

    protected $fillable = [self::USER_ID, self::RELATION_ID, self::TYPE];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'data' => 'array'
    ];

    public static function isFavorite($relationId, $type)
    {
        return (int) self::query()
            ->where('user_id', Auth::id())
            ->where('relation_id', $relationId)
            ->where('type', $type)->exists();
    }

}
