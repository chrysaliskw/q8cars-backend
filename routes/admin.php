<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Bank\LoanRequestController;
use App\Http\Controllers\Admin\Bank\PartnerBankController;
use App\Http\Controllers\Admin\Bank\SuggestedBankController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BodyTypeController;
use App\Http\Controllers\Admin\CarComparisonListsController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarVersionController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NewsPostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TestRideRequestController;
use App\Http\Controllers\Admin\OfferRequestController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\Trash\UserTrashController;
use App\Http\Controllers\Admin\Trash\BrandTrashController;
use App\Http\Controllers\Admin\Trash\BodyTypeTrashController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CuratedComparisonController;
use App\Http\Controllers\Admin\EmiCalculatorController;
use App\Http\Controllers\Admin\Image360Controller;
use App\Http\Controllers\Admin\NotificationController;
use App\Models\Car
;use App\Http\Controllers\Admin\OfferController;
use App\Models\Notification;
use Mockery\Matcher\Not;

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
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login'); // This is 'admin.login'
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
    Route::get('car/select', [CarController::class, 'select'])->name('car.select');
    Route::get('color/select', [ColorController::class, 'select'])->name('color.select');
    Route::get('car-version/select', [CarVersionController::class, 'select'])->name('car-version.select');

    Route::resources([
        'brand' => BrandController::class,              // Brands
        'body-type' => BodyTypeController::class,       // Body Type
        'user' => UserController::class,                // User
        'car' => CarController::class,                  // Car
        'car-version' => CarVersionController::class,   // Car Version
        'color' =>ColorController::class  ,             //color
        'faq' => FaqController::class,                  // FAQ
        'news' => NewsPostController::class,            // News
        'emi-info' => EmiCalculatorController::class,   // Emi Calculator
        'comparison' => CarComparisonListsController::class, // Car Comparison
        'offers' => OfferController::class,              // Offers
        'notifications' => NotificationController::class, //Notifications
        'curated-comparison' => CuratedComparisonController::class, // Curated Comparison
        'partner-banks' => PartnerBankController::class, //Partner Banks
        'image-360' => Image360Controller::class,       //Image 360
    ]);

    //bank
    Route::post('suggested-banks/update', [SuggestedBankController::class, 'update'])->name('suggested-banks.update');
    Route::resource('suggested-banks', SuggestedBankController::class)->only(['index','show']);

    //loan
    Route::post('loan-requests/update', [LoanRequestController::class, 'update'])->name('loan-requests.update');
    Route::resource('loan-requests', LoanRequestController::class)->only(['index','show']);

    Route::get('car-comparison-lists/select', [CarComparisonListsController::class, 'select'])->name('car-comparison-lists.select');
    // Test ride requests
    Route::post('test-ride-requests/update', [TestRideRequestController::class, 'update'])->name('test-ride-requests.update');
    Route::resource('test-ride-requests', TestRideRequestController::class)->only(['index','show']);

    // Offers
    // Route::resource('offers', OfferController::class);

    // Offer request
    Route::post('offer-requests/update', [OfferRequestController::class, 'update'])->name('offer-requests.update');
    Route::resource('offer-requests', OfferRequestController::class)->only(['index','show']);

    // Review
    Route::post('reviews/update', [ReviewController::class, 'update'])->name('reviews.update');
    Route::resource('reviews', ReviewController::class)->only(['index','show']);

    //News
    Route::post('news/banner', [NewsPostController::class, 'updatebanner'])->name('news.banner');




    //Trash
    Route::resource('trash-user',UserTrashController::class)->only('index','show','edit');
    Route::resource('trash-brand',BrandTrashController::class)->only('index','show','edit');
    Route::resource('trash-body-type',BodyTypeTrashController::class)->only('index','show','edit');
      // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
