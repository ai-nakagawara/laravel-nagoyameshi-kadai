<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
});

Route::group(['middleware' => 'guest:admin'], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    // Route::get('user/index', [UserController::class, 'index'])->name('user.index');
    // Route::get('user/edit', [UserController::class, 'edit'])->name('user.edit');
    // Route::post('user/update', [UserController::class, 'update'])->name('user.update');
    Route::resource('user', UserController::class)->only(['index', 'edit', 'update'])->middleware(['auth', 'verified'])->names('user');
});
