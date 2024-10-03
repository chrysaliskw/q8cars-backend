<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\BrandController;
use App\Http\Controllers\Api\User\BodyTypeController;
use App\Http\Controllers\Api\User\FaqListingController;
use App\Http\Controllers\Test\TestExternalApiController;

Route::post('/test-external', TestExternalApiController::class);
// Brand List
Route::get('/brands', BrandController::class);
//Body Type List
Route::get('/body-types', BodyTypeController::class);
//faq listing
Route::get('/faqs',FaqListingController::class);

Route::name('user.')->prefix('user')->group(function () {
    require __DIR__.'/user_api.php';
});

