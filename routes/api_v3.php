<?php


use App\Http\Controllers\ApiV3\AuthController;
use App\Http\Controllers\ApiV3\productsController;
use Illuminate\Support\Facades\Route;



Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('register', [AuthController::class, 'register'])->name('api.register');
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('me', [AuthController::class, 'me']);




Route::get('all-services', [productsController::class, 'services']);
Route::get('products-service/{serviceId}', [productsController::class, 'products']);
Route::get('products-details/{productId}', [productsController::class, 'productDetails']);