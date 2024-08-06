<?php

 
use App\Models\ChatUser;
use Illuminate\Http\Request;
use App\Models\SubscribePackage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\Auth\ChatController;
use App\Http\Controllers\Api\Auth\CityController;
use App\Http\Controllers\Api\Auth\UsersController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\Api\Auth\ProductController;
use App\Http\Controllers\Api\Auth\SlidersController;
use App\Http\Controllers\Api\Abdallah\FileController;
use App\Http\Controllers\Api\Auth\CategoryController;
use App\Http\Controllers\Api\Auth\ChatUserController;
use App\Http\Controllers\Api\Auth\ProviderController;
use App\Http\Controllers\Api\Auth\ShipmentController;
use App\Http\Controllers\Api\Auth\ContactUsController;
use App\Http\Controllers\Api\Auth\DashboardController;
use App\Http\Controllers\Api\Auth\ChatMessageController;
use App\Http\Controllers\Api\Auth\OrganizationController;
use App\Http\Controllers\Api\Auth\SettingAdminController;
use App\Http\Controllers\Api\Auth\SubscribeUserController;
use App\Http\Controllers\Api\Auth\SubscribePackageController;
use App\Http\Controllers\Api\Auth\NotificationAdminController;

/**
 abdallah:
   write "RouteServiceProvider" this new route

 */



//-------------------------------------------------------------------  must to be login
 
Route::group(['prefix' => 'auth', 'middleware' =>  ['jwt.auth'] ], function ($router) {

    //cart
    Route::post('/cart_product/increment', 'App\Http\Controllers\Api\Auth\CartProductController@increment')->name('api.auth.store.cart_product');
    Route::post('/cart_product/decrement', 'App\Http\Controllers\Api\Auth\CartProductController@decrement')->name('api.auth.store.cart_product');
    Route::post('/cart_product/cancel', 'App\Http\Controllers\Api\Auth\CartProductController@cancel')->name('api.auth.index.cart_product');
    Route::get('/cart_product/cart_products', 'App\Http\Controllers\Api\Auth\CartProductController@cart_products')->name('api.auth.index.cart_product');
    Route::get('/cart_product/badge_counter', 'App\Http\Controllers\Api\Auth\CartProductController@badge_counter')->name('api.auth.index.cart_product');
    Route::get('/cart_product/all_provider', 'App\Http\Controllers\Api\Auth\CartProductController@all_provider')->name('api.auth.index.cart_product');
    Route::get('/cart_product/cart_providers', 'App\Http\Controllers\Api\Auth\CartProductController@cart_providers')->name('api.auth.index.cart_product');
    Route::get('/cart_product/provider_specific_cart', 'App\Http\Controllers\Api\Auth\CartProductController@provider_specific_cart')->name('api.auth.index.cart_product');
    
    
    //file
    Route::post('/file-upload', [FileController::class, 'upload']); 
    Route::post('/file-generate', [FileController::class, 'generate']); 
    
    //users
   Route::post('/users/photo', [UsersController::class, 'photo']) ;  
   Route::post('/users/search_text', [ UsersController::class, 'search_text'] );  
   Route::post('/user/block', [ UsersController::class, 'block'] );  
   Route::post('/user/hidden', [ UsersController::class, 'hidden'] );  
   Route::post('/user/delete', [ UsersController::class, 'delete'] );  
   Route::post('/user/updateData', [ UsersController::class, 'updateData'] );  
   Route::post('/user/createWaitUser', [ UsersController::class, 'createWaitUser'] );  
   Route::post('/provider/search_text', [ UsersController::class, 'search_text'] );  

  //password admin
  Route::post('/users/passwordGenerate', [UsersController::class, 'passwordGenerate']) ; 

  //city
  Route::post('/city/hidden', [ CityController::class, 'hidden'] );  
  Route::post('/city/updateData', [ CityController::class, 'updateData'] );  
  
  //org
  Route::post('/org/hidden', [ OrganizationController::class, 'hidden'] );  
  Route::post('/org/updateData', [ OrganizationController::class, 'updateData'] );  

  //shipment
  Route::post('/shipment/hidden', [ ShipmentController::class, 'hidden'] );  
  Route::post('/shipment/updateData', [ ShipmentController::class, 'updateData'] );  

  //sliders
  Route::post('/sliders/hidden', [ SlidersController::class, 'hidden'] );  
  Route::post('/sliders/updateData', [ SlidersController::class, 'updateData'] );  

  //setting admin
  Route::post('/setting_admin/updateAboutUsAndTerms', [ SettingAdminController::class, 'updateAboutUsAndTerms'] );  
  
  //Dashboard
  Route::get('/dashboard/admin/counter_analytics', [ DashboardController::class, 'admin_counter_analytics'] );  

  //notification  
  Route::get('/notification_admin/getCreatedByAdminListNotification', [ NotificationAdminController::class, 'getCreatedByAdminListNotification'] );  
  Route::get('/notification_normal/getNormalUserListNotification', [ NotificationAdminController::class, 'getNormalUserListNotification'] );  
  
  
    //subsribe user
    Route::post('/subscribe_user/current_user_subscibe', [ SubscribeUserController::class, 'current_user_subscibe'] );  
    Route::post('/subscribe_user/store_by_admin', [ SubscribeUserController::class, 'store_by_admin'] );  
    Route::post('/subscribe_user/store_by_visa', [ SubscribeUserController::class, 'store_by_visa'] );  
    Route::post('/subscribe_user/store_at_free_package', [ SubscribeUserController::class, 'store_at_free_package'] );  

    //product
    Route::post('/product/search_by_word',  [ProductController::class, 'search_by_word']);
    
  /////////////////////////////////////// chat

  //chat users
  Route::get('/chatUser/chatWithMeUsers', [ ChatUserController::class, 'chatWithMeUsers'] ); 

  //message 
  Route::get('/chatMessage/getMessageWithSpecificUser', [ ChatMessageController::class, 'getMessageWithSpecificUser'] ); 
  Route::post('/chatMessage/createMessageWithSpecificUser', [ ChatMessageController::class, 'createMessageWithSpecificUser'] ); 
  Route::post('/chatMessage/updateStatus', [ ChatMessageController::class, 'updateStatus'] ); 

  //last message "sync"
  Route::post('/ChatBoth/getLastUpdateCache', [ ChatController::class, 'getLastUpdateCache'] ); 
  Route::post('/ChatMessage/getLastUpdateCache', [ ChatMessageController::class, 'getLastUpdateCache'] ); 
  Route::post('/ChatUser/getLastUpdateCache', [ ChatUserController::class, 'getLastUpdateCache'] ); 
  Route::post('/ChatBoth/getLastUpdateWithSpecificUser', [ ChatController::class, 'getLastUpdateWithSpecificUser'] ); 
  Route::post('/ChatBoth/getSocketContinue', [ ChatController::class, 'getSocketContinue'] ); 
  
});



  //------------------------------------------------------------------- public data (guest user )
 
  //home
  Route::get('/home', [HomeController::class, 'home']);

  //product
  Route::get('/product/{id}', [ProductController::class, 'show']);
  Route::get('/product/test', [ProductController::class, 'test']);
  Route::post('/product/filter', [ProductController::class, 'filter']);


  //provider
  Route::post('/provider/filter', [ProviderController::class, 'filter']);
  Route::get('/provider/show', [ProviderProfileController::class, 'show']);
   Route::get('/provider/show_provider_by_userid', [ ProviderController::class, 'show_provider_by_userid'] );  
 
  //option data
  Route::get('/category', [CategoryController::class, 'index']);
  Route::get('/organization', [OrganizationController::class, 'index']);
  Route::get('/city', [CityController::class, 'index']);

  //setting admin
  Route::get('/setting_admin/getPublicData', [ SettingAdminController::class, 'getPublicData'] );  
  Route::get('/setting_admin/getPaymentSetting', [ SettingAdminController::class, 'getPaymentSetting'] );  
  

  // subscribe package
  Route::get('/subscribe_package/get_with_paginate', [ SubscribePackageController::class, 'get_with_paginate'] );  
  Route::get('/subscribe_package/get_all', [ SubscribePackageController::class, 'get_all'] );  

  //contact us
  Route::post('/contactGuest', [ContactUsController::class, 'contactGuest']);

//------------------------------------------------------------------- test
 
Route::get('/about/{theSubject}', function($theSubject) {
  
  return $theSubject.' content goes here.';
});

Route::get('/socket/starter', function() {
  
  return view('socket/starter');  
});