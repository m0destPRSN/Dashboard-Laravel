<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Type\TypeController;
use \App\Http\Controllers\Category\CategoryController;
use \App\Http\Controllers\Location\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome.welcome');
});

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('admin')->get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::middleware('admin')->prefix('types')->group(function () {
    Route::get('', [TypeController::class, 'index'])->name('types.index');
    Route::post('/store',[TypeController::class,'store'])->name('types.store');
});
Route::middleware('admin')->prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/store',[CategoryController::class,'store'])->name('categories.store');
});
Route::middleware('admin')->prefix('locations')->group(function (){
    Route::get('',[LocationController::class,'index'])->name('locations.index');
});

Route::post('/search', [\App\Http\Controllers\Search\SearchController::class, 'search'])->name('search');

Route::get('/test',[\App\Http\Controllers\Test\TestController::class,'testElasticSearchConnection']);
