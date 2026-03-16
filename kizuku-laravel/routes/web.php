<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ═══ PUBLIC ROUTES ═══
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kampus-partner', [HomeController::class, 'allCampuses'])->name('kampus-partner.all');
Route::get('/program/{slug}', [HomeController::class, 'showProgram'])->name('programs.show');
Route::post('/pendaftaran', [HomeController::class, 'storePendaftaran'])->name('pendaftaran.store');

// ═══ PROTECTED ADMIN ROUTES (dengan role check untuk admin) ═══
Route::middleware(['auth', 'admin'])->prefix('dashboard-admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Berita
    Route::resource('berita', \App\Http\Controllers\BeritaController::class);

    // CRUD Partner Campus
    Route::resource('partner-campus', \App\Http\Controllers\PartnerCampusController::class);

    // CRUD Programs & Batches
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class);

    // CRUD Applicants
    Route::resource('applicants', \App\Http\Controllers\Admin\ApplicantController::class);
    Route::patch('applicants/{applicant}/status', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateStatus'])->name('applicants.updateStatus');

    // Payment Settings
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

    // Export
    Route::get('/export', [ExportController::class, 'index'])->name('export');
    Route::get('/export/download', [ExportController::class, 'download'])->name('export.download');
});

// ═══ BREEZE AUTH ROUTES ═══
Route::get('/dashboard', function () {
    // Check if user is admin
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Regular user stays on dashboard
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
