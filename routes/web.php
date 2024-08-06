<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\productsController;
use App\Http\Controllers\Dashboard\providerController;
use App\Http\Controllers\Dashboard\servicesController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/', function () {
    return "admin";
});

Route::get('/about/{theSubject}', function ($theSubject) {
    return $theSubject . ' content goes here.';
});


Route::get('login', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('custom-login', [AuthController::class, 'customLogin'])->name('login.custom');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    //services 
    Route::get('services', [servicesController::class, 'index'])->name('dashboard');
    Route::post('add/services', [servicesController::class, 'store'])->name('addServices');
    Route::post('delete/services/{id}', [servicesController::class, 'delete'])->name('deleteService');

    //products
    Route::get('products', [productsController::class, 'index'])->name('products');
    Route::post('add/products', [productsController::class, 'store'])->name('addProducts');
    Route::post('delete/products/{id}', [productsController::class, 'delete'])->name('deleteProducts');

    //allProviders
    Route::get('all/users/providers', [providerController::class, 'index'])->name('userProvider');

});





