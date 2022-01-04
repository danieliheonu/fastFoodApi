<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavouriteController;

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

Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);

Route::get("/users", [UserController::class, 'listUsers']);
Route::get("/restaurant", [RestaurantController::class, 'listRestaurants']);
Route::get("/restaurant/{id}/rating", [RatingController::class, 'listRatings']);

Route::get("/category", [CategoryController::class, "viewCategories"]);
Route::post("/category", [CategoryController::class, "createCategory"]);
Route::get("/category/{id}", [CategoryController::class, "viewCategory"]);
Route::put("/category/{id}", [CategoryController::class, "updateCategory"]);
Route::delete("/category/{id}", [CategoryController::class, "deleteCategory"]);

Route::get('/restaurant/{id}/categories', [ProductController::class, 'listRestaurantProductCategories']);
Route::get('/restaurant/{id}/products', [ProductController::class, 'listRestaurantProducts']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::post("/auth/logout", [AuthController::class, 'logout']);

    Route::get("/user/{id}", [UserController::class, 'userDetail']);
    Route::put("/user/{id}", [UserController::class, 'updateUserDetail']);
    Route::delete("/user/{id}", [UserController::class, 'deleteUserDetail']);
    Route::get("/user/{id}/restaurants", [UserController::class, 'userRestaurantList']);
    Route::get("/user/{id}/favourite", [UserController::class, 'userFavouriteRestaurant']);

    Route::post("/restaurant", [RestaurantController::class, 'createRestaurant']);
    Route::get("/restaurant/{id}", [RestaurantController::class, 'restaurantDetail']);
    Route::put('/restaurant/{id}', [RestaurantController::class, 'updateRestaurant']);
    Route::delete('/restaurant/{id}', [RestaurantController::class, 'deleteRestaurant']);

    Route::post('/restaurant/{id}/rating', [RatingController::class, 'rateRestaurant']);
    
    Route::get('/category/{id}/product', [ProductController::class, 'listProductByCategory']);
    Route::post('/restaurant/{id}/product', [ProductController::class, 'createProduct']);
    Route::get('/product/{id}', [ProductController::class, 'viewProduct']);
    Route::put('/product/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/product/{id}', [ProductController::class, 'deleteProduct']);

    Route::post('restaurant/{id}/favourite', [FavouriteController::class, 'addFavourite']);
    Route::delete('restaurant/{id}/favourite', [FavouriteController::class, 'removeFavourite']);
});
