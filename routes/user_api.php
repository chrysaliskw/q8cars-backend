<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\BodyTypeController;
use App\Http\Controllers\Api\User\BrandController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\GuestController;
use App\Http\Controllers\Api\User\OtpController;
use App\Http\Controllers\Api\User\OtpVerificationController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\ProfileImageUpdateController;
use App\Http\Controllers\Api\User\TestDriveRequestController;
use App\Http\Controllers\Api\User\FavouriteController;
use App\Http\Controllers\Api\User\OfferRequestController;
use App\Http\Controllers\Api\User\SubmitReviewController;
use App\Http\Controllers\Api\User\FaqListingController;

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
    Route::apiResource('profile', ProfileController::class)->only(['index', 'store']);
    Route::post('profile-image-update', [ProfileImageUpdateController::class, 'picture']);

    // Brand List
    Route::get('/brands', BrandController::class);
    
    //Body Type List
    Route::get('/body-types', BodyTypeController::class);

    //test drive request
    Route::post('/test-drive-send-otp',[TestDriveRequestController::class, 'sendOtp']);
    Route::post('/test-drive-otp-verify',[TestDriveRequestController::class,'verifyOtp']);
    //favourites api
    Route::apiResource('favourite',FavouriteController::class)->only(['index', 'store']);
    //get offers and onroad price
    Route::post('/offer-request', OfferRequestController::class);
    //sub,it review
    Route::post('/submit-review',SubmitReviewController::class);
    //faq listing
    Route::get('/faq',FaqListingController::class);
});

/**
 * Routes which can be accessed by authenticated user only
 */ 
Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {
});

