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


Route::group(['prefix' => 'user'], function (){

    Route::Post('/login', 'UserController@login');

    Route::Post('/register', 'UserController@create');

});

Route::group(['middleware' => 'token', 'prefix' => 'home'], function (){

    Route::Post('/', 'InfoController@home');

});

