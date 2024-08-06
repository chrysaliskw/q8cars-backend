<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\GuestController;

/*
|--------------------------------------------------------------------------
| User Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

// Route::post('/guests', GuestController::class);

//Routes which can be accessed by guest user also
Route::middleware('auth:user_api')->group(function () {
    //
});

Route::middleware(['auth:user_api', 'userIsNotGuest'])->group(function () {
    //
});