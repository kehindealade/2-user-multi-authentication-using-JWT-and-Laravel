<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'user', 'middleware' => ['assign.guard:web']], function(){
    Route::post('auth/login', 'ApiController@userLogin')->name('userLogin');
    Route::post('auth/register', 'ApiController@registerUser');

    Route::group(['prefix' => 'profile', 'middleware' => 'jwt.auth'], function(){
        Route::get('', 'UserProfileController@index');
        Route::put('/update', 'UserProfileController@update'); //Update profile
        Route::put('changepwd', 'UserProfileController@changePassword');
    });
});


Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admin']], function(){
    Route::post('auth/login', 'ApiController@adminLogin')->name('adminLogin');
});
