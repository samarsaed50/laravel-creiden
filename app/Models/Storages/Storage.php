<?php

namespace App\Models\Storages;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;
use App\Models\Items\Item;

class Storage extends Model
{
    protected $fillable = ['name'];
    
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    
}


