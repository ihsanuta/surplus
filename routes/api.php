<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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
});
