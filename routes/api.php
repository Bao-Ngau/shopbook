<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ProdController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SendMailController;
use App\Http\Controllers\API\UserController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});
Route::group(['middleware' => 'checkRole:supper_admin'], function () {
    Route::delete('/author-delete/{id}', [AuthorController::class, 'delete']);
    Route::delete('/category-delete/{id}', [CategoryController::class, 'delete']);
    Route::delete('/book-delete/{id}', [BookController::class, 'delete']);
    Route::delete('/product-delete/{id}', [ProdController::class, 'delete']);
    Route::prefix('/user')->group(function () {
        Route::get('/index/{pageSize}/{currentPage}', [UserController::class, 'index']);
        Route::post('/add', [UserController::class, 'add']);
        Route::post('/update', [UserController::class, 'update']);
        Route::post('/search/{pageSize}/{currentPage}', [UserController::class, "search"]);
        Route::delete('/user-delete/{id}', [UserController::class, 'delete']);
    });
    Route::prefix('/role')->group(function () {
        Route::get('/getIdAndName', [RoleController::class, 'getIdAndName']);
    });
});
Route::group(['middleware' => ['checkRole:admin,supper_admin']], function () {
    Route::prefix('/author')->group(function () {
        Route::get('/index/{pageSize}/{currentPage}', [AuthorController::class, 'index']);
        Route::post('/add', [AuthorController::class, 'add']);
        Route::put('/update', [AuthorController::class, 'update']);
        Route::post('/search/{pageSize}/{currentPage}', [AuthorController::class, "search"]);
        Route::get('/getIdAndName', [AuthorController::class, 'getIdAndName']);
    });
    Route::prefix('/category')->group(function () {
        Route::get('/index/{pageSize}/{currentPage}', [CategoryController::class, 'index']);
        Route::post('/add', [CategoryController::class, 'add']);
        Route::put('/update', [CategoryController::class, 'update']);
        Route::post('/search/{pageSize}/{currentPage}', [CategoryController::class, "search"]);
        Route::get('/getIdAndName', [CategoryController::class, 'getIdAndName']);
    });
    Route::prefix('/book')->group(function () {
        Route::get('/index/{pageSize}/{currentPage}', [BookController::class, 'index']);
        Route::post('/add', [BookController::class, 'add']);
        Route::post('/update', [BookController::class, 'update']);
        Route::post('/search/{pageSize}/{currentPage}', [BookController::class, "search"]);
    });
    Route::prefix('/product')->group(function () {
        Route::post('/add', [ProdController::class, 'add']);
        Route::post('/update', [ProdController::class, 'update']);
    });
});
Route::group(['middleware' => ['checkRole:user,admin,supper_admin']], function () {
    Route::prefix('/cart')->group(function () {
        Route::get('/getCartByUser/{user_id}', [CartController::class, 'getCartByUser']);
        Route::post('/add', [CartController::class, 'add']);
        Route::post('/update-quantity', [CartController::class, 'updateQuantityById']);
        Route::delete('/delete/{id}', [CartController::class, 'delete']);
    });
});

Route::get('/product/index/{pageSize}/{currentPage}', [ProdController::class, 'index']);
Route::get('/book/getBookByStatus/{pageSize}/{currentPage}', [BookController::class, 'getBookByStatus']);

Route::post('/send-mail', [SendMailController::class, 'index']);
