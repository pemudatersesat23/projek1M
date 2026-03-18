<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::orderBy('urutan')->get();
        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'urutan' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('fasilitas_images', 'public');
        }

        Fasilitas::create([
            'nama'   => $request->nama,
            'image'  => $imagePath,
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function edit(Fasilitas $fasilitas)
    {
        return view('admin.fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, Fasilitas $fasilitas)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'urutan' => 'nullable|integer',
        ]);

        $data = [
            'nama'   => $request->nama,
            'urutan' => $request->urutan ?? $fasilitas->urutan,
        ];

        if ($request->hasFile('image')) {
            if ($fasilitas->image && Storage::disk('public')->exists($fasilitas->image)) {
                Storage::disk('public')->delete($fasilitas->image);
            }
            $data['image'] = $request->file('image')->store('fasilitas_images', 'public');
        }

        $fasilitas->update($data);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    public function destroy(Fasilitas $fasilitas)
    {
        if ($fasilitas->image && Storage::disk('public')->exists($fasilitas->image)) {
            Storage::disk('public')->delete($fasilitas->image);
        }
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
