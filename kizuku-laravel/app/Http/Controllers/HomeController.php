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
        $beritas = Berita::published()->latest()->take(7)->get();
        $campuses = \App\Models\PartnerCampus::latest()->get();
        $heroSections = \App\Models\HeroSection::where('is_active', true)->latest()->get();
        $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->get();
        $featuredPrograms = \App\Models\Program::with(['batches' => function($q) {
            $q->whereIn('status', ['dibuka', 'akan_dibuka']);
        }])->where('is_featured', true)->where('status', 'aktif')->take(5)->get();
        $fasilitas = \App\Models\Fasilitas::orderBy('urutan')->get();
        $galleries = \App\Models\Gallery::where('is_active', true)->orderBy('order')->get();

        return view('home', compact('beritas', 'campuses', 'heroSections', 'testimonials', 'featuredPrograms', 'fasilitas', 'galleries'));
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
}
