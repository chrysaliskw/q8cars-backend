<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view(('admin.auth.login'));
});

// File path
Route::get('/file', [FileController::class, 'index'])->name('file.index');
Route::get('/file/create', [FileController::class, 'create'])->name('file.create');

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

