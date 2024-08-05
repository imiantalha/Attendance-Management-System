<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
    Route::get('attendances/report/{id}', [AttendanceController::class, 'report'])->name('attendances.report');
    Route::get('attendances/week-report/{id}', [AttendanceController::class, 'weeklyReport'])->name('attendances.weekly.report');
    Route::get('attendances/month-report/{id}', [AttendanceController::class, 'monthlyReport'])->name('attendances.monthly.report');
    Route::get('attendances/year-report/{id}', [AttendanceController::class, 'yearlyReport'])->name('attendances.yearly.report');
});

require __DIR__.'/auth.php';
