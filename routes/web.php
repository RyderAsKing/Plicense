<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view("welcome");
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/licenses', [HomeController::class, 'licenses'])->name('licenses');
    Route::get('/licenses/{id}/reissue', [HomeController::class, 'licenses_reissue'])->name('licenses.reissue');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/licenses', [AdminController::class, 'licenses'])->name('admin.licenses');
    Route::get('/admin/licenses/create', [AdminController::class, 'licenses_create'])->name('admin.licenses.create');
    Route::post('/admin/licenses/create', [AdminController::class, 'licenses_create_store'])->name('admin.licenses.create');
    Route::get('/admin/licenses/{id}/reissue', [AdminController::class, 'licenses_reissue'])->name('admin.licenses.reissue');
    Route::get('/admin/licenses/{id}/expire', [AdminController::class, 'licenses_expire'])->name('admin.licenses.expire');
});
