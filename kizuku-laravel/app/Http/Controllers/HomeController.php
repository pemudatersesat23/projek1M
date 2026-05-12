<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Applicant;
use App\Models\Program;
use App\Models\Batch;
use Illuminate\Http\Request;

use App\Models\ApplicantDocument;
use Illuminate\Support\Facades\Storage;

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

        // ── Programs ────────────────────────────────────────────────────────
        // Ambil featured programs; jika tidak ada, fallback ke semua aktif.
        // Sehingga blade TIDAK perlu melakukan query sendiri.
        $batchQuery = fn ($q) => $q->whereIn('status', ['dibuka', 'akan_dibuka']);

        $featuredPrograms = \App\Models\Program::with(['batches' => $batchQuery])
            ->where('is_featured', true)
            ->where('status', 'aktif')
            ->get();

        if ($featuredPrograms->isEmpty()) {
            $featuredPrograms = \App\Models\Program::with(['batches' => $batchQuery])
                ->where('status', 'aktif')
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



    public function showProgram($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();
        
        // Find active batch (dibuka) or next upcoming batch (akan_dibuka)
        $activeBatch = $program->batches()->where('status', 'dibuka')->first();
        $nextBatch = $program->batches()->where('status', 'akan_dibuka')->orderBy('tanggal_buka')->first();
        
        // Get batch history (history of previous and current batches)
        $batchHistory = $program->batches()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('program-detail', compact('program', 'activeBatch', 'nextBatch', 'batchHistory'));
    }



    public function storePendaftaran(\App\Http\Requests\PendaftaranRequest $request, \App\Services\FileUploadService $uploadService)
    {
        $fileInputs = ['ktp', 'kk', 'foto', 'ijazah', 'sertifikat', 'cv', 'transkrip', 'bukti_sosmed'];
        \Log::info('Pendaftaran submission started', $request->safe()->except($fileInputs));
        
        try {
            \Log::info('Validation passed via Request class');

            $applicant = Applicant::create($request->safe()->except($fileInputs));
            \Log::info('Applicant created', ['id' => $applicant->id]);

            // Handle File Uploads using Service
            $filesToUpload = [];
            foreach ($fileInputs as $field) {
                if ($request->hasFile($field)) {
                    $filesToUpload[$field] = $request->file($field);
                }
            }

            if (!empty($filesToUpload)) {
                $docs = $uploadService->uploadMultiple($filesToUpload);
                $docs['applicant_id'] = $applicant->id;
                ApplicantDocument::create($docs);
                \Log::info('Documents saved via Service');
            }

            \Log::info('Redirecting back');
            return redirect()->back()->with('success', __('messages.form.registration_success'));
        } catch (\Exception $e) {
            \Log::error('Pendaftaran error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withErrors(__('messages.form.error_occurred') ?? 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')->withInput();
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
