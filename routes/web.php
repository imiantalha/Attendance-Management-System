<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('attendances', AttendanceController::class);

    Route::prefix('users/{user}/attendance')->group(function () {
        Route::get('/report', [AttendanceController::class, 'report'])->name('attendances.report');
        Route::get('/week-report', [AttendanceController::class, 'weeklyReport'])->name('attendances.weekly.report');
        Route::get('/month-report', [AttendanceController::class, 'monthlyReport'])->name('attendances.monthly.report');
        Route::get('/year-report', [AttendanceController::class, 'yearlyReport'])->name('attendances.yearly.report');
    });
});

require __DIR__ . '/auth.php';
