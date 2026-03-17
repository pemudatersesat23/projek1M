<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Batch;
use App\Models\Program;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('program')->latest()->paginate(10);
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        $batch = new Batch(); // Inisialisasi objek kosong untuk form
        return view('admin.batches.create', compact('programs', 'batch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'nama_batch' => 'required|string|max:255',
            'status' => 'required|in:akan_dibuka,dibuka,ditutup,berjalan,selesai',
            'tanggal_buka' => 'nullable|date',
            'tanggal_tutup' => 'nullable|date',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'kuota' => 'nullable|integer',
            'link_form' => 'nullable|url',
        ]);

        Batch::create($request->all());

        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil ditambahkan.');
    }

    public function show(Batch $batch)
    {
        return view('admin.batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        $programs = Program::all();
        return view('admin.batches.edit', compact('batch', 'programs'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'nama_batch' => 'required|string|max:255',
            'status' => 'required|in:akan_dibuka,dibuka,ditutup,berjalan,selesai',
            'tanggal_buka' => 'nullable|date',
            'tanggal_tutup' => 'nullable|date',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'kuota' => 'nullable|integer',
            'link_form' => 'nullable|url',
        ]);

        $batch->update($request->all());

        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil diperbarui.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil dihapus.');
    }
}
