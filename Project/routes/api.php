<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseOrdersController;
use App\Http\Controllers\SuppliersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
| Protected routes (Sanctum)
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{product}', [ProductsController::class, 'show']);
Route::put('/products/{product}', [ProductsController::class, 'update']);
Route::post('/products', [ProductsController::class, 'store']);
Route::delete('/products/{product}', [ProductsController::class, 'destroy']);

Route::get('/suppliers', [SuppliersController::class, 'index']);
Route::post('/suppliers', [SuppliersController::class, 'store']);
Route::get('/suppliers/{supplier}', [SuppliersController::class, 'show']);
Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update']);
Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy']);

Route::get('/purchaseorders', [PurchaseOrdersController::class, 'index']);
Route::post('/purchaseorders', [PurchaseOrdersController::class, 'store']);
Route::get('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'show']);
Route::put('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'update']);
Route::delete('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'destroy']);
Route::post('/purchaseorders/{id}/submit', [PurchaseOrdersController::class, 'submit']);
Route::post('/purchaseorders/{id}/receive', [PurchaseOrdersController::class, 'receive']);
Route::post('/purchaseorders/{id}/cancel', [PurchaseOrdersController::class, 'cancel']);


//Teste
