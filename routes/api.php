<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Location\LocationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('user',UserController::class);

Route::prefix('user')->group(function () {
    Route::post('/registration-phone', [UserController::class, 'registratePhone']);
    Route::post('/validation-phone', [UserController::class, 'validatePhone']);
    Route::post('/enter-user-info', [UserController::class, 'enterUserInfo']);
});

Route::resource('location',LocationController::class);
