<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

// ═══ PUBLIC ROUTES ═══
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id', 'jp'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/program/{slug}', [HomeController::class, 'showProgram'])->name('programs.show');
Route::get('/berita/{berita}', [HomeController::class, 'showBerita'])->name('berita.show');

// ═══ STANDALONE PROGRAM PAGES (Alias Routes) ═══
Route::get('/tokutei-ginou', function() {
    return app(HomeController::class)->showProgram('tokutei-ginou-tg');
})->name('pages.tokutei');

Route::get('/engineer-jepang', function() {
    return app(HomeController::class)->showProgram('engineer-jepang-gijinkoku');
})->name('pages.engineer');

Route::get('/ex-internship', function() {
    return app(HomeController::class)->showProgram('engineer-jepang-ex-internship');
})->name('pages.magang');

Route::get('/kursus-bahasa-jepang', function() {
    return app(HomeController::class)->showProgram('kursus-bahasa-jepang');
})->name('pages.kursus');

Route::get('/program', [HomeController::class, 'showAllPrograms'])->name('programs.index');

Route::get('/alur-pendaftaran', [HomeController::class, 'showAlur'])->name('pages.alur');
Route::get('/faq', [HomeController::class, 'faq'])->name('pages.faq');

Route::post('/pendaftaran', [HomeController::class, 'storePendaftaran'])->name('pendaftaran.store');

// AJAX: resolve dynamic fields for a program+schema combo (public, GET, read-only)
// Returns ONLY schema-specific fields when schema_id is provided.
// General (program-level) fields are always rendered SSR in registration-form.blade.php.
Route::get('/api/dynamic-fields', function(\App\Services\DynamicFormService $svc) {
    $programId = request('program_id');
    $schemaId  = request('schema_id') ?: null;

    if (!$programId) return response()->json([]);

    $locale = app()->getLocale();

    if ($schemaId) {
        // Return ONLY the schema-specific fields (schema_id not null)
        // General fields are already rendered SSR — do not duplicate them
        $fields = \App\Models\FormField::active()
            ->where('program_id', (int)$programId)
            ->where('schema_id', (int)$schemaId)
            ->ordered()
            ->get();
    } else {
        // No schema selected — return empty (general fields already visible SSR)
        return response()->json([]);
    }

    return response()->json(
        $fields->map(fn($f) => [
            'id'         => $f->id,
            'field_name' => $f->field_name,
            'type'       => $f->type,
            'label'      => $f->getTranslation('label', $locale) ?: $f->getTranslation('label', 'id'),
            'placeholder'=> $f->getTranslation('placeholder', $locale) ?: $f->getTranslation('placeholder', 'id') ?: '',
            'description'=> $f->getTranslation('description', $locale) ?: $f->getTranslation('description', 'id') ?: '',
            'is_required'=> (bool) $f->is_required,
            'options'    => $f->options,
            'accepted_file_types' => $f->accepted_file_types,
            'max_file_size'       => $f->max_file_size,
        ])
    );
})->name('api.dynamic-fields');

// ═══ PROTECTED ADMIN ROUTES (dengan role check untuk admin) ═══
Route::middleware(['auth', 'admin'])->prefix('dashboard-admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Berita
    Route::resource('berita', \App\Http\Controllers\BeritaController::class)->parameters([
        'berita' => 'berita'
    ]);

    // CRUD Partner Campus
    Route::resource('partner-campus', \App\Http\Controllers\PartnerCampusController::class);

    // CRUD Programs & Batches
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class);
    Route::resource('program-schemas', \App\Http\Controllers\Admin\ProgramSchemaController::class);
    Route::resource('form-fields', \App\Http\Controllers\Admin\FormFieldController::class);
    Route::get('form-fields-schemas', [\App\Http\Controllers\Admin\FormFieldController::class, 'schemasForProgram'])->name('form-fields.schemas');
    Route::resource('fasilitas', \App\Http\Controllers\Admin\FasilitasController::class)->parameters([
        'fasilitas' => 'fasilitas'
    ]);

    // CMS Hero & Testimonials
    Route::resource('hero-sections', \App\Http\Controllers\Admin\HeroSectionController::class);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
    Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);
    Route::resource('keunggulans', \App\Http\Controllers\Admin\KeunggulanController::class);

    // CRUD Applicants
    Route::resource('applicants', \App\Http\Controllers\Admin\ApplicantController::class);
    Route::patch('applicants/{applicant}/status', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateStatus'])->name('applicants.updateStatus');

    // Protected dynamic file download (admin only, IDOR-guarded)
    Route::get(
        'applicants/{applicant}/dynamic-files/{file}/download',
        [\App\Http\Controllers\Admin\ApplicantDynamicFileDownloadController::class, 'download']
    )->name('applicants.dynamic-files.download');


    // Payment Settings
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

    // Export
    Route::get('/export', [ExportController::class, 'index'])->name('export');
    Route::get('/export/download', [ExportController::class, 'download'])->name('export.download');

    // Site Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
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
