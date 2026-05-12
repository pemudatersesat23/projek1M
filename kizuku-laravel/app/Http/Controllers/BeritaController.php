<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Http\Requests\BeritaRequest;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::latest()->get();
        return view('admin.berita.index', compact('beritas'));
    }

    public function show(Berita $berita)
    {
        return view('admin.berita.show', compact('berita'));
    }

    public function store(BeritaRequest $request)
    {
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('berita_images', 'public');
        }

        Berita::create([
            'judul'          => $validated['judul'],
            'kategori'       => $validated['kategori'] ?? 'kat-info',
            'image'          => $imagePath,
            'lokasi'         => $validated['lokasi'] ?? null,
            'isi'            => $validated['isi'] ?? null,
            'status_publish' => $validated['status_publish'],
        ]);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil dipublish!');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(BeritaRequest $request, Berita $berita)
    {
        $validated = $request->validated();

        $data = [
            'judul'          => $validated['judul'],
            'kategori'       => $validated['kategori'] ?? 'kat-info',
            'lokasi'         => $validated['lokasi'] ?? null,
            'isi'            => $validated['isi'] ?? null,
            'status_publish' => $validated['status_publish'],
        ];

        if ($request->hasFile('image')) {
            if ($berita->image && Storage::disk('public')->exists($berita->image)) {
                Storage::disk('public')->delete($berita->image);
            }
            $data['image'] = $request->file('image')->store('berita_images', 'public');
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->image && Storage::disk('public')->exists($berita->image)) {
            Storage::disk('public')->delete($berita->image);
        }
        $berita->delete();

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil dihapus!');
    }
}
