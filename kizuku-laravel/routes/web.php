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
Route::get('/partnership/{partnerCampus}', [HomeController::class, 'showPartner'])->name('partner.show');

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
Route::get('/api/dynamic-fields', function(\App\Services\DynamicFormService $svc) {
    $programId = request('program_id');
    $schemaId  = request('schema_id') ?: null;
    $batchId   = request('batch_id') ?: null;

    if (!$programId) return response()->json(['form_id' => null, 'fields' => []]);

    $locale = app()->getLocale();
    
    // Resolve form
    $form = $svc->resolveForm((int)$programId, $schemaId ? (int)$schemaId : null, $batchId ? (int)$batchId : null);
    
    if (!$form) {
        return response()->json(['form_id' => null, 'fields' => []]);
    }

    // If schemaId is provided, we return fields. 
    // In Task 5D, we might want to return ALL fields if the form changed, 
    // but the current frontend architecture expects only "additional" fields if general are SSR.
    // However, since we are now 100% dynamic, maybe we should just replace everything?
    // User request says: "dynamic fields harus diganti sesuai selected form, field dari schema lama harus hilang, tidak boleh duplicate field"
    
    $fields = $svc->getFieldsForForm($form);

    return response()->json([
        'form_id' => $form->id,
        'fields'  => $fields->map(fn($f) => [
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
    ]);
})->name('api.dynamic-fields');

// ═══ PROTECTED ADMIN ROUTES (dengan role check untuk admin) ═══
Route::middleware(['auth', 'admin'])->prefix('dashboard-admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Berita
    Route::resource('berita', \App\Http\Controllers\BeritaController::class)->except(['create'])->parameters([
        'berita' => 'berita'
    ]);

    // CRUD Partner Campus
    Route::resource('partner-campus', \App\Http\Controllers\PartnerCampusController::class)->except(['show']);

    // CRUD Programs & Batches
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class)->except(['show']);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class)->except(['show']);
    Route::resource('program-schemas', \App\Http\Controllers\Admin\ProgramSchemaController::class)->except(['show']);

    // New Google Forms-like Builder Routes
    Route::get('forms', [\App\Http\Controllers\Admin\FormController::class, 'index'])->name('forms.index');
    Route::get('forms/create', [\App\Http\Controllers\Admin\FormController::class, 'create'])->name('forms.create');
    Route::post('forms', [\App\Http\Controllers\Admin\FormController::class, 'store'])->name('forms.store');
    Route::get('forms/{form}/builder', [\App\Http\Controllers\Admin\FormController::class, 'builder'])->name('forms.builder');
    Route::patch('forms/{form}', [\App\Http\Controllers\Admin\FormController::class, 'update'])->name('forms.update');
    Route::get('forms/{form}/preview', [\App\Http\Controllers\Admin\FormController::class, 'preview'])->name('forms.preview');
    Route::post('forms/{form}/publish', [\App\Http\Controllers\Admin\FormController::class, 'publish'])->name('forms.publish');
    Route::post('forms/{form}/draft', [\App\Http\Controllers\Admin\FormController::class, 'draft'])->name('forms.draft');
    Route::post('forms/{form}/archive', [\App\Http\Controllers\Admin\FormController::class, 'archive'])->name('forms.archive');

    Route::post('forms/{form}/fields', [\App\Http\Controllers\Admin\FormBuilderFieldController::class, 'store'])->name('forms.fields.store');
    Route::patch('forms/{form}/fields/{field}', [\App\Http\Controllers\Admin\FormBuilderFieldController::class, 'update'])->name('forms.fields.update');
    Route::post('forms/{form}/fields/{field}/duplicate', [\App\Http\Controllers\Admin\FormBuilderFieldController::class, 'duplicate'])->name('forms.fields.duplicate');
    Route::delete('forms/{form}/fields/{field}', [\App\Http\Controllers\Admin\FormBuilderFieldController::class, 'destroy'])->name('forms.fields.destroy');
    Route::post('forms/{form}/fields/reorder', [\App\Http\Controllers\Admin\FormBuilderFieldController::class, 'reorder'])->name('forms.fields.reorder');

    // Task 5E — Responses Management
    Route::get('forms/{form}/responses', [\App\Http\Controllers\Admin\FormResponseController::class, 'index'])->name('forms.responses.index');
    Route::get('forms/{form}/responses/export/csv', [\App\Http\Controllers\Admin\FormResponseController::class, 'exportCsv'])->name('forms.responses.export.csv');
    Route::get('forms/{form}/responses/{applicant}', [\App\Http\Controllers\Admin\FormResponseController::class, 'show'])->name('forms.responses.show');

    Route::resource('fasilitas', \App\Http\Controllers\Admin\FasilitasController::class)->only(['index', 'store', 'edit', 'update', 'destroy'])->parameters([
        'fasilitas' => 'fasilitas'
    ]);

    // CMS Hero & Testimonials
    Route::resource('hero-sections', \App\Http\Controllers\Admin\HeroSectionController::class)->except(['show']);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except(['show']);
    Route::resource('galleries', \App\Http\Controllers\Admin\GalleryController::class)->except(['show']);
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show']);
    Route::resource('keunggulans', \App\Http\Controllers\Admin\KeunggulanController::class)->except(['show']);

    // CRUD Applicants
    Route::resource('applicants', \App\Http\Controllers\Admin\ApplicantController::class)->only(['index', 'show', 'destroy']);
    Route::patch('applicants/{applicant}/status', [\App\Http\Controllers\Admin\ApplicantController::class, 'updateStatus'])->name('applicants.updateStatus');

    // Protected dynamic file download (admin only, IDOR-guarded)
    Route::get(
        'applicants/{applicant}/dynamic-files/{file}/download',
        [\App\Http\Controllers\Admin\ApplicantDynamicFileDownloadController::class, 'download']
    )->name('applicants.dynamic-files.download');
    Route::get(
        'applicants/{applicant}/documents/{document}/{field}/download',
        [\App\Http\Controllers\Admin\ApplicantDocumentDownloadController::class, 'download']
    )->name('applicants.documents.download');


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
