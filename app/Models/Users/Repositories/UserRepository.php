<?php

namespace App\Models\Users\Repositories;

use App\Models\Users\User;
use App\Repositories\BaseRepository;
use App\Models\Users\Repositories\UserRepositoryInterface;
use App\Models\Users\Repositories\ViewData;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    use ViewData;

   protected $with = ['storage'];

	public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function apiRegister($data)
    {

       return $this->create($data);

    }

    public function create($data)
    {

        $user = $this->model->create($data);

        if ($user) {

            $user['token'] = $user->createToken('TutsForWeb')->accessToken;

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

    public function apiAddItems($data)
    {
        $user = auth()->user();
      // dd(request('items'));
       if($user->storage){
         foreach(request('items') as $item){
            $storage = $user->storage;
            $extension = $item->getClientOriginalExtension();
            $name = 'master' . rand(11111, 99999) . '.' . $extension;
            $destinationPath = storage_path('app/public/uploads/');
            $item->move($destinationPath, $name);
            $storage->items()->create([  
                    "item" => 'uploads/' . $name,
                  
            ]);  
         }  
         return ["status" => true, "message" => __("api.success"), "data" => $storage->items];
       }
       return ["status" => false, "message" => __("api.storage not found"), "data" => ""];
    }

}

