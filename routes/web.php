<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/licenses', [HomeController::class, 'licenses'])->name('licenses');
    Route::post('/licenses/{id}/reissue', [HomeController::class, 'licenses_reissue'])->name('licenses.reissue');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/token', [ApiController::class, 'update'])->name('api.update');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/licenses', [AdminController::class, 'licenses'])->name('admin.licenses');
    Route::get('/admin/licenses/create', [AdminController::class, 'licenses_create'])->name('admin.licenses.create');
    Route::post('/admin/licenses/create', [AdminController::class, 'licenses_create_store'])->name('admin.licenses.create.store');
    Route::post('/admin/licenses/{id}/reissue', [AdminController::class, 'licenses_reissue'])->name('admin.licenses.reissue');
    Route::post('/admin/licenses/{id}/expire', [AdminController::class, 'licenses_expire'])->name('admin.licenses.expire');
});
