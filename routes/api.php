<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:api')->get('/user',[UserController::class,'index']);
// Route::post('/user/register',[UserController::class,'register']);
// Route::post('/user/login',[UserController::class,'login']);

Route::group([
    'middleware' => 'api',
    'prefix'=>'auth'
], function () {
    Route::get('user', [UserController::class, 'index']);
    Route::post('user/register', [UserController::class, 'register']);
    Route::post('user/login', [UserController::class, 'login']);
    Route::get('user/profile', [UserController::class, 'userProfile']);
    Route::get('user/new_token', [UserController::class, 'newAccessToken']);
    Route::get('user/logout', [UserController::class, 'logout']);

    Route::get('book/list', [BookController::class, 'index']);
    Route::get('book/data/{id}', [BookController::class, 'data']);
    Route::post('book/add', [BookController::class, 'add']);
    Route::post('book/update/{id}', [BookController::class, 'update']);
    Route::get('book/delete/{id}', [BookController::class, 'delete']);

    Route::post('book/rent', [BookController::class, 'rentBook']);
    Route::get('book/return/{id}', [BookController::class, 'returnBook']);
    Route::get('book/rented_list', [BookController::class, 'rentedBookList']);
});
