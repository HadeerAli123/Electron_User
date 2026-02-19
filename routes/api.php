<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InstallmentRequestController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

});


Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/tree', [CategoryController::class, 'getTree']);
    Route::get('/parents', [CategoryController::class, 'getParents']);

    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::get('/{id}/children', [CategoryController::class, 'getWithChildren']);
    Route::get('/{id}/products', [CategoryController::class, 'getWithProducts']);

    Route::get('/parent/{parentId}', [CategoryController::class, 'getChildren']);

    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::patch('/{id}', [CategoryController::class, 'update']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('categories/{id}', [CategoryController::class, 'update']);
});


    Route::post('categories', [CategoryController::class, 'store']);


    Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/category/{categoryId}', [ProductController::class, 'getByCategory']);
    Route::get('/{id}', [ProductController::class, 'show']);
});





Route::prefix('installments')->group(function () {

   
    
   
    Route::get('/plans/{planId}/products', [InstallmentRequestController::class, 'getPlanWithProducts']);

    Route::get('/products/{productId}/plans', [InstallmentRequestController::class, 'getProductPlans']);

  
    Route::get('/requests/{requestId}', [InstallmentRequestController::class, 'getRequest']);

  
    Route::post('/requests', [InstallmentRequestController::class, 'createRequest']);
});
