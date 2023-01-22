<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'admins/', 'namespace' => 'Backend\Admin'], function () {

    Route::post("register", "AdminController@register"); 


});

Route::group(['namespace' => 'Backend\User'], function () {

    Route::group(['prefix' => 'users/'], function () {
      Route::post("register", "UserController@register"); 
    });

    Route::group(['middleware' => 'auth:admin_api'], function () {
         Route::resource('users', 'UserController')->except(['create','edit']);
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('add-items', 'UserController@apiAddItems');
   });

});

Route::group(['namespace' => 'Backend\Storage'], function () {


    Route::group(['middleware' => 'auth:admin_api'], function () {
         Route::resource('storages', 'StorageController')->except(['create','edit']);
    });

});