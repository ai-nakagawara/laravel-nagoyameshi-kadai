<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Middleware\Subscribed;
use App\Http\Middleware\NotSubscribed;
use App\Models\Restaurant;

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
    Route::resource('user', UserController::class)->only(['index', 'edit', 'update'])->middleware(['auth', 'verified'])->names('user');
    Route::resource('restaurants', RestaurantController::class)->only(['index', 'show'])->names('restaurants');
    Route::get('subscription/create', [SubscriptionController::class, 'create'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.create');
    Route::post('subscription/store', [SubscriptionController::class, 'store'])->middleware(['auth', 'verified', 'subscribed'])->name('subscription.store');
    Route::get('subscription/edit', [SubscriptionController::class, 'edit'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.edit');
    Route::patch('subscription/update', [SubscriptionController::class, 'update'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.update');
    Route::get('subscription/cancel', [SubscriptionController::class, 'cancel'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.cancel');
    Route::delete('subscription/destroy', [SubscriptionController::class, 'destroy'])->middleware(['auth', 'verified', 'not.subscribed'])->name('subscription.destroy');
    Route::resource('restaurants.reviews', ReviewController::class)->only(['index'])->middleware(['auth', 'verified'])->names('restaurants.reviews');
    Route::resource('restaurants.reviews', ReviewController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->middleware(['auth', 'verified', 'not.subscribed'])->names('restaurants.reviews');

});
