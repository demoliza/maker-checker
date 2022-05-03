<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request){
        return $request->user();
    });
    Route::post('logout', 'AuthController@logout');
    Route::apiResource('users', 'UserController');
    Route::get('requests', 'RequestController@index');
    Route::patch('requests/{id}/approve', 'RequestController@approve');
    Route::delete('requests/{id}/decline', 'RequestController@decline');
});

Route::get('/admins', function (Request $request){
    $admins = DB::table('users')->where(['is_admin'=>true])->pluck('email');
    return $admins;
});
