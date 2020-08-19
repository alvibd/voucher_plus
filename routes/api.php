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

Route::middleware('api')->group(function(){
    Route::get('/categories', 'CategoryController@show');
});

Route::middleware(['auth:api', 'jwt.refresh'])->namespace('Auth')->group(function(){
    Route::post('/me', 'ApiAuthController@me');
    Route::post('/logout', 'ApiAuthController@logout');
});

Route::middleware(['auth:api', 'jwt.refresh'])->group(function(){
    Route::post('/vendor/registration', 'VendorController@store');
});

Route::middleware(['auth:api', 'role:owner', 'jwt.refresh'])->prefix('/vendor')->group(function(){
    Route::patch('/edit/{vendor}', 'VendorController@update');

    Route::post('/{vendor}/create_deals', 'DealController@store');

    Route::post('/{vendor}/{deal}/create_items', 'DealItemController@store');
});

