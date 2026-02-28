<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kota', 'like', "%{$s}%");
            });
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas = $query->latest()->get();

        $stats = [
            'total'     => Siswa::count(),
            'aktif'     => Siswa::where('status', 'Aktif')->count(),
            'berangkat' => Siswa::where('status', 'Berangkat')->count(),
            'proses'    => Siswa::where('status', 'Proses')->count(),
        ];

        return view('admin.siswa.index', compact('siswas', 'stats'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'wa'      => 'required|string|max:50',
            'kota'    => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        Siswa::create($request->all());

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$request->nama}\" berhasil disimpan!");
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'wa'      => 'required|string|max:50',
            'kota'    => 'required|string|max:255',
            'program' => 'required|string|max:255',
        ]);

        $siswa->update($request->all());

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$siswa->nama}\" berhasil diupdate!");
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama;
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Data \"{$nama}\" berhasil dihapus!");
    }
}
