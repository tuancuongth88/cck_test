<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';
    public $timestamps = false;

    const EMAIL = 'email';
    const TOKEN = 'token';
    const CREATED_AT = 'created_at';

    protected $fillable = [
        self::EMAIL,
        self::TOKEN,
        self::CREATED_AT,
    ];
}
