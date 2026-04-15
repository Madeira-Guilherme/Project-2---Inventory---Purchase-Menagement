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
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/products', [ProductsController::class, 'index']);
    Route::get('/products/{product}', [ProductsController::class, 'show']);
    Route::post('/products', [ProductsController::class, 'store'])
        ->middleware('permission:create products');
    Route::put('/products/{product}', [ProductsController::class, 'update'])
        ->middleware('permission:update products');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy']);
    Route::post('/products/{id}/restore', [ProductsController::class, 'restore']);

    Route::get('/suppliers', [SuppliersController::class, 'index']);
    Route::post('/suppliers', [SuppliersController::class, 'store']);
    Route::get('/suppliers/{supplier}', [SuppliersController::class, 'show']);
    Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update']);
    Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy']);
    Route::post('/suppliers/{id}/restore', [SuppliersController::class, 'restore']);

    Route::get('/purchaseorders', [PurchaseOrdersController::class, 'index']);
    Route::post('/purchaseorders', [PurchaseOrdersController::class, 'store'])
        ->middleware('permission:create orders');
    Route::get('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'show']);
    Route::put('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'update'])
        ->middleware('permission:update orders');
    Route::delete('/purchaseorders/{purchases}', [PurchaseOrdersController::class, 'destroy']);

    Route::post('/purchaseorders/{id}/restore', [PurchaseOrdersController::class, 'restore']);

    Route::post('/purchaseorders/{id}/submit', [PurchaseOrdersController::class, 'submit'])
        ->middleware('permission:mark orders');
    Route::post('/purchaseorders/{id}/receive', [PurchaseOrdersController::class, 'receive'])
        ->middleware('permission:mark orders');
    Route::post('/purchaseorders/{id}/cancel', [PurchaseOrdersController::class, 'cancel'])
        ->middleware('permission:mark orders');
});


//Teste
