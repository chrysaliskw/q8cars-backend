<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\BodyTypeController;
use App\Http\Controllers\Api\User\BrandController;
use App\Http\Controllers\Api\User\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\GuestController;
use App\Http\Controllers\Api\User\OtpController;
use App\Http\Controllers\Api\User\OtpVerificationController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\TestDriveRequestController;
use App\Http\Controllers\Api\User\FavouriteController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\JustLaunchController;
use App\Http\Controllers\Api\User\OfferRequestController;
use App\Http\Controllers\Api\User\SubmitReviewController;
use App\Http\Controllers\Api\User\AccountDeleteController;
use App\Http\Controllers\Api\User\PopularCarController;
use App\Http\Controllers\Api\User\PopularCarFilterController;

// Guest user login
Route::post('/guests', GuestController::class);
// User Login
Route::post('/sessions', AuthController::class);
// OTP Verification
Route::post('/otp-verifications', OtpVerificationController::class);
// Resend OTP
Route::post('/otps', OtpController::class);

/**
 * User Routes which can be accessed by guest user also 
 */ 
Route::middleware('auth:user_api')->group(function () {
    // User Profile 
    Route::post('profile-image-updates', [ProfileController::class, 'picture']);
    Route::apiResource('profiles', ProfileController::class)->only(['index', 'store']);
    //test drive request
    Route::post('/test-drive-send-otps',[TestDriveRequestController::class, 'sendOtp']);
    Route::post('/test-drive-otp-verifys',[TestDriveRequestController::class,'verifyOtp']);
    //favourites api
    Route::apiResource('favourites',FavouriteController::class)->only(['index', 'store']);
    //get offers and onroad price
    Route::post('/offer-requests', OfferRequestController::class);
    //submit review
    Route::post('/submit-reviews',SubmitReviewController::class);
    // Cars Api
    Route::get('cars/colors',[CarController::class,'colors']);
    Route::get('cars/compare-similar', [CarController::class, 'compareSimilar']);
    Route::get('cars/images', [CarController::class, 'carImages']);
    Route::apiResource('cars', CarController::class)->only(['index', 'show']);
    // Home
    Route::get('homes', HomeController::class);
    Route::get('just-launch/cars', [JustLaunchController::class, 'getJustLaunchCars']);
    Route::get('just-launch', [JustLaunchController::class, 'index']);

    //popular car listing
    Route::get('popular-cars',PopularCarController::class);
    Route::get('popular-cars/filter',PopularCarFilterController::class);
    
    //delete account
    Route::get('/account-delete', AccountDeleteController::class);
    
   
});

/**
 * Routes which can be accessed by authenticated user only
 */ 
Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {
  
   
});

