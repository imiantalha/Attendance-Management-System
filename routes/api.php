<?php

use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Versioned API endpoints live under /api/v1. Web routes remain separate
| and continue to return Blade views.
|
*/

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/me', fn (Request $request) => new UserResource(
        $request->user()->load('roles')
    ))->name('api.v1.me');

    Route::apiResource('users', UserController::class)
        ->only(['index', 'show'])
        ->names('api.v1.users');

    Route::apiResource('attendances', AttendanceController::class)
        ->only(['index', 'show', 'store', 'update', 'destroy'])
        ->names('api.v1.attendances');
});

// Backwards-compatible endpoint for existing clients.
Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => new UserResource(
    $request->user()->load('roles')
));
