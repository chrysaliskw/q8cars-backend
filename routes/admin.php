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
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\NotificationController;
use App\Models\Car;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Api\User\LoanController as UserLoanController;

use App\Http\Controllers\Admin\SubAdmin\PermissionController;
use App\Http\Controllers\Admin\SubAdmin\RoleController;
use App\Http\Controllers\Admin\SubAdmin\SubAdminController;

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


    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/{profile}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //select
    Route::get('brand/select', [BrandController::class, 'select'])->name('brand.select');
    Route::get('body-type/select', [BodyTypeController::class, 'select'])->name('body-type.select');
    Route::get('car/select', [CarController::class, 'select'])->name('car.select');
    Route::get('color/select', [ColorController::class, 'select'])->name('color.select');
    Route::get('car-version/select', [CarVersionController::class, 'select'])->name('car-version.select');
    Route::get('car-comparison-lists/select', [CarComparisonListsController::class, 'select'])->name('car-comparison-lists.select');
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});



// Dashboard
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Dashboard'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

// users
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Users'])->group(function () {
    Route::resources([
        'user' => UserController::class
    ]);
});

//Brands
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Brands'])->group(function () {
    Route::resources([
        'brand' => BrandController::class,              // Brands
    ]);
});
//Body Type
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Body Types'])->group(function () {
    Route::resources([
        'body-type' => BodyTypeController::class,       // Body Type
    ]);
});

//Colour
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Colors'])->group(function () {
    Route::resources([
        'color' => ColorController::class,             //color
    ]);
});

//Emi Calculator
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Emi Calculator'])->group(function () {
    Route::resources([
        'emi-info' => EmiCalculatorController::class,   // Emi Calculator
    ]);
});

//Loan Eligibility Calculator
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Loan Eligibility Calculator'])->group(function () {
    Route::resources([
        'loan-info' => LoanController::class,           //Loan Eligibility Calculator
    ]);
});
//Car Management
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Car Management'])->group(function () {
    Route::resources([
        'car' => CarController::class,                  // Car
        'car-version' => CarVersionController::class,   // Car Version
        'comparison' => CarComparisonListsController::class, // Car Comparison
        'curated-comparison' => CuratedComparisonController::class, // Curated Comparison
    ]);
});

//Test Drive Requests
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Test Drive Requests'])->group(function () {

    Route::post('test-ride-requests/update', [TestRideRequestController::class, 'update'])->name('test-ride-requests.update');
    Route::resource('test-ride-requests', TestRideRequestController::class)->only(['index', 'show']);
});

//Offers
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Offers'])->group(function () {
    Route::resources([
        'offers' => OfferController::class,              // Offers
    ]);
});

//Offers Requests
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Offers Requests'])->group(function () {
    Route::resources([
        'offer-requests' => OfferRequestController::class,              // Offers Requests
    ]);
    Route::resource('offer-requests', OfferRequestController::class)->only(['index', 'show']);
    Route::post('offer-requests/update', [OfferRequestController::class, 'update'])->name('offer-requests.update');
});

//Notifications
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Notifications'])->group(function () {
    Route::resources([
        'notifications' => NotificationController::class, //Notifications
    ]);
});

//Banks
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Banks'])->group(function () {

    Route::post('suggested-banks/update', [SuggestedBankController::class, 'update'])->name('suggested-banks.update');
    Route::resource('suggested-banks', SuggestedBankController::class)->only(['index', 'show']);
    Route::post('loan-requests/update', [LoanRequestController::class, 'update'])->name('loan-requests.update');
    Route::resource('loan-requests', LoanRequestController::class)->only(['index', 'show']);
    Route::resources([
        'partner-banks' => PartnerBankController::class, //Partner Banks
    ]);
});

//Reviews
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Reviews'])->group(function () {

    Route::post('reviews/update', [ReviewController::class, 'update'])->name('reviews.update');
    Route::resource('reviews', ReviewController::class)->only(['index', 'show']);
});

//Faq
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Faq'])->group(function () {
    Route::resources([
        'faq' => FaqController::class,                  // FAQ
    ]);
});

//News
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|News'])->group(function () {
    Route::resources([
        'news' => NewsPostController::class,            // News
    ]);
    Route::post('news/banner', [NewsPostController::class, 'updatebanner'])->name('news.banner');
});


//Trash
Route::middleware(['auth:admin', 'role_or_permission:Super Admin|Trash'])->group(function () {

    Route::resource('trash-user', UserTrashController::class)->only('index', 'show', 'edit');
    Route::resource('trash-brand', BrandTrashController::class)->only('index', 'show', 'edit');
    Route::resource('trash-body-type', BodyTypeTrashController::class)->only('index', 'show', 'edit');
});
// ------------------------------------------------------------


// Sub Admin
Route::middleware(['auth:admin', 'role_or_permission:Super Admin'])->name('sub-admin.')->prefix('sub-admins')->group(function () {
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('admin', SubAdminController::class);
    // Route::post('/admin/add-role', [SubAdminController::class, 'addRole'])->name('admin.add-role');
});
