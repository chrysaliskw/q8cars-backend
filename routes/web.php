<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

// Route::get('/', function () {
//     return view(('admin.auth.login'));
// });
Route::get('/', function () {
    $domain = parse_url(request()->root())['host'];
    if($domain == 'admin.newsayara.com') {
        return redirect()->route('admin.login');
    } 
    return redirect('/admin/login');
});

Route::get('/login', function () {
    $domain = parse_url(request()->root())['host'];
    if($domain == 'admin.newsayara.com') {
        return redirect()->route('admin.login');
    } 
    return redirect()->route('admin.login');
})->name('login');

// File path
Route::get('/file', [FileController::class, 'index'])->name('file.index');
Route::get('/file/create', [FileController::class, 'create'])->name('file.create');

Route::name('admin.')->prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});

