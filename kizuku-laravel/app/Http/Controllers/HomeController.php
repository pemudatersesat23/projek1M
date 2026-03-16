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
        $beritas = Berita::latest()->take(7)->get();
        $campuses = \App\Models\PartnerCampus::latest()->take(4)->get();

        return view('home', compact('beritas', 'campuses'));
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

        return view('program-detail', compact('program', 'activeBatch', 'nextBatch'));
    }

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'ttl' => 'required|string|max:255',
            'alamat' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pendidikan' => 'required|string|max:255',
            'pengalaman' => 'nullable|string',
            'motivasi' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'batch_id' => 'required|exists:batches,id',
            // File Validation
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'foto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $applicant = Applicant::create($request->except(['ktp', 'kk', 'foto', 'ijazah', 'sertifikat']));

        // Handle File Uploads
        $docs = [];
        $fileFields = ['ktp', 'kk', 'foto', 'ijazah', 'sertifikat'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('documents', 'public');
                $docs[$field] = $path;
            }
        }

        if (!empty($docs)) {
            $docs['applicant_id'] = $applicant->id;
            ApplicantDocument::create($docs);
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Kami akan mereview data kamu segera.');
    }
}
