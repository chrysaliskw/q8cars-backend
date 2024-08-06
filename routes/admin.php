<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/test', function () {
        return view('welcome');
        //return redirect()->route('admin.login');
    });
});

/**
 * Routes that are common to autheticated admin users
 */
Route::middleware('auth:admin')->group(function () {
    //
});
