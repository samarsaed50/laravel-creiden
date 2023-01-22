<?php

namespace App\Models\Users\Repositories;

use Illuminate\Http\Request;
 
interface UserRepositoryInterface
{
    public function apiRegister($data);
    public function apiAddItems($data);
}

