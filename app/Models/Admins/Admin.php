<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = ['name','email','password'];

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


