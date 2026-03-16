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

    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'wa'   => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'program' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        Siswa::create([
            'nama'     => $request->nama,
            'wa'       => $request->wa,
            'email'    => $request->email,
            'kota'     => '-',
            'program'  => $request->program ?? 'Belum dipilih',
            'status'   => 'Proses',
            'catatan'  => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Kami akan menghubungi kamu segera.');
    }
}
