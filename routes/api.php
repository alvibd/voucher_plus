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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->namespace('Auth')->group(function(){
    Route::post('/registration', 'ApiAuthController@register');
    // Route::post('/vendor/registration', 'ApiAuthController@vendorRegistration');
    Route::post('/login', 'ApiAuthController@login');
});

Route::middleware('auth:api')->group(function(){
    Route::post('/vendor/registration', 'VendorController@store');
    Route::patch('/vendor/edit/{vendor}', 'VendorController@update');
});

Route::middleware(['auth:api', 'role:owner'])->prefix('/vendor')->group(function(){
    Route::patch('/edit/{vendor}', 'VendorController@update');

    Route::post('/{vendor}/create_deals', 'DealController@store');

    Route::post('/{vendor}/{deal}/create_items', 'DealItemController@store');
});

