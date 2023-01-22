<?php

namespace App\Http\Controllers\Backend\Item;

use App\Http\Controllers\Backend\CustomController;
use App\Models\Items\Repositories\ItemRepository;
use App\Models\Items\Requests\StoreItemRequest;
use App\Models\Items\Requests\UpdateItemRequest;


class ItemController extends CustomController
{
    protected $view = 'backend.items';

    protected $route = 'admin.items';

    protected $storeRequestFile = StoreItemRequest::class;

    protected $updateRequestFile = UpdateItemRequest::class;

    public function __construct(ItemRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {

        $items = Item::query()->search(request()->get('search'))->latest()->paginate();

        return view('backend.items.index', compact('items'));

    }

}


