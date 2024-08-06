<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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


Route::group(['middleware' => 'api'], function($router) {
    Route::get('/', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    })->name('api.hello');
});

Route::group(['middleware' =>  ['jwt.auth'],'prefix' => 'auth'], function ($router) {
    
Route::get('/cart_product', 'App\Http\Controllers\Api\Auth\CartProductController@index')->name('api.auth.index.cart_product');
Route::get('/cart_product/{id}', 'App\Http\Controllers\Api\Auth\CartProductController@show')->name('api.auth.show.cart_product');
Route::post('/cart_product', 'App\Http\Controllers\Api\Auth\CartProductController@store')->name('api.auth.store.cart_product');
Route::put('/cart_product/{id}', 'App\Http\Controllers\Api\Auth\CartProductController@update')->name('api.auth.update.cart_product');
Route::delete('/cart_product/{id}', 'App\Http\Controllers\Api\Auth\CartProductController@destroy')->name('api.auth.delete.cart_product');
Route::get('/cart_product/search/{search}', 'App\Http\Controllers\Api\Auth\CartProductController@search')->name('api.auth.search.cart_product');
Route::get('/category', 'App\Http\Controllers\Api\Auth\CategoryController@index')->name('api.auth.index.category');
Route::get('/category/{id}', 'App\Http\Controllers\Api\Auth\CategoryController@show')->name('api.auth.show.category');
Route::post('/category', 'App\Http\Controllers\Api\Auth\CategoryController@store')->name('api.auth.store.category');
Route::put('/category/{id}', 'App\Http\Controllers\Api\Auth\CategoryController@update')->name('api.auth.update.category');
Route::delete('/category/{id}', 'App\Http\Controllers\Api\Auth\CategoryController@destroy')->name('api.auth.delete.category');
Route::get('/category/search/{search}', 'App\Http\Controllers\Api\Auth\CategoryController@search')->name('api.auth.search.category');
Route::get('/chat_message', 'App\Http\Controllers\Api\Auth\ChatMessageController@index')->name('api.auth.index.chat_message');
Route::get('/chat_message/{id}', 'App\Http\Controllers\Api\Auth\ChatMessageController@show')->name('api.auth.show.chat_message');
Route::post('/chat_message', 'App\Http\Controllers\Api\Auth\ChatMessageController@store')->name('api.auth.store.chat_message');
Route::put('/chat_message/{id}', 'App\Http\Controllers\Api\Auth\ChatMessageController@update')->name('api.auth.update.chat_message');
Route::delete('/chat_message/{id}', 'App\Http\Controllers\Api\Auth\ChatMessageController@destroy')->name('api.auth.delete.chat_message');
Route::get('/chat_message/search/{search}', 'App\Http\Controllers\Api\Auth\ChatMessageController@search')->name('api.auth.search.chat_message');
Route::get('/chat_user', 'App\Http\Controllers\Api\Auth\ChatUserController@index')->name('api.auth.index.chat_user');
Route::get('/chat_user/{id}', 'App\Http\Controllers\Api\Auth\ChatUserController@show')->name('api.auth.show.chat_user');
Route::post('/chat_user', 'App\Http\Controllers\Api\Auth\ChatUserController@store')->name('api.auth.store.chat_user');
Route::put('/chat_user/{id}', 'App\Http\Controllers\Api\Auth\ChatUserController@update')->name('api.auth.update.chat_user');
Route::delete('/chat_user/{id}', 'App\Http\Controllers\Api\Auth\ChatUserController@destroy')->name('api.auth.delete.chat_user');
Route::get('/chat_user/search/{search}', 'App\Http\Controllers\Api\Auth\ChatUserController@search')->name('api.auth.search.chat_user');
Route::get('/city', 'App\Http\Controllers\Api\Auth\CityController@index')->name('api.auth.index.city');
Route::get('/city/{id}', 'App\Http\Controllers\Api\Auth\CityController@show')->name('api.auth.show.city');
Route::post('/city', 'App\Http\Controllers\Api\Auth\CityController@store')->name('api.auth.store.city');
Route::put('/city/{id}', 'App\Http\Controllers\Api\Auth\CityController@update')->name('api.auth.update.city');
Route::delete('/city/{id}', 'App\Http\Controllers\Api\Auth\CityController@destroy')->name('api.auth.delete.city');
Route::get('/city/search/{search}', 'App\Http\Controllers\Api\Auth\CityController@search')->name('api.auth.search.city');
Route::get('/fav_gallery_image', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@index')->name('api.auth.index.fav_gallery_image');
Route::get('/fav_gallery_image/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@show')->name('api.auth.show.fav_gallery_image');
Route::post('/fav_gallery_image', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@store')->name('api.auth.store.fav_gallery_image');
Route::put('/fav_gallery_image/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@update')->name('api.auth.update.fav_gallery_image');
Route::delete('/fav_gallery_image/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@destroy')->name('api.auth.delete.fav_gallery_image');
Route::get('/fav_gallery_image/search/{search}', 'App\Http\Controllers\Api\Auth\FavGalleryImageController@search')->name('api.auth.search.fav_gallery_image');
Route::get('/fav_gallery_video', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@index')->name('api.auth.index.fav_gallery_video');
Route::get('/fav_gallery_video/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@show')->name('api.auth.show.fav_gallery_video');
Route::post('/fav_gallery_video', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@store')->name('api.auth.store.fav_gallery_video');
Route::put('/fav_gallery_video/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@update')->name('api.auth.update.fav_gallery_video');
Route::delete('/fav_gallery_video/{id}', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@destroy')->name('api.auth.delete.fav_gallery_video');
Route::get('/fav_gallery_video/search/{search}', 'App\Http\Controllers\Api\Auth\FavGalleryVideoController@search')->name('api.auth.search.fav_gallery_video');
Route::get('/fav_product', 'App\Http\Controllers\Api\Auth\FavProductController@index')->name('api.auth.index.fav_product');
Route::get('/fav_product/{id}', 'App\Http\Controllers\Api\Auth\FavProductController@show')->name('api.auth.show.fav_product');
Route::post('/fav_product', 'App\Http\Controllers\Api\Auth\FavProductController@store')->name('api.auth.store.fav_product');
Route::put('/fav_product/{id}', 'App\Http\Controllers\Api\Auth\FavProductController@update')->name('api.auth.update.fav_product');
Route::delete('/fav_product/{id}', 'App\Http\Controllers\Api\Auth\FavProductController@destroy')->name('api.auth.delete.fav_product');
Route::get('/fav_product/search/{search}', 'App\Http\Controllers\Api\Auth\FavProductController@search')->name('api.auth.search.fav_product');
Route::get('/fav_provider', 'App\Http\Controllers\Api\Auth\FavProviderController@index')->name('api.auth.index.fav_provider');
Route::get('/fav_provider/{id}', 'App\Http\Controllers\Api\Auth\FavProviderController@show')->name('api.auth.show.fav_provider');
Route::post('/fav_provider', 'App\Http\Controllers\Api\Auth\FavProviderController@store')->name('api.auth.store.fav_provider');
Route::put('/fav_provider/{id}', 'App\Http\Controllers\Api\Auth\FavProviderController@update')->name('api.auth.update.fav_provider');
Route::delete('/fav_provider/{id}', 'App\Http\Controllers\Api\Auth\FavProviderController@destroy')->name('api.auth.delete.fav_provider');
Route::get('/fav_provider/search/{search}', 'App\Http\Controllers\Api\Auth\FavProviderController@search')->name('api.auth.search.fav_provider');
Route::get('/gallery_image', 'App\Http\Controllers\Api\Auth\GalleryImageController@index')->name('api.auth.index.gallery_image');
Route::get('/gallery_image/{id}', 'App\Http\Controllers\Api\Auth\GalleryImageController@show')->name('api.auth.show.gallery_image');
Route::post('/gallery_image', 'App\Http\Controllers\Api\Auth\GalleryImageController@store')->name('api.auth.store.gallery_image');
Route::put('/gallery_image/{id}', 'App\Http\Controllers\Api\Auth\GalleryImageController@update')->name('api.auth.update.gallery_image');
Route::delete('/gallery_image/{id}', 'App\Http\Controllers\Api\Auth\GalleryImageController@destroy')->name('api.auth.delete.gallery_image');
Route::get('/gallery_image/search/{search}', 'App\Http\Controllers\Api\Auth\GalleryImageController@search')->name('api.auth.search.gallery_image');
Route::get('/gallery_video', 'App\Http\Controllers\Api\Auth\GalleryVideoController@index')->name('api.auth.index.gallery_video');
Route::get('/gallery_video/{id}', 'App\Http\Controllers\Api\Auth\GalleryVideoController@show')->name('api.auth.show.gallery_video');
Route::post('/gallery_video', 'App\Http\Controllers\Api\Auth\GalleryVideoController@store')->name('api.auth.store.gallery_video');
Route::put('/gallery_video/{id}', 'App\Http\Controllers\Api\Auth\GalleryVideoController@update')->name('api.auth.update.gallery_video');
Route::delete('/gallery_video/{id}', 'App\Http\Controllers\Api\Auth\GalleryVideoController@destroy')->name('api.auth.delete.gallery_video');
Route::get('/gallery_video/search/{search}', 'App\Http\Controllers\Api\Auth\GalleryVideoController@search')->name('api.auth.search.gallery_video');
Route::get('/notification_admin', 'App\Http\Controllers\Api\Auth\NotificationAdminController@index')->name('api.auth.index.notification_admin');
Route::get('/notification_admin/{id}', 'App\Http\Controllers\Api\Auth\NotificationAdminController@show')->name('api.auth.show.notification_admin');
Route::post('/notification_admin', 'App\Http\Controllers\Api\Auth\NotificationAdminController@store')->name('api.auth.store.notification_admin');
Route::put('/notification_admin/{id}', 'App\Http\Controllers\Api\Auth\NotificationAdminController@update')->name('api.auth.update.notification_admin');
Route::delete('/notification_admin/{id}', 'App\Http\Controllers\Api\Auth\NotificationAdminController@destroy')->name('api.auth.delete.notification_admin');
Route::get('/notification_admin/search/{search}', 'App\Http\Controllers\Api\Auth\NotificationAdminController@search')->name('api.auth.search.notification_admin');
Route::get('/order_product', 'App\Http\Controllers\Api\Auth\OrderProductController@index')->name('api.auth.index.order_product');
Route::get('/order_product/{id}', 'App\Http\Controllers\Api\Auth\OrderProductController@show')->name('api.auth.show.order_product');
Route::post('/order_product', 'App\Http\Controllers\Api\Auth\OrderProductController@store')->name('api.auth.store.order_product');
Route::put('/order_product/{id}', 'App\Http\Controllers\Api\Auth\OrderProductController@update')->name('api.auth.update.order_product');
Route::delete('/order_product/{id}', 'App\Http\Controllers\Api\Auth\OrderProductController@destroy')->name('api.auth.delete.order_product');
Route::get('/order_product/search/{search}', 'App\Http\Controllers\Api\Auth\OrderProductController@search')->name('api.auth.search.order_product');
Route::get('/order_vendor', 'App\Http\Controllers\Api\Auth\OrderVendorController@index')->name('api.auth.index.order_vendor');
Route::get('/order_vendor/{id}', 'App\Http\Controllers\Api\Auth\OrderVendorController@show')->name('api.auth.show.order_vendor');
Route::post('/order_vendor', 'App\Http\Controllers\Api\Auth\OrderVendorController@store')->name('api.auth.store.order_vendor');
Route::put('/order_vendor/{id}', 'App\Http\Controllers\Api\Auth\OrderVendorController@update')->name('api.auth.update.order_vendor');
Route::delete('/order_vendor/{id}', 'App\Http\Controllers\Api\Auth\OrderVendorController@destroy')->name('api.auth.delete.order_vendor');
Route::get('/order_vendor/search/{search}', 'App\Http\Controllers\Api\Auth\OrderVendorController@search')->name('api.auth.search.order_vendor');
Route::get('/organization', 'App\Http\Controllers\Api\Auth\OrganizationController@index')->name('api.auth.index.organization');
Route::get('/organization/{id}', 'App\Http\Controllers\Api\Auth\OrganizationController@show')->name('api.auth.show.organization');
Route::post('/organization', 'App\Http\Controllers\Api\Auth\OrganizationController@store')->name('api.auth.store.organization');
Route::put('/organization/{id}', 'App\Http\Controllers\Api\Auth\OrganizationController@update')->name('api.auth.update.organization');
Route::delete('/organization/{id}', 'App\Http\Controllers\Api\Auth\OrganizationController@destroy')->name('api.auth.delete.organization');
Route::get('/organization/search/{search}', 'App\Http\Controllers\Api\Auth\OrganizationController@search')->name('api.auth.search.organization');
Route::get('/password_resets', 'App\Http\Controllers\Api\Auth\PasswordResetsController@index')->name('api.auth.index.password_resets');
Route::get('/password_resets/{id}', 'App\Http\Controllers\Api\Auth\PasswordResetsController@show')->name('api.auth.show.password_resets');
Route::post('/password_resets', 'App\Http\Controllers\Api\Auth\PasswordResetsController@store')->name('api.auth.store.password_resets');
Route::put('/password_resets/{id}', 'App\Http\Controllers\Api\Auth\PasswordResetsController@update')->name('api.auth.update.password_resets');
Route::delete('/password_resets/{id}', 'App\Http\Controllers\Api\Auth\PasswordResetsController@destroy')->name('api.auth.delete.password_resets');
Route::get('/password_resets/search/{search}', 'App\Http\Controllers\Api\Auth\PasswordResetsController@search')->name('api.auth.search.password_resets');
Route::get('/product', 'App\Http\Controllers\Api\Auth\ProductController@index')->name('api.auth.index.product');
Route::get('/product/{id}', 'App\Http\Controllers\Api\Auth\ProductController@show')->name('api.auth.show.product');
Route::post('/product', 'App\Http\Controllers\Api\Auth\ProductController@store')->name('api.auth.store.product');
Route::put('/product/{id}', 'App\Http\Controllers\Api\Auth\ProductController@update')->name('api.auth.update.product');
Route::delete('/product/{id}', 'App\Http\Controllers\Api\Auth\ProductController@destroy')->name('api.auth.delete.product');

Route::get('/provider', 'App\Http\Controllers\Api\Auth\ProviderController@index')->name('api.auth.index.provider');
Route::get('/provider/{id}', 'App\Http\Controllers\Api\Auth\ProviderController@show')->name('api.auth.show.provider');
Route::post('/provider', 'App\Http\Controllers\Api\Auth\ProviderController@store')->name('api.auth.store.provider');
Route::put('/provider/{id}', 'App\Http\Controllers\Api\Auth\ProviderController@update')->name('api.auth.update.provider');
Route::delete('/provider/{id}', 'App\Http\Controllers\Api\Auth\ProviderController@destroy')->name('api.auth.delete.provider');
Route::get('/provider/search/{search}', 'App\Http\Controllers\Api\Auth\ProviderController@search')->name('api.auth.search.provider');
Route::get('/rate_product', 'App\Http\Controllers\Api\Auth\RateProductController@index')->name('api.auth.index.rate_product');
Route::get('/rate_product/{id}', 'App\Http\Controllers\Api\Auth\RateProductController@show')->name('api.auth.show.rate_product');
Route::post('/rate_product', 'App\Http\Controllers\Api\Auth\RateProductController@store')->name('api.auth.store.rate_product');
Route::put('/rate_product/{id}', 'App\Http\Controllers\Api\Auth\RateProductController@update')->name('api.auth.update.rate_product');
Route::delete('/rate_product/{id}', 'App\Http\Controllers\Api\Auth\RateProductController@destroy')->name('api.auth.delete.rate_product');
Route::get('/rate_product/search/{search}', 'App\Http\Controllers\Api\Auth\RateProductController@search')->name('api.auth.search.rate_product');
Route::get('/rate_provider', 'App\Http\Controllers\Api\Auth\RateProviderController@index')->name('api.auth.index.rate_provider');
Route::get('/rate_provider/{id}', 'App\Http\Controllers\Api\Auth\RateProviderController@show')->name('api.auth.show.rate_provider');
Route::post('/rate_provider', 'App\Http\Controllers\Api\Auth\RateProviderController@store')->name('api.auth.store.rate_provider');
Route::put('/rate_provider/{id}', 'App\Http\Controllers\Api\Auth\RateProviderController@update')->name('api.auth.update.rate_provider');
Route::delete('/rate_provider/{id}', 'App\Http\Controllers\Api\Auth\RateProviderController@destroy')->name('api.auth.delete.rate_provider');
Route::get('/rate_provider/search/{search}', 'App\Http\Controllers\Api\Auth\RateProviderController@search')->name('api.auth.search.rate_provider');
Route::get('/shipment', 'App\Http\Controllers\Api\Auth\ShipmentController@index')->name('api.auth.index.shipment');
Route::get('/shipment/{id}', 'App\Http\Controllers\Api\Auth\ShipmentController@show')->name('api.auth.show.shipment');
Route::post('/shipment', 'App\Http\Controllers\Api\Auth\ShipmentController@store')->name('api.auth.store.shipment');
Route::put('/shipment/{id}', 'App\Http\Controllers\Api\Auth\ShipmentController@update')->name('api.auth.update.shipment');
Route::delete('/shipment/{id}', 'App\Http\Controllers\Api\Auth\ShipmentController@destroy')->name('api.auth.delete.shipment');
Route::get('/shipment/search/{search}', 'App\Http\Controllers\Api\Auth\ShipmentController@search')->name('api.auth.search.shipment');
Route::get('/sliders', 'App\Http\Controllers\Api\Auth\SlidersController@index')->name('api.auth.index.sliders');
Route::get('/sliders/{id}', 'App\Http\Controllers\Api\Auth\SlidersController@show')->name('api.auth.show.sliders');
Route::post('/sliders', 'App\Http\Controllers\Api\Auth\SlidersController@store')->name('api.auth.store.sliders');
Route::put('/sliders/{id}', 'App\Http\Controllers\Api\Auth\SlidersController@update')->name('api.auth.update.sliders');
Route::delete('/sliders/{id}', 'App\Http\Controllers\Api\Auth\SlidersController@destroy')->name('api.auth.delete.sliders');
Route::get('/sliders/search/{search}', 'App\Http\Controllers\Api\Auth\SlidersController@search')->name('api.auth.search.sliders');
Route::get('/users', 'App\Http\Controllers\Api\Auth\UsersController@index')->name('api.auth.index.users');
Route::get('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@show')->name('api.auth.show.users');
Route::post('/users', 'App\Http\Controllers\Api\Auth\UsersController@store')->name('api.auth.store.users');
Route::put('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@update')->name('api.auth.update.users');
Route::delete('/users/{id}', 'App\Http\Controllers\Api\Auth\UsersController@destroy')->name('api.auth.delete.users');
Route::get('/users/search/{search}', 'App\Http\Controllers\Api\Auth\UsersController@search')->name('api.auth.search.users');
   
Route::get('/setting_admin', 'App\Http\Controllers\Api\Auth\SettingAdminController@index')->name('api.auth.index.setting_admin');
Route::get('/setting_admin/{id}', 'App\Http\Controllers\Api\Auth\SettingAdminController@show')->name('api.auth.show.setting_admin');
Route::post('/setting_admin', 'App\Http\Controllers\Api\Auth\SettingAdminController@store')->name('api.auth.store.setting_admin');
Route::put('/setting_admin/{id}', 'App\Http\Controllers\Api\Auth\SettingAdminController@update')->name('api.auth.update.setting_admin');
Route::delete('/setting_admin/{id}', 'App\Http\Controllers\Api\Auth\SettingAdminController@destroy')->name('api.auth.delete.setting_admin');
Route::get('/setting_admin/search/{search}', 'App\Http\Controllers\Api\Auth\SettingAdminController@search')->name('api.auth.search.setting_admin');


//subscribe_package
// Route::get('/subscribe_package', 'App\Http\Controllers\Api\Auth\SubscribePackageController@index')->name('api.auth.index.subscribe_package');
Route::get('/subscribe_package/{id}', 'App\Http\Controllers\Api\Auth\SubscribePackageController@show')->name('api.auth.show.subscribe_package');
Route::post('/subscribe_package', 'App\Http\Controllers\Api\Auth\SubscribePackageController@store')->name('api.auth.store.subscribe_package');
Route::put('/subscribe_package/{id}', 'App\Http\Controllers\Api\Auth\SubscribePackageController@update')->name('api.auth.update.subscribe_package');
Route::delete('/subscribe_package/{id}', 'App\Http\Controllers\Api\Auth\SubscribePackageController@destroy')->name('api.auth.delete.subscribe_package');
Route::get('/subscribe_package/search/{search}', 'App\Http\Controllers\Api\Auth\SubscribePackageController@search')->name('api.auth.search.subscribe_package');

//subscribe_user
Route::get('/subscribe_user', 'App\Http\Controllers\Api\Auth\SubscribeUserController@index')->name('api.auth.index.subscribe_user');
// Route::get('/subscribe_user/{id}', 'App\Http\Controllers\Api\Auth\SubscribeUserController@show')->name('api.auth.show.subscribe_user');
// Route::post('/subscribe_user', 'App\Http\Controllers\Api\Auth\SubscribeUserController@store')->name('api.auth.store.subscribe_user');
// Route::put('/subscribe_user/{id}', 'App\Http\Controllers\Api\Auth\SubscribeUserController@update')->name('api.auth.update.subscribe_user');
// Route::delete('/subscribe_user/{id}', 'App\Http\Controllers\Api\Auth\SubscribeUserController@destroy')->name('api.auth.delete.subscribe_user');
// Route::get('/subscribe_user/search/{search}', 'App\Http\Controllers\Api\Auth\SubscribeUserController@search')->name('api.auth.search.subscribe_user');
   
//contact us
Route::get('/contact_us', 'App\Http\Controllers\Api\Auth\ContactUsController@index')->name('api.auth.index.contact_us');
Route::get('/contact_us/{id}', 'App\Http\Controllers\Api\Auth\ContactUsController@show')->name('api.auth.show.contact_us');
Route::post('/contact_us', 'App\Http\Controllers\Api\Auth\ContactUsController@store')->name('api.auth.store.contact_us');
Route::put('/contact_us/{id}', 'App\Http\Controllers\Api\Auth\ContactUsController@update')->name('api.auth.update.contact_us');
Route::delete('/contact_us/{id}', 'App\Http\Controllers\Api\Auth\ContactUsController@destroy')->name('api.auth.delete.contact_us');
Route::get('/contact_us/search/{search}', 'App\Http\Controllers\Api\Auth\ContactUsController@search')->name('api.auth.search.contact_us');

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});


