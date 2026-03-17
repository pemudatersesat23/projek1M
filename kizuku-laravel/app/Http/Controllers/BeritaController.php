<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::latest()->get();
        return view('admin.berita.index', compact('beritas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'status_publish' => 'required|in:draft,published',
        ]);

        Berita::create([
            'judul'          => $request->judul,
            'kategori'       => $request->kategori ?? 'kat-info',
            'emoji'          => $request->emoji ?? '📢',
            'isi'            => $request->isi,
            'status_publish' => $request->status_publish,
        ]);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil dipublish!');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'status_publish' => 'required|in:draft,published',
        ]);

        $berita->update([
            'judul'          => $request->judul,
            'kategori'       => $request->kategori ?? 'kat-info',
            'emoji'          => $request->emoji ?? '📢',
            'isi'            => $request->isi,
            'status_publish' => $request->status_publish,
        ]);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil dihapus!');
    }
}
