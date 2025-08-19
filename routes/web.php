<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');

// ตัวอย่างหน้า dashboard ที่ต้อง login
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('auth');

// Routes สำหรับการเช่ารถ
Route::get('/rentals/create', [App\Http\Controllers\RentalController::class, 'create'])->name('rentals.create');
Route::post('/rentals', [App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');