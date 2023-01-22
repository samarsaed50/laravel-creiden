<?php

namespace App\Models\Storages\Repositories;

use App\Models\Storages\Storage;
use App\Repositories\BaseRepository;
use App\Models\Storages\Repositories\StorageRepositoryInterface;
use App\Models\Storages\Repositories\ViewData;

class StorageRepository extends BaseRepository implements StorageRepositoryInterface
{
    use ViewData;

//    protected $with = [''];

	public function __construct(Storage $storage)
    {
        $this->model = $storage;
    }

    public function create($data)
    {

        $user = $this->model->create($data);

        if ($user) {

            return ["status" => true, "message" => __("api.success"), "data" => $user];
        }

        return ["status" => false, "message" => __("api.failed"), "data" => ""];

    }

    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        $model->update($data, $options);

        return ["status" => true, "message" => __("api.success"), "data" => $model];
    }


}

