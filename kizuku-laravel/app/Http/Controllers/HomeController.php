<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Applicant;
use App\Models\Program;
use App\Models\Batch;
use Illuminate\Http\Request;

use App\Models\ApplicantDocument;
use App\Models\PartnerCampus;
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

        // Resolve dynamic form — fallback logic handled by service
        $form            = $dynamicFormService->resolveForm($program->id, null, $activeBatch?->id);
        $dynamicFields   = $form ? $dynamicFormService->getFieldsForForm($form) : collect();
        $hasDynamicFiles = $dynamicFormService->hasFileFields($dynamicFields);
        $currentLocale   = app()->getLocale();

        return view('program-detail', compact(
            'program', 'activeBatch', 'nextBatch', 'batchHistory',
            'form', 'dynamicFields', 'hasDynamicFiles', 'currentLocale'
        ));
    }



    public function storePendaftaran(
        \App\Http\Requests\PendaftaranRequest $request,
        \App\Services\RegistrationService $registrationService
    ) {
        \Log::info('Pendaftaran started (100% Dynamic Mode)', $request->safe()->except(['dynamic_files']));

        try {
            $applicant = $registrationService->register($request);
            \Log::info('Pendaftaran complete', ['applicant_id' => $applicant->id]);

            $applicant->loadMissing('form');
            $successMessage = $applicant->form?->getTranslation('success_message', app()->getLocale(), false)
                ?: $applicant->form?->getTranslation('success_message', 'id', false)
                ?: __('messages.form.registration_success');

            return redirect()->back()->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
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

    public function showPartner(PartnerCampus $partnerCampus)
    {
        $recentCampuses = PartnerCampus::where('id', '!=', $partnerCampus->id)->latest()->take(5)->get();
        return view('partner-detail', compact('partnerCampus', 'recentCampuses'));
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
