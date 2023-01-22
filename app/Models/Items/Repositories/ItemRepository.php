<?php

namespace App\Models\Items\Repositories;

use App\Models\Items\Item;
use App\Repositories\BaseRepository;
use App\Models\Items\Repositories\ItemRepositoryInterface;
use App\Models\Items\Repositories\ViewData;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    use ViewData;

//    protected $with = [''];

	public function __construct(Item $item)
    {
        $this->model = $item;
    }


}

