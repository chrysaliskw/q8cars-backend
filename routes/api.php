<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::name('user.')->prefix('user')->group(function () {
    require __DIR__.'/user_api.php';
});

