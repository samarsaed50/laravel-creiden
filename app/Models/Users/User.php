<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Storages\Storage;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = ['name','email','password','storage_id'];

    public function storage()
    {
        return $this->belongsTo(Storage::class,'storage_id');
    }

    public function setPasswordAttribute($password): void
    {
        if ($password) {
            // If password was accidentally passed in already hashed, try not to double hash it
            if (
                (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
                (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
            ) {
                $hash = $password;
            } else {
                $hash = Hash::make($password);
            }

            $this->attributes['password'] = $hash;
        }
    }


}


