<?php

/**
 * how to use create folder helper in same plcae where is http folder exist
 * but it inside
 * how to call it in controller
 *1)use App\Helpers\ApiResponse;
 * public $apiResponse; inside controller first line after
 *add this constracutor after public $apiResponse
 *   public function __construct( ApiResponse $apiResponse)
 *   {
 *       $this->apiResponse = $apiResponse;
 *   }
 * return responce like that      return $this->apiResponse->setSuccess(" Successfuly")->setData($product)->send(); ,or with error
 *      return $this->apiResponse->setError("  Error message")->setData()->send(); // set data empty beacuse we will send it null

 */

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class ApiResponse
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * @var array
     */
    protected $body;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }



    /**
     * Set response data.
     *
     * @param $data
     * @return $this
     */
    public function setData($data = null): object
    {
        $this->body['data'] = $data;
        return $this;
    }


    public function setError($error): object

    {
        $this->body['status'] = false;
        $this->body['message'] = $error;
        return $this;
    }

    public function setSuccess($message): object

    {
        $this->body['status'] = true;
        $this->body['message'] = $message;
        return $this;
    }

    public function returnJSON(): JsonResponse
    {
        $responsecode = 200;
         if ($this->body['status'] == false) {
            $responsecode = 400;
        }
        return $this->response->json($this->body, $responsecode);
    }

    public function responseHandler($response){

        if ($response["status"] == false) {
            return $this->setError($response["message"])->setData($response["data"])->returnJSON();

        }
          
        return $this->setSuccess($response["message"])->setData($response["data"])->returnJSON();
    }
}
