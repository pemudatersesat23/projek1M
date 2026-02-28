<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ═══ PUBLIC ROUTES ═══
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/pendaftaran', [HomeController::class, 'storePendaftaran'])->name('pendaftaran.store');

// ═══ ADMIN ROUTES (dilindungi auth) ═══
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard → redirect ke siswa
    Route::get('/', function () {
        return redirect()->route('admin.siswa.index');
    })->name('dashboard');

    // CRUD Siswa
    Route::resource('siswa', SiswaController::class);

    // CRUD Berita
    Route::resource('berita', BeritaController::class);
});

// ═══ BREEZE AUTH ROUTES ═══
Route::get('/dashboard', function () {
    return redirect()->route('admin.siswa.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
