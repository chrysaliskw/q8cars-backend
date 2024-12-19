<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\CarController;
use App\Http\Controllers\Api\User\OtpController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\BankController;
use App\Http\Controllers\Api\User\HomeController;
use App\Http\Controllers\Api\User\LoanController;
use App\Http\Controllers\Api\User\BrandController;
use App\Http\Controllers\Api\User\GuestController;
use App\Http\Controllers\Api\User\OfferController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\BodyTypeController;
use App\Http\Controllers\Api\User\FuelCostController;
use App\Http\Controllers\Api\User\CarSearchController;
use App\Http\Controllers\Api\User\FavouriteController;
use App\Http\Controllers\Api\User\JustLaunchController;
use App\Http\Controllers\Api\User\PopularCarController;
use App\Http\Controllers\Api\User\CompareCarsController;
use App\Http\Controllers\Api\User\LoanRequestController;
use App\Http\Controllers\Api\User\RelatedNewsController;
use App\Http\Controllers\Api\User\UpcomingCarController;
use App\Http\Controllers\Api\User\BankPartnersController;
use App\Http\Controllers\Api\User\NotificationController;
use App\Http\Controllers\Api\User\OfferRequestController;
use App\Http\Controllers\Api\User\SubmitReviewController;
use App\Http\Controllers\Api\User\View360ImageController;
use App\Http\Controllers\Api\User\AccountDeleteController;
use App\Http\Controllers\Api\User\EmiCalculatorController;
use App\Http\Controllers\Api\User\ReviewsAndNewsController;
use App\Http\Controllers\Api\User\OtpVerificationController;
use App\Http\Controllers\Api\User\PopularCarFilterController;
use App\Http\Controllers\Api\User\TestDriveRequestController;
use App\Http\Controllers\Api\User\CuratedComparisonController;
use App\Http\Controllers\Api\User\CompareCarsDetailsController;
use App\Http\Controllers\Api\User\FavouriteComparisonController;
use App\Http\Controllers\Api\User\BankSuggestionRequestController;
use Google\Service\Blogger\Post;

// Guest user login
Route::post('/guests', GuestController::class);
// User Login
Route::post('/sessions', AuthController::class);
// OTP Verification
Route::post('/otp-verifications', OtpVerificationController::class);
// Resend OTP
Route::post('/otps', OtpController::class);
   // Token Refresh Route
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

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
    Route::post('cars/video-view',[CarController::class,'incrementVideoViewCount']);
    Route::get('cars/colors',[CarController::class,'colors']);
    Route::get('cars/compare-similar', [CarController::class, 'compareSimilar']);
    Route::get('cars/images', [CarController::class, 'carImages']);
    Route::get('cars/search',CarSearchController::class);
    Route::apiResource('cars', CarController::class)->only(['index', 'show']);

    //car versions according to car
    Route::get('car-versions',[CarSearchController::class,'getCarVersion']);
    // Home
    Route::get('homes', HomeController::class);
    Route::get('just-launch/cars', [JustLaunchController::class, 'getJustLaunchCars']);
    Route::get('just-launch', [JustLaunchController::class, 'index']);
    Route::get('popular-offers',[OfferController::class,'getPopularOffers']);
    //popular car listing
    Route::get('popular-cars',PopularCarController::class);
    Route::get('popular-cars/filter',PopularCarFilterController::class);
    Route::apiResource('offers', OfferController::class)->only(['index', 'show']);
    Route::get('compare-cars',CompareCarsController::class);
    Route::post('car-comparisons', CompareCarsDetailsController::class);
    Route::get('curated-comparisons',CuratedComparisonController::class);
    //popular brand
    Route::get('popular-brands',[BrandController::class,'popularBrands']);
    Route::get('reviews-and-news',ReviewsAndNewsController::class);
    Route::get('search-suggestions',[ReviewsAndNewsController::class,'getSuggestions']);
    Route::get('compare-suggestions',[CompareCarsController::class,'getSuggestions']);
    Route::post('add-suggestions',[ReviewsAndNewsController::class,'addSuggestions']);
    //delete account
    Route::get('/account-delete', AccountDeleteController::class);
    // EMI Calculator
    Route::get('/emi-calculator', EmiCalculatorController::class);

    //notifications
    Route::get('/notifications', NotificationController::class);
    Route::post('/notifications/toggle-mute', [NotificationController::class, 'toggleMute']);
    Route::post('/notifications/read',[NotificationController::class,'read']);

    //favourite comparison
    Route::get('/fav-comparisons', FavouriteComparisonController::class);
    Route::delete('/fav-comparisons/{id}', [FavouriteComparisonController::class, 'destroy']);

    //bank
    Route::get('/banks', BankController::class);
    //bank logo and id
    Route::get('/bank-partners', BankPartnersController::class);
    Route::get('/related-news', RelatedNewsController::class);
    //upcoming cars
    Route::get('/upcoming-cars', UpcomingCarController::class);
    Route::post('/bank-suggestions', BankSuggestionRequestController::class);

    //loan
    Route::post('/loan-requests', LoanRequestController::class);
    Route::post('/loan-details', LoanController::class);

    //fuel cost calculator
    Route::post('/fuel-cost', FuelCostController::class);
    Route::get('/360-view-images',View360ImageController::class);
   
});

/**
 * Routes which can be accessed by authenticated user only
 */
Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {

});

