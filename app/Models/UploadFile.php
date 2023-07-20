<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    use HasFactory;

    protected $table = 'files';

    const FILE_MODEL = 'file_model';
    const FILE_NAME = 'file_name';
    const FILE_EXTENSION = 'file_extension';
    const FILE_PATH = 'file_path';
    const FILE_SIZE = 'file_size';
    const FILE_ITEM_TYPE = 'item_type';
    const FILE_ITEM_ID = 'item_id';


    protected $fillable = [
        self::FILE_MODEL,
        self::FILE_NAME,
        self::FILE_EXTENSION,
        self::FILE_PATH,
        self::FILE_SIZE,
        self::FILE_ITEM_TYPE,
        self::FILE_ITEM_ID,
    ];

    public function hrOrganization()
    {
        return $this->hasOne(HrOrganization::class, HrOrganization::CERTIFICATE_FILE, 'id');
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class, self::FILE_ITEM_ID, 'id')->whereNotNull('item_id');
    }
}
