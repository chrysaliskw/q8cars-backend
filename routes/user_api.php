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
use App\Http\Controllers\Api\User\OfferRequestController;
use App\Http\Controllers\Api\User\SubmitReviewController;

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
    // Cars
    Route::apiResource('cars', CarController::class)->only(['index', 'show']);
});

/**
 * Routes which can be accessed by authenticated user only
 */ 
Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {
    // User Profile 
    // Route::post('profile-image-updates', [ProfileController::class, 'picture']);
    // Route::apiResource('profiles', ProfileController::class)->only(['index', 'store']);
  
    // //test drive request
    // Route::post('/test-drive-send-otps',[TestDriveRequestController::class, 'sendOtp']);
    // Route::post('/test-drive-otp-verifys',[TestDriveRequestController::class,'verifyOtp']);
    // //favourites api
    // Route::apiResource('favourites',FavouriteController::class)->only(['index', 'store']);
    // //get offers and onroad price
    // Route::post('/offer-requests', OfferRequestController::class);
    // //submit review
    // Route::post('/submit-reviews',SubmitReviewController::class);
    // // Cars
    // Route::apiResource('cars', CarController::class)->only(['index', 'show']);
 
});

