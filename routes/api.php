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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register','UserController@store');
Route::post('login','UserController@login');
Route::post('resetPassword','UserController@resetPassword');

Route::group(['middleware' => ['auth']], function (){
    Route::apiResource('users','UserController');
    Route::get('show','UserController@show');
    Route::post('changePassword','UserController@changePassword');
    Route::apiResource('application','AppController');
    Route::apiResource('restriction','RestrictionController');
    Route::apiResource('usage','UsageController');
    Route::get('show','UsageController@show');  
});