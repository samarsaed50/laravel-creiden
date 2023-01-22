<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Storages\Storage;
use QCod\ImageUp\HasImageUploads;

class Item extends Model
{
    use HasImageUploads;
    protected $fillable = ['storage_id','item'];
    protected static $fileFields = ['item'];

    public function storage()
    {
        return $this->belongsTo(Storage::class,'storage_id');
    }


}


