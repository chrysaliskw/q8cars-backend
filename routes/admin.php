<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BodyTypeController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TestRideRequestController;
use App\Http\Controllers\Admin\OfferRequestController;
use App\Http\Controllers\Admin\ReviewController;

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

    // Select2
    Route::get('brand/select', [BrandController::class, 'select'])->name('brand.select');
    Route::get('body-type/select', [BodyTypeController::class, 'select'])->name('body-type.select');

    Route::resources([
        'brand' => BrandController::class,          // Brands
        'body-type' => BodyTypeController::class,   // Body Type
        'user' => UserController::class,            // User
        'car' => CarController::class,              // Car
    ]);
   
    // Test ride requests
    Route::post('test-ride-requests/update', [TestRideRequestController::class, 'update'])->name('test-ride-requests.update');
    Route::resource('test-ride-requests', TestRideRequestController::class)->only(['index','show']);

    // Offer request
    Route::resource('offer-requests', OfferRequestController::class)->only(['index','show']);
    
    // Review
    Route::post('reviews/update', [ReviewController::class, 'update'])->name('reviews.update');
    Route::resource('reviews', ReviewController::class)->only(['index','show']);
 
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
