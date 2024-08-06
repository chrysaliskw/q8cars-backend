<?php

use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\GuestController;
use App\Http\Controllers\Api\User\OtpController;
use App\Http\Controllers\Api\User\OtpVerificationController;

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
});

/**
 * Routes which can be accessed by authenticated user only
 */ 
Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {
});

