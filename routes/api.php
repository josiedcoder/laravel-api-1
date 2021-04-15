<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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
    'namespace' => 'App\Http\Controllers'
], function ($router) {
    Route::post('login', [ApiController::class, 'login'])->name('login');
    Route::post('register', [ApiController::class, 'register'])->name('register');
    Route::post('logout', [ApiController::class, 'logout'])->name('logout');
    Route::post('refresh', [ApiController::class, 'refresh'])->name('refresh');
    Route::get('profile', [ApiController::class, 'profile'])->name('profile');
    Route::get('btc-rate', [ApiController::class, 'getBTCRate'])->name('btc.rate');
});
