<?php

use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/license/verify', [LicenseController::class, 'verify'])
    ->middleware('throttle:license-verify');

// Keep GET for backward compatibility, but prefer POST with key in body.
Route::get('/license/{key}', [LicenseController::class, 'verify'])
    ->middleware('throttle:license-verify');

Route::middleware(['auth:api', 'admin', 'throttle:admin-api'])->group(function () {
    Route::post('/user/create', [UserController::class, 'create']);
    Route::delete('/user/{email}/delete', [UserController::class, 'delete']);
});
