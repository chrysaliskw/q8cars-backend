<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BodyTypeController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Admin Common Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

/*
 * Routes that are common to all
 */

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/login/forgot-password', [AuthenticatedSessionController::class, 'forgotPassword'])->name('login.forgot-password');
    Route::get('/login/send-reset-password-link', [AuthenticatedSessionController::class, 'sendResetLink'])->name('login.send-reset-password-link');
    Route::post('/login/show-reset-form', [AuthenticatedSessionController::class, 'showResetPasswordForm'])->name('login.show-form');
    Route::post('/login/reset-password', [AuthenticatedSessionController::class, 'resetPassword'])->name('login.reset-password');
});

/**
 * Routes that are common to autheticated admin users
 */
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

       // Admin Profile
       Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
       Route::get('/profile/{profile}', [ProfileController::class, 'edit'])->name('profile.edit');
       Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
   
    //brands
    Route::resource('brand',BrandController::class);
    //body types
    Route::resource('body-type',BodyTypeController::class);
    //users
    Route::resource('user',UserController::class);
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
