<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');

// ตัวอย่างหน้า dashboard ที่ต้อง login
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware('auth')->name('dashboard');

// Routes สำหรับการเช่ารถ
Route::middleware('auth')->group(function () {
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
    Route::delete('/rentals/{rental}', [RentalController::class, 'destroy'])->name('rentals.destroy');
    Route::get('/rentals/export', [RentalController::class, 'export'])->name('rentals.export');
});

Route::get('/test-rentals', function() {
    $rentals = App\Models\Rental::all();
    return response()->json([
        'count' => $rentals->count(),
        'rentals' => $rentals->take(3)->map(function($rental) {
            return [
                'id' => $rental->id,
                'firstname' => $rental->firstname,
                'lastname' => $rental->lastname,
                'phone' => $rental->phone,
                'status' => $rental->status,
                'start_date' => $rental->start_date,
                'end_date' => $rental->end_date
            ];
        })
    ]);
});

Route::get('/test', function() {
    return view('pages.test');
});