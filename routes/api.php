<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\SendMailController;
use App\Models\Role;

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
Route::group(['middleware' => ['checkRole:supper_admin']], function () {
    Route::delete('/author-delete', [AuthorController::class, 'delete']);
});
Route::group(['middleware' => ['checkRole:admin,supper_admin']], function () {
    Route::prefix('/author')->group(function () {
        Route::get('/index', [AuthorController::class, 'index']);
        Route::post('/add', [AuthorController::class, 'add']);
        Route::put('/edit', [AuthorController::class, 'edit']);
    });
});
Route::get('/send-mail', [SendMailController::class, 'index']);
