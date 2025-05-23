<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Telegram\TelegramController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Type\TypeController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\TurboSMS\TurboSMSController;
use App\Http\Controllers\Map\MapController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('map');
});

Route::get('/main', function () {
    return view('welcome.welcome');
});

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// OTP routes
Route::post('/otp/send', [TurboSMSController::class, 'sendOTP'])->name('otp.send');
Route::post('/otp/resend', [TurboSMSController::class, 'sendOTP'])->name('otp.resend');
Route::post('/otp/verify', [TurboSMSController::class, 'verifyOTP'])->name('otp.verify');
Route::get('/otp', function (\Illuminate\Http\Request $request) {
    $phone = $request->query('phone');
    if (!$phone) return redirect()->route('login')->with('error', 'Phone is required');
    return view('auth.otp', compact('phone'));
})->name('otp.form');

// Profile completion
Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('profile.complete');
Route::post('/profile/complete', [ProfileController::class, 'complete'])->name('profile.complete.save');

// Home
Route::get('home', [HomeController::class, 'index'])->name('home');

// Admin routes
Route::middleware('admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::prefix('types')->group(function () {
        Route::get('', [TypeController::class, 'index'])->name('types.index');
        Route::post('/store', [TypeController::class, 'store'])->name('types.store');
    });
    Route::prefix('categories')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
    });
    Route::prefix('locations')->group(function () {
        Route::get('', [LocationController::class, 'index'])->name('locations.index');
    });
});

// Search and test
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::get('/test', [\App\Http\Controllers\Test\TestController::class, 'testElasticSearchConnection']);

// Admin auth
Route::get('admin/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login.submit');

// Telegram auth
Route::post('/telegram/login', [TelegramController::class, 'authByTelegram'])->name('telegram.login');
Route::get('/telegram/index', [TelegramController::class, 'index'])->name('telegram.index');

// Google maps

Route::get('/map', function () {
    return view('map.map_search');
})->name('map');
Route::post('/map', [MapController::class, 'search'])->name('map.index');
