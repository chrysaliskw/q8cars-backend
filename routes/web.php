<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
    //return redirect()->route('admin.login');
});

// File path
Route::get('/file', [FileController::class, 'index'])->name('file.index');

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

