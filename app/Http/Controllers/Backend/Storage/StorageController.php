<?php

namespace App\Http\Controllers\Backend\Storage;

use App\Http\Controllers\Backend\CustomController;
use App\Models\Storages\Repositories\StorageRepositoryInterface;
use App\Models\Storages\Requests\StoreStorageRequest;
use App\Models\Storages\Requests\UpdateStorageRequest;
use App\Helpers\ApiResponse;

class StorageController extends CustomController
{

    protected $storeRequestFile = StoreStorageRequest::class;

    protected $updateRequestFile = UpdateStorageRequest::class;

    private $storageRepository;
    public $apiResponse;

    public function __construct(StorageRepositoryInterface $storageRepository, ApiResponse $apiResponse)
    {
        parent::__construct($storageRepository);
        $this->storageRepository = $storageRepository;
        $this->apiResponse = $apiResponse;
    }


}


