<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Program;
use App\Http\Requests\Admin\BatchRequest;

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
        $batch = new Batch();
        return view('admin.batches.create', compact('programs', 'batch'));
    }

    public function store(BatchRequest $request)
    {
        Batch::create($request->validated());
        
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

    public function update(BatchRequest $request, Batch $batch)
    {
        $batch->update($request->validated());

        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil diperbarui.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch berhasil dihapus.');
    }
}
