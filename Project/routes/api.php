<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuppliersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{product}', [ProductsController::class, 'show']);
Route::put('/products/{product}', [ProductsController::class, 'update']);
Route::post('/products', [ProductsController::class, 'store']);
Route::delete('/products/{product}', [ProductsController::class, 'destroy']);

Route::get('/supplier', [SuppliersController::class, 'index']);
Route::get('/supplier/{supplier}', [SuppliersController::class, 'show']);
Route::post('/supplier', [SuppliersController::class, 'store']);
Route::put('/supplier/{supplier}', [SuppliersController::class, 'update']);
Route::delete('/supplier/{supplier}', [SuppliersController::class, 'destroy']);
