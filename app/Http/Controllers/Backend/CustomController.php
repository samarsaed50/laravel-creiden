<?php

namespace App\Http\Controllers\Backend;
use App\Repositories\BaseRepositoryInterface;
use App\Responses\GeneralIndexResponse;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

class CustomController extends Controller
{
    protected $model;
    protected $view;
    protected $route;
    protected $storeRequestFile;
    protected $updateRequestFile;
    protected $apiResponse = ApiResponse::class;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->model = $repository;
        $this->folder = substr($this->view, strrpos($this->view, '.') + 1);
    }

    public function index()
    {
       
        return $this->apiResponse->responseHandler(["status" => true, "message" => __("api.success"), "data" => $this->model->all()]);
    }


    public function store()
    {
        app($this->storeRequestFile);
        // dd(request()->all());
       $response = $this->model->create(request()->all(), false);
        return $this->apiResponse->responseHandler($response);
    }

    public function show($id)
    {

        return $this->apiResponse->responseHandler(["status" => true, "message" => __("api.success"), "data" => $this->model->getById($id)]);
    }


    public function update($id)
    {
        app($this->updateRequestFile);
//        dd(request()->all());
        $response = $this->model->updateById($id, request()->all());

         return $this->apiResponse->responseHandler($response);
    }

    public function destroy($id)
    {
        $this->model->deleteById($id);
        return $this->apiResponse->responseHandler(["status" => true, "message" => __("api.success_deleted"), "data" => '']);
    }

}
