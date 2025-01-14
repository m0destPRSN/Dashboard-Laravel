<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Type\TypeController;

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
    return view('welcome');
});

Auth::routes();

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('admin')->get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::middleware('admin')->prefix('types')->group(function () {
    Route::get('', [TypeController::class, 'index'])->name('types.index');
    Route::get('/add',[TypeController::class,'add'])->name('types.add');
    Route::post('/store',[TypeController::class,'store'])->name('types.store');
});
