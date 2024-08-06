<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    //return redirect()->route('admin.login');
});

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

Route::name('api.')->prefix('api')->group(function () {
    require __DIR__.'/api.php';
});