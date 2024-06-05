<?php

use Illuminate\Http\Response;
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
Route::group(['prefix' => 'authorize'], function () {
    Route::controller(\App\Http\Controllers\Api\AuthController::class)->group(function (){
        Route::post('login', 'login');
        Route::any('unauthenticated', function () {
            return response('Please login', Response::HTTP_UNAUTHORIZED);
        })->name('login');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(\App\Http\Controllers\Api\ProductController::class)->group(function () {
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'index');
            Route::get('/detail/{id}', 'detail')->where('id', '[0-9]+');
            Route::delete('delete/{id}', 'delete')->where('id', '[0-9]+');
            Route::put('update', 'update');
            Route::post('create', 'create');
        });
    });

    Route::controller(\App\Http\Controllers\Api\StoreController::class)->group(function () {
        Route::group(['prefix' => 'store'], function () {
            Route::get('/', 'index');
            Route::get('/detail/{id}', 'detail')->where('id', '[0-9]+');
            Route::delete('delete/{id}', 'delete')->where('id', '[0-9]+');
            Route::put('update', 'update');
            Route::post('create', 'create');
        });
    });

    Route::any('logout', '\App\Http\Controllers\Api\AuthController@logout');
});

