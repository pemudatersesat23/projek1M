<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Applicant;
use App\Models\Program;
use App\Models\Batch;
use Illuminate\Http\Request;

use App\Models\ApplicantDocument;
use Illuminate\Support\Facades\Storage;
use App\Services\DynamicFormService;

class HomeController extends Controller
{
    public function index()
    {
        $beritas      = Berita::published()->latest()->take(7)->get();
        $campuses     = \App\Models\PartnerCampus::latest()->get();
        $heroSections = \App\Models\HeroSection::where('is_active', true)->orderBy('sort_order')->get();
        $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->get();
        $fasilitas    = \App\Models\Fasilitas::orderBy('urutan')->get();
        $galleries    = \App\Models\Gallery::where('is_active', true)->orderBy('order')->get();

        $featuredPrograms = \App\Models\Program::active()
            ->featured()
            ->ordered()
            ->with(['activeBatches', 'activeSchemas'])
            ->get();

        if ($featuredPrograms->isEmpty()) {
            $featuredPrograms = \App\Models\Program::active()
                ->ordered()
                ->with(['activeBatches', 'activeSchemas'])
                ->get();
        }

        // ── Site Stats ───────────────────────────────────────────────────────
        // Satu query untuk semua setting stats (bukan 5 query terpisah di blade).
        $statKeys    = ['stats_active', 'stats_alumni', 'stats_success', 'stats_years', 'stats_programs'];
        $settingsMap = \App\Models\Setting::whereIn('key', $statKeys)->get()->keyBy('key');

        $siteStats = [
            'active'   => (bool) ($settingsMap->get('stats_active')?->value ?? 1),
            'items'    => [
                [
                    'value'     => $settingsMap->get('stats_alumni')?->value   ?? '1000+',
                    'label_key' => 'messages.home.stats.alumni',
                ],
                [
                    'value'     => $settingsMap->get('stats_success')?->value  ?? '98%',
                    'label_key' => 'messages.home.stats.success',
                ],
                [
                    'value'     => $settingsMap->get('stats_years')?->value    ?? '10+',
                    'label_key' => 'messages.home.stats.years',
                ],
                [
                    'value'     => $settingsMap->get('stats_programs')?->value ?? '4',
                    'label_key' => 'messages.home.stats.programs',
                ],
            ],
        ];

        return view('home', compact(
            'beritas', 'campuses', 'heroSections', 'testimonials',
            'featuredPrograms', 'fasilitas', 'galleries', 'siteStats'
        ));
    }



    public function showProgram($slug, DynamicFormService $dynamicFormService)
    {
        $program = Program::where('slug', $slug)
            ->with(['batches' => function($q) {
                $q->orderBy('created_at', 'desc');
            }, 'activeBatches', 'activeSchemas'])
            ->firstOrFail();

        $activeBatch  = $program->currentOpenBatch();
        $nextBatch    = $program->batches->where('status', 'akan_dibuka')->sortBy('tanggal_buka')->first();
        $batchHistory = $program->batches;

        // Resolve dynamic form fields — schema_id null at page load (schema not yet chosen)
        // All general fields are loaded here; schema-specific will merge if schema is pre-selected
        $dynamicFields   = $dynamicFormService->getFieldsFor($program->id);
        $hasDynamicFiles = $dynamicFormService->hasFileFields($dynamicFields);
        $currentLocale   = app()->getLocale();

        return view('program-detail', compact(
            'program', 'activeBatch', 'nextBatch', 'batchHistory',
            'dynamicFields', 'hasDynamicFiles', 'currentLocale'
        ));
    }



    public function storePendaftaran(
        \App\Http\Requests\PendaftaranRequest $request,
        \App\Services\FileUploadService $uploadService,
        \App\Services\DynamicForm\DynamicValidationService $dynValidator,
        \App\Services\DynamicForm\DynamicFileUploadService $dynUploader,
        \App\Services\DynamicFormService $dynFormService
    ) {
        $fixedFileInputs = ['ktp', 'kk', 'foto', 'ijazah', 'sertifikat', 'cv', 'transkrip', 'bukti_sosmed'];
        \Log::info('Pendaftaran started', $request->safe()->except(array_merge($fixedFileInputs, ['dynamic_files'])));

        // 1. Resolve active form fields for this program/schema
        $programId    = (int) $request->input('program_id');
        $schemaId     = $request->input('schema_id') ? (int) $request->input('schema_id') : null;
        $activeFields = $dynFormService->getFieldsFor($programId, $schemaId);

        // 2. Validate dynamic payload (unknown-field guard + per-field rules)
        try {
            $dynValidator->validateDynamicPayload($request, $activeFields);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        // 3. DB transaction with file-upload tracking for rollback
        $uploadedPaths = [];

        try {
            $applicant = \Illuminate\Support\Facades\DB::transaction(
                function () use (
                    $request, $uploadService, $dynUploader,
                    $fixedFileInputs, $activeFields, &$uploadedPaths
                ) {
                    // 3a. Create Applicant (existing flow)
                    $applicant = Applicant::create(
                        $request->safe()->except(
                            array_merge($fixedFileInputs, ['dynamic_answers', 'dynamic_files'])
                        )
                    );
                    \Log::info('Applicant created', ['id' => $applicant->id]);

                    // 3b. Fixed document uploads (existing flow)
                    $filesToUpload = [];
                    foreach ($fixedFileInputs as $field) {
                        if ($request->hasFile($field)) {
                            $filesToUpload[$field] = $request->file($field);
                        }
                    }
                    if (!empty($filesToUpload)) {
                        $docs = $uploadService->uploadMultiple($filesToUpload);
                        $docs['applicant_id'] = $applicant->id;
                        ApplicantDocument::create($docs);
                        \Log::info('Fixed documents saved');
                    }

                    // 3c. Save dynamic answers
                    $dynamicAnswers = $request->input('dynamic_answers', []);
                    foreach ($activeFields->filter(fn($f) => !$f->isFile()) as $field) {
                        $rawValue = $dynamicAnswers[$field->field_name] ?? null;
                        if ($rawValue === null && !$field->is_required) continue;

                        \App\Models\ApplicantFormAnswer::create([
                            'applicant_id'         => $applicant->id,
                            'form_field_id'        => $field->id,
                            'value'                => is_array($rawValue) ? $rawValue : (string) $rawValue,
                            'field_label_snapshot' => $field->label,
                            'field_type_snapshot'  => $field->type,
                        ]);
                    }

                    // 3d. Upload and save dynamic files
                    foreach ($activeFields->filter(fn($f) => $f->isFile()) as $field) {
                        $fileKey = "dynamic_files.{$field->field_name}";
                        if (!$request->hasFile($fileKey)) continue;

                        $uploaded        = $request->file($fileKey);
                        $meta            = $dynUploader->upload($uploaded, $applicant->id, $field->id);
                        $uploadedPaths[] = $meta['path'];

                        \App\Models\ApplicantDynamicFile::create([
                            'applicant_id'         => $applicant->id,
                            'form_field_id'        => $field->id,
                            'file_path'            => $meta['path'],
                            'original_name'        => $meta['original_name'],
                            'mime_type'            => $meta['mime_type'],
                            'size'                 => $meta['size'],
                            'field_label_snapshot' => $field->label,
                            'field_type_snapshot'  => $field->type,
                        ]);
                    }

                    return $applicant;
                }
            );

            \Log::info('Pendaftaran complete', ['applicant_id' => $applicant->id]);
            return redirect()->back()->with('success', __('messages.form.registration_success'));

        } catch (\Exception $e) {
            // Cleanup any files that were uploaded before the failure
            if (!empty($uploadedPaths)) {
                $dynUploader->deleteMany($uploadedPaths);
            }
            \Log::error('Pendaftaran error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withErrors(__('messages.form.error_occurred') ?? 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function showBerita(Berita $berita)
    {
        // Get recent news for sidebar/footer of detail page
        $recentNews = Berita::published()->where('id', '!=', $berita->id)->latest()->take(5)->get();
        return view('berita-detail', compact('berita', 'recentNews'));
    }

    public function showAlur()
    {
        return view('alur-pendaftaran');
    }

    public function showAllPrograms()
    {
        $programs = Program::where('status', 'aktif')->with(['batches' => function($q) {
            $q->whereIn('status', ['dibuka', 'akan_dibuka']);
        }])->get();
        return view('programs.index', compact('programs'));
    }

    public function faq()
    {
        $faqsRaw = \App\Models\Faq::where('is_active', true)->orderBy('order')->get();
        // Group by category, handle empty categories as "Umum"
        $faqsGrouped = [];
        foreach($faqsRaw as $f) {
            $cat = $f->getTranslation('kategori', app()->getLocale(), false);
            if(empty($cat) && app()->getLocale() == 'jp') {
                $cat = '一般'; // Umum in JP
            } elseif (empty($cat)) {
                $cat = 'Umum';
            }
            $faqsGrouped[$cat][] = $f;
        }

        return view('faq', compact('faqsGrouped'));
    }
}
