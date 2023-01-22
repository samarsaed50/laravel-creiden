<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admins\Repositories\AdminRepositoryInterface;
use App\Models\Admins\Requests\AdminRegisterRequest;
use App\Models\Admins\Repositories\CustomerRepository;
use App\Helpers\ApiResponse;

class AdminController extends Controller
{

    private $adminRepository;
    public $apiResponse;

    public function __construct(AdminRepositoryInterface $adminRepository, ApiResponse $apiResponse)
    {
        $this->adminRepository = $adminRepository;
        $this->apiResponse = $apiResponse;
    }

    public function register(AdminRegisterRequest $request)
    {
        $response = $this->adminRepository->apiRegister($request->all());

        return $this->apiResponse->responseHandler($response);

    }

}


