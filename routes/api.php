<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Order_detailsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Product__reviewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Shipping_AddressController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//user controller
Route::middleware('auth:sanctum')->group(function(){
    //user controller
    Route::post('/logout',[UserController::class,'logout']);
    Route::post('/update-email',[UserController::class,'updateEmail']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    //  wishlist controller 
    Route::post('/product/{productId}/favorite',[WishlistController::class,'Favorite']);
    Route::delete('/product/{productId}/favorite',[WishlistController::class,'removFavorite']);
    Route::get('/wishlist',[WishlistController::class,'showlist']);
    //order controller
    Route::get('/myorders',[OrderController::class,'userOrders']);
    //address
    Route::post('/address',[Shipping_AddressController::class,'addAddress']);
    Route::delete('/address/{id}',[Shipping_AddressController::class,'destroy']);
    Route::get('/myaddress',[Shipping_AddressController::class,'MyAddress']);
    Route::put('/updateaddress/{id}',[Shipping_AddressController::class,'updateAddress']);
    // product reviews 
    Route::post('/product/{product_id}/reviews',[Product__reviewsController::class,'store']);

    // order controller
    Route::post('/create-order', [OrderController::class, 'createOrder']);
    // profile controller
    Route::put('/update-profile',[ProfileController::class,'updateprofile']);
    Route::get('/show-profile',[ProfileController::class,'show']);
});
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
//product controller
Route::get('/products',[ProductController::class,'index']);
Route::get('/product/{id}',[ProductController::class,'show']);
Route::post('/card/{id}/{quantity}',[ProductController::class,'addToCart']);
Route::get('/search/{query}',[ProductController::class,'search']);
//order
Route::get('/orders',[Order_detailsController::class,'index']);
//order details
Route::get('/details/{id}',[Order_detailsController::class,'orderdetails']);
// category
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/category/{id}',[CategoryController::class,'show']);






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
