<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReviewController; // Added import
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
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\LinkOnMap\LinkOnMapController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\UserChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Changed to use MapController@index to ensure $mapLinks is passed
Route::get('/', [MapController::class, 'index']);

Route::get('/main', [HomeController::class, 'welcome']);

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
    Route::prefix('types')->name('types.')->group(function () {
        Route::get('', [TypeController::class, 'index'])->name('index');
        Route::get('/create', [TypeController::class, 'create'])->name('create');
        Route::post('/store', [TypeController::class, 'store'])->name('store');
        Route::get('/{type}/edit', [TypeController::class, 'edit'])->name('edit');
        Route::put('/{type}', [TypeController::class, 'update'])->name('update');
        Route::delete('/{type}', [TypeController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('locations')->name('admin.locations.')->group(function () {
        Route::get('', [LocationController::class, 'index'])->name('index'); // admin.locations.index
        Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('edit'); // admin.locations.edit
        Route::put('/{location}', [LocationController::class, 'update'])->name('update'); // admin.locations.update
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy'); // admin.locations.destroy
    });
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('', [PostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('links-on-map')->name('links-on-map.')->group(function () {
        Route::get('', [LinkOnMapController::class, 'index'])->name('index');
        Route::get('/create', [LinkOnMapController::class, 'create'])->name('create');
        Route::post('', [LinkOnMapController::class, 'store'])->name('store');
        Route::get('/{link}/edit', [LinkOnMapController::class, 'edit'])->name('edit');
        Route::put('/{link}', [LinkOnMapController::class, 'update'])->name('update');
        Route::delete('/{link}', [LinkOnMapController::class, 'destroy'])->name('destroy');
    });
});

// Search and test
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/test', [\App\Http\Controllers\Test\TestController::class, 'testElasticSearchConnection']);

// Admin auth
Route::get('admin/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login.submit');

// Telegram auth
Route::post('/telegram/login', [TelegramController::class, 'authByTelegram'])->name('telegram.login');
Route::get('/telegram/index', [TelegramController::class, 'index'])->name('telegram.index');

// Google maps

// Define the '/map' route to use MapController@search and name it 'map'
Route::get('/map', [MapController::class, 'index'])->name('map');

Route::get('/map/search', [MapController::class, 'search'])->name('map.search'); // Changed name to avoid conflict
// locations

Route::get('/create', function () {
    return view('locations.create_location');
})->name('create');


Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create')->middleware('auth');

Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');

Route::get('/locations/{id}', [LocationController::class, 'show'])->name('locations.single');

Route::get('/my-locations', [LocationController::class, 'myLocations'])
    ->middleware('auth')
    ->name('locations.my');

// Reviews
Route::post('/locations/{location}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

// Chat


Route::middleware('auth')->group(function () {
    // Start new conversation for a location (customer contacting owner)
    Route::post('/locations/{location}/messages', [ChatController::class, 'startConversation'])->name('chat.startConversation');

    // Show chat for user (customer) with location
    Route::get('/chat/location/{location}', [ChatController::class, 'showLocationChat'])->name('chat.location');

    // Show chat for owner with a specific user (customer) for a location
    Route::get('/owner/chat/location/{location}/user/{customer}', [ChatController::class, 'showOwnerChat'])->name('chat.owner');

    // --- User-to-user chat routes ---

    // Show user-to-user chat page (MUST be before the catch-all /chat/{location}/{customer})
    Route::get('/chat/user/{otherUser}', [UserChatController::class, 'show'])
        ->whereNumber('otherUser')
        ->name('user-chat.show');

    // AJAX: fetch messages for a conversation (user-to-user, or any one-on-one chat)
    Route::get('/chat/conversation/{conversation}/messages', [UserChatController::class, 'fetchMessages'])->name('user-chat.fetch');

    // AJAX: send a message in a conversation
    Route::post('/chat/conversation/{conversation}/messages/send', [UserChatController::class, 'sendMessage'])->name('user-chat.send');

    // AJAX: Fetch messages for a location chat
    Route::get('/chat/{location}/{customer}', [ChatController::class, 'fetchMessages'])
        ->whereNumber('location')
        ->whereNumber('customer')
        ->name('chat.fetchMessages');

    // AJAX: Send a message in a location chat
    Route::post('/chat/{location}', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');

    // List all chats for the authenticated user
    Route::get('/chats', [ChatController::class, 'list'])->name('chat.list');
});



