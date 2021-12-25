<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);

Route::get("/users", [UserController::class, 'listUsers']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post("/auth/logout", [AuthController::class, 'logout']);

    Route::get("/user/{id}", [UserController::class, 'userDetail']);
    Route::put("/user/{id}", [UserController::class, 'updateUserDetail']);
    Route::delete("/user/{id}", [UserController::class, 'deleteUserDetail']);

});
