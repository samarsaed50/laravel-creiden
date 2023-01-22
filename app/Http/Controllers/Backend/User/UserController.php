<?php

namespace App\Http\Controllers\Backend\User;


use App\Models\Users\Repositories\UserRepository;
use App\Models\Users\Requests\StoreUserRequest;
use App\Models\Users\Requests\UpdateUserRequest;
use App\Models\Users\Requests\StoreItemsRequest;
use App\Http\Controllers\Controller;
use App\Models\Users\Repositories\UserRepositoryInterface;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Backend\CustomController;

class UserController extends CustomController
{
    protected $storeRequestFile = StoreUserRequest::class;

    protected $updateRequestFile = UpdateUserRequest::class;

    private $userRepository;
    public $apiResponse;

    public function __construct(UserRepositoryInterface $userRepository, ApiResponse $apiResponse)
    {
        parent::__construct($userRepository);
        $this->userRepository = $userRepository;
        $this->apiResponse = $apiResponse;
    }

    public function register(StoreUserRequest $request)
    {
        $response = $this->userRepository->apiRegister($request->all());

        return $this->apiResponse->responseHandler($response);

    }


    public function apiAddItems(StoreItemsRequest $request)
    {
        $response = $this->userRepository->apiAddItems($request->all());

        return $this->apiResponse->responseHandler($response);

    }


}


