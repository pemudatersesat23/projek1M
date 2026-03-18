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
        $campuses = \App\Models\PartnerCampus::latest()->take(4)->get();
        $heroSections = \App\Models\HeroSection::where('is_active', true)->latest()->get();
        $testimonials = \App\Models\Testimonial::where('is_active', true)->latest()->get();
        $featuredPrograms = \App\Models\Program::where('is_featured', true)->where('status', 'aktif')->take(5)->get();

        return view('home', compact('beritas', 'campuses', 'heroSections', 'testimonials', 'featuredPrograms'));
    }

    public function allCampuses()
    {
        $campuses = \App\Models\PartnerCampus::latest()->get();
        return view('kampus-partner', compact('campuses'));
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

    public function showBerita(Berita $berita)
    {
        return view('berita-detail', compact('berita'));
    }

    public function storePendaftaran(\App\Http\Requests\PendaftaranRequest $request, \App\Services\FileUploadService $uploadService)
    {
        \Log::info('Pendaftaran submission started', $request->safe()->except(['ktp', 'kk', 'foto', 'ijazah', 'sertifikat']));
        
        try {
            \Log::info('Validation passed via Request class');

            $applicant = Applicant::create($request->safe()->except(['ktp', 'kk', 'foto', 'ijazah', 'sertifikat']));
            \Log::info('Applicant created', ['id' => $applicant->id]);

            // Handle File Uploads using Service
            $fileFields = ['ktp', 'kk', 'foto', 'ijazah', 'sertifikat'];
            $filesToUpload = [];
            foreach ($fileFields as $field) {
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
}
