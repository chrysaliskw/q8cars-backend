<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    //return redirect()->route('admin.login');
});

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});