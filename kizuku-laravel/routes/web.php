<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ═══ PUBLIC ROUTES ═══
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/pendaftaran', [HomeController::class, 'storePendaftaran'])->name('pendaftaran.store');

// ═══ ADMIN ROUTES (dilindungi auth) ═══
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Siswa
    Route::resource('siswa', SiswaController::class);

    // CRUD Berita
    Route::resource('berita', BeritaController::class);

    // Payment Settings
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

    // Export
    Route::get('/export', [ExportController::class, 'index'])->name('export');
    Route::get('/export/download', [ExportController::class, 'download'])->name('export.download');
});

// ═══ BREEZE AUTH ROUTES ═══
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
