<?php

namespace App\Models\Admins\Repositories;

use App\Models\Admins\Admin;
use App\Repositories\BaseRepository;
use App\Models\Admins\Repositories\AdminRepositoryInterface;
use App\Models\Admins\Repositories\ViewData;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    use ViewData;

//    protected $with = [''];

	public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    public function apiRegister($data)
    {

        $admin = $this->model->create($data);

        if ($admin) {

            $admin['token'] = $admin->createToken('TutsForWeb')->accessToken;

            return ["status" => true, "message" => __("api.success"), "data" => $admin];
        }

        return ["status" => false, "message" => __("api.failed"), "data" => ""];

    }



}

