<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const TYPE = 'type';
    const NOTIFIABLE_TYPE = 'notifiable_type';
    const NOTIFIABLE_ID = 'notifiable_id';
    const DATA = 'data';
    const READ_AT = 'read_at';
    protected $fillable = [self::TYPE, self::NOTIFIABLE_TYPE,self::NOTIFIABLE_ID, self::DATA, self::READ_AT];

    public function getIdAttribute($id){
        return $id;
    }
}
