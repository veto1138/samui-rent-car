<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\MPDFController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\GoogleCalendarController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');

// ตัวอย่างหน้า dashboard ที่ต้อง login
// Route::get('/dashboard', function () {
//     return view('pages.dashboard');
// })->middleware('auth')->name('dashboard');

Route::get('/', [RentalController::class, 'create'])->name('rentals.create');
Route::prefix('google-calendar')->group(function () {
    Route::post('/rentals/{rental}/add', [GoogleCalendarController::class, 'addToCalendar'])->name('google-calendar.rentals.add');
});

// Routes สำหรับการเช่ารถ
Route::middleware('auth')->group(function () {
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
    Route::put('/rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
    Route::delete('/rentals/{rental}', [RentalController::class, 'destroy'])->name('rentals.destroy');
    // Route::get('/rentals/export', [RentalController::class, 'export'])->name('rentals.export');
    // Route::get('/rentals/export-pdf/{rental}', [RentalController::class, 'exportPdf'])->name('rentals.export-pdf');
    Route::get('/rentals/export-pdf-single/{rental}', [RentalController::class, 'exportPdfSingle'])->name('rentals.export-pdf-single');
    
    // Routes สำหรับตรวจสอบการเช่ารถซ้ำกัน
    Route::post('/rentals/check-available-cars', [RentalController::class, 'checkAvailableCars'])->name('rentals.check-available-cars');
    Route::post('/rentals/check-duplicate', [RentalController::class, 'checkDuplicateRentalAjax'])->name('rentals.check-duplicate');
    
    // Routes สำหรับ Google Calendar
    Route::prefix('google-calendar')->group(function () {
        Route::get('/', function() {
            return view('google-calendar.index');
        })->name('google-calendar.index');
        Route::put('/rentals/{rental}/update', [GoogleCalendarController::class, 'updateInCalendar'])->name('google-calendar.rentals.update');
        Route::delete('/rentals/{rental}/remove', [GoogleCalendarController::class, 'removeFromCalendar'])->name('google-calendar.rentals.remove');
        Route::get('/rentals/{rental}/view', [GoogleCalendarController::class, 'viewInCalendar'])->name('google-calendar.rentals.view');
        Route::get('/rentals/{rental}/test-url', [GoogleCalendarController::class, 'testEventUrl'])->name('google-calendar.rentals.test-url');
        Route::post('/clear-invalid-events', [GoogleCalendarController::class, 'clearInvalidEventIds'])->name('google-calendar.clear-invalid-events');
        Route::post('/test-connection', [GoogleCalendarController::class, 'testConnection'])->name('google-calendar.test-connection');
        Route::post('/sync-all', [GoogleCalendarController::class, 'syncAllRentals'])->name('google-calendar.sync-all');
    });
    
    // API Routes สำหรับ Dashboard
    Route::get('/api/statistics', function() {
        $totalCars = \App\Models\Car::count();
        $availableCars = \App\Models\Car::where('status', 'available')->count();
        $rentedCars = \App\Models\Car::where('status', 'rented')->count();
        $maintenanceCars = \App\Models\Car::where('status', 'maintenance')->count();
        
        return response()->json([
            'total_cars' => $totalCars,
            'available_cars' => $availableCars,
            'rented_cars' => $rentedCars,
            'maintenance_cars' => $maintenanceCars
        ]);
    })->name('api.statistics');
    
    Route::get('/api/current-rentals', function() {
        $currentRentals = \App\Models\Rental::whereIn('status', ['pending', 'using'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orWhere(function($query) {
                $query->where('start_date', '>=', now())
                      ->where('status', 'pending');
            })
            ->orderBy('start_date', 'asc')
            ->get();
        
        return response()->json([
            'rentals' => $currentRentals
        ]);
    })->name('api.current-rentals');
    
    Route::get('/api/available-cars', function() {
        $availableCars = \App\Models\Car::where('status', 'available')
            ->orderBy('full_name', 'asc')
            ->get();
        
        return response()->json([
            'cars' => $availableCars
        ]);
    })->name('api.available-cars');
});

// Routes สำหรับจัดการรถยนต์
Route::middleware('auth')->group(function () {
    Route::resource('cars', CarController::class);
});

// Routes สำหรับจัดการผู้ใช้งาน
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});