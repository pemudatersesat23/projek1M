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
        $batches = Batch::with('program')->latest()->get();
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        return view('admin.batches.create', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:akan_dibuka,dibuka,ditutup,berjalan,selesai',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date',
            'class_start' => 'nullable|date',
            'class_estimate_end' => 'nullable|date',
            'quota' => 'nullable|integer',
            'link_form' => 'nullable|string',
        ]);

        Batch::create($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil dibuat.');
    }

    public function show(Batch $batch)
    {
        return view('admin.batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        $programs = Program::where('status', 'aktif')->get();
        return view('admin.batches.edit', compact('batch', 'programs'));
    }

    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:akan_dibuka,dibuka,ditutup,berjalan,selesai',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date',
            'class_start' => 'nullable|date',
            'class_estimate_end' => 'nullable|date',
            'quota' => 'nullable|integer',
            'link_form' => 'nullable|string',
        ]);

        $batch->update($validated);

        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil diperbarui.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil dihapus.');
    }
}
