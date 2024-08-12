<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\User\BodyTypeController;
use App\Http\Controllers\Api\User\BrandController;
use App\Http\Controllers\Api\User\FaqListingController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
  // Brand List
  Route::get('/brands', BrandController::class);
    
  //Body Type List
  Route::get('/body-types', BodyTypeController::class);
   
    //faq listing
    Route::get('/faqs',FaqListingController::class);

Route::name('user.')->prefix('user')->group(function () {
    require __DIR__.'/user_api.php';
});

