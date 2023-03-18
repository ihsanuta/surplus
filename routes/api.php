<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('healthcheck', function(){
    return response()->json([
        'status' => 'success',
        'message' => 'OK',
    ],200);
});

Route::prefix('v1')->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::post('category','create');
        Route::put('category/{id}','update');
        Route::get('category','list');
        Route::get('category/{id}','get');
        Route::delete('category/{id}','delete');
    });

    Route::controller(ImageController::class)->group(function () {
        Route::post('image','create');
        Route::post('image/{id}','update');
        Route::get('image','list');
        Route::get('image/{id}','get');
        Route::delete('image/{id}','delete');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::post('product','create');
        Route::put('product/{id}','update');
        Route::get('product','list');
        Route::get('product/{id}','get');
        Route::delete('product/{id}','delete');
    });
});
