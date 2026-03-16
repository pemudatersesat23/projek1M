<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Siswa;
use Illuminate\Http\Request;

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
        $program = \App\Models\Program::where('slug', $slug)->firstOrFail();
        
        // Find active batch (dibuka) or next upcoming batch (akan_dibuka)
        $activeBatch = $program->batches()->where('status', 'dibuka')->first();
        $nextBatch = $program->batches()->where('status', 'akan_dibuka')->orderBy('registration_start')->first();

        return view('program-detail', compact('program', 'activeBatch', 'nextBatch'));
    }

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'wa'   => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'program' => 'nullable|string|max:255',
            'batch_id' => 'nullable|exists:batches,id',
            'catatan' => 'nullable|string',
        ]);

        Siswa::create([
            'nama'     => $request->nama,
            'wa'       => $request->wa,
            'email'    => $request->email,
            'kota'     => '-',
            'program'  => $request->program ?? 'Belum dipilih',
            'batch_id' => $request->batch_id,
            'status'   => 'Proses',
            'catatan'  => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Kami akan menghubungi kamu segera.');
    }
}
