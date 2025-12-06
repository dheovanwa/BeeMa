<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Download booking file - accessible by both dosen and mahasiswa
    Route::get('/booking/{booking}/download', function (App\Models\Booking $booking) {
        $user = Auth::user();
        
        // Check if user is authorized to download this file
        // Mahasiswa can download their own files
        // Dosen can download files from bookings on their schedules
        if ($user->role === 'mahasiswa' && $booking->user_id === $user->id) {
            // Mahasiswa downloading their own file
        } elseif ($user->role === 'dosen' && $booking->schedule->user_id === $user->id) {
            // Dosen downloading file from their schedule
        } else {
            abort(403, 'Unauthorized to download this file.');
        }
        
        if (!$booking->file_path || !Storage::disk('public')->exists($booking->file_path)) {
            abort(404, 'File not found.');
        }
        
        return response()->download(storage_path('app/public/' . $booking->file_path));
    })->name('booking.download');
    
    // Dosen routes - only accessible by users with role 'dosen'
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        // GET /dosen/dashboard -> List of own schedules
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
        
        // GET /dosen/incoming-requests -> View booking requests
        Route::get('/incoming-requests', [DosenController::class, 'incomingRequests'])->name('incoming-requests');
        
        // RESOURCE /dosen/schedules -> CRUD Schedules
        Route::resource('schedules', DosenController::class)->except(['index', 'show']);
        
        // PATCH /dosen/booking/{id}/status -> Approve/Reject student request
        Route::patch('/booking/{booking}/status', [DosenController::class, 'updateBookingStatus'])->name('booking.status');
    });
    
    // Mahasiswa routes - only accessible by users with role 'mahasiswa'
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // GET /mahasiswa/dashboard -> List of available schedules from all Dosen (Searchable)
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        
        // POST /mahasiswa/booking -> Store booking + Handle File Upload
        Route::post('/booking', [MahasiswaController::class, 'storeBooking'])->name('booking.store');
        
        // GET /mahasiswa/my-bookings -> See history and status
        Route::get('/my-bookings', [MahasiswaController::class, 'myBookings'])->name('my-bookings');
        
        // Additional route for booking form
        Route::get('/booking/{schedule}', [MahasiswaController::class, 'showBookingForm'])->name('booking.form');
    });

    // Admin routes - only accessible by users with role 'admin'
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // GET /admin/dashboard -> Admin dashboard with statistics
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // GET /admin/assignments -> List all assignments
        Route::get('/assignments', [AdminController::class, 'index'])->name('assignments.index');
        
        // GET /admin/assignments/create -> Show create assignment form
        Route::get('/assignments/create', [AdminController::class, 'create'])->name('assignments.create');
        
        // POST /admin/assignments -> Store new assignment
        Route::post('/assignments', [AdminController::class, 'store'])->name('assignments.store');
        
        // DELETE /admin/assignments/{assignment} -> Delete assignment
        Route::delete('/assignments/{assignment}', [AdminController::class, 'destroy'])->name('assignments.destroy');
    });
});
