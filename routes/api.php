<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', RegisterController::class)->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
});

Route::apiResource('ip-address', IpAddressController::class)->only([
    'store', 'index', 'update'
]);

Route::get('activity-logs', [ActivityLogController::class, 'index']);
