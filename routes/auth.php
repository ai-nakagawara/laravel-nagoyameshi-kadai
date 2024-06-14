<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Queue\Console\RestartCommand;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'guest:admin'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [Admin\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('admin.login');

    Route::post('admin/login', [Admin\Auth\AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::middleware('auth:admin')->group(function () {
    Route::post('admin/logout', [Admin\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('admin.logout');

    Route::get('admin/index',[Admin\UserController::class,'index'])->name('admin.users.index');

    Route::get('admin/show/{num}', [Admin\UserController::class, 'show'])->name('admin.users.show');

    Route::get('admin/restaurants/index', [Admin\RestaurantController::class, 'index'])->name('admin.restaurants.index');

    Route::get('admin/restaurants/show/{restaurant}', [Admin\RestaurantController::class, 'show'])->name('admin.restaurants.show');

    Route::get('admin/restaurants/create', [Admin\RestaurantController::class, 'create'])->name('admin.restaurants.create');

    Route::post('admin/restaurants/store', [Admin\RestaurantController::class, 'store'])->name('admin.restaurants.store');

    Route::get('admin/restaurants/{restaurant}/edit', [Admin\RestaurantController::class, 'edit'])->name('admin.restaurants.edit');

    Route::patch('admin/restaurants/{restaurant}/update', [Admin\RestaurantController::class, 'update'])->name('admin.restaurants.update');

    Route::delete('admin/restaurants/delete/{num}', [Admin\RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');

    // Route::resource('restaurants', RestaurantController::class,)->only('store', 'update', 'destroy');
});


