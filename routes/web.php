<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//登录注册
Route::group(['prefix' => 'user'], function (){

    Route::Post('/login', 'UserController@login');
    Route::Post('/register', 'UserController@create');

});

//用户中心
Route::group(['middleware' => 'token', 'prefix' => 'home'], function (){

    Route::Post('/', 'InfoController@home');

    //购买
    Route::Get('/package', 'PackageController@index');
    Route::Post('/package/buy', 'PackageController@buy');
});

Route::Get('/package/buy/jump', 'PackageController@jump');

//管理
Route::group(['middleware' => 'token', 'prefix' => 'admin'], function (){

    Route::Post('/verify', 'AdminController@verify');

    //套餐
    Route::Post('/package', 'AdminController@package');
    Route::Post('/package/create', 'AdminController@create_package');
    Route::Post('/package/delete', 'AdminController@delete_package');

    //用户
    Route::Post('/user', 'AdminController@user');

});


//测试

Route::Get('/test', 'TestController@email');
