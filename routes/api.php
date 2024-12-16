<?php

use App\Http\Controllers\FavoriteProductsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthDriverController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/',function (){
    return 'hello';
});
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/stores/search/{name}', [StoresController::class, 'search']);
Route::get('/stores', [StoresController::class, 'index']);
Route::get('/store/products/{id}',[StoresController::class,'show']);


Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);
Route::get('/products/search/{name}', [ProductsController::class, 'search']);


Route::post('/drivers/contact', [OrderController::class, 'contactDriver']);

Route::group(['middleware'=>['auth:sanctum']],function ()  {
    Route::get('cart', [CartController::class, 'getCart']);
    Route::post('cart/decrease', [CartController::class, 'decreaseItems']);
    Route::post('cart/add', [CartController::class, 'addToCart']);
    Route::delete('cart/remove', [CartController::class, 'removeFromCart']);
    Route::delete('cart/remove/all', [CartController::class, 'removeCart']);
    Route::post('cart/buy', [CartController::class, 'buy']);
    Route::post('cart/unbuy/{order}', [CartController::class, 'unBuy']);

    Route::post('/users/favorite/{id}',[FavoriteProductsController::class,'favorite']);
    Route::post('/users/unfavorite/{id}',[FavoriteProductsController::class,'unfavorite']);
    Route::post('/users/favorites',[FavoriteProductsController::class,'favorites']);
    
    Route::get('/user/orders', [OrderController::class, 'getOrders']);
    Route::get('/orders/accepted', [OrderController::class, 'getAcceptedOrders']);
    Route::get('/orders/pinding', [OrderController::class, 'getPindingOrders']);
    Route::get('/orders/driver/current', [OrderController::class, 'currentOrders']);
    Route::post('/orders/status/{order}', [OrderController::class, 'changeStatus']);
    
    Route::post('/logout', [AuthController::class, 'logout']);

    //

});




Route::post('/driver/register', [AuthDriverController::class, 'register']);
Route::post('/driver/login', [AuthDriverController::class, 'login']);



Route::group(['middleware'=>['auth:driver']],function (){
    Route::post('/orders/accept/{order}', [OrderController::class, 'acceptOrder']);
    Route::post('/orders/decline/{order}', [OrderController::class, 'declineOrder']);
    Route::get('/orders',[OrderController::class,'index']);
    //////////////

    Route::post('/driver/logout', [AuthDriverController::class, 'logout']);
});