<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramSchema;
use App\Models\Program;
use App\Models\Batch;
use App\Http\Requests\Admin\ProgramSchemaRequest;

class ProgramSchemaController extends Controller
{
    public function index()
    {
        $schemas = ProgramSchema::with(['program', 'batch'])->orderBy('sort_order')->latest()->paginate(10);
        return view('admin.program_schemas.index', compact('schemas'));
    }

    public function create()
    {
        $programs = Program::where('status', 'aktif')->get();
        $batches = Batch::whereIn('status', ['dibuka', 'diperpanjang', 'akan_dibuka'])->get();
        $schema = new ProgramSchema();
        return view('admin.program_schemas.create', compact('programs', 'batches', 'schema'));
    }

    public function store(ProgramSchemaRequest $request)
    {
        ProgramSchema::create($request->validated());
        
        return redirect()->route('admin.program-schemas.index')->with('success', 'Program Schema berhasil ditambahkan.');
    }

    public function show(ProgramSchema $programSchema)
    {
        return view('admin.program_schemas.show', compact('programSchema'));
    }

    public function edit(ProgramSchema $programSchema)
    {
        $programs = Program::all();
        $batches = Batch::all();
        $schema = $programSchema;
        return view('admin.program_schemas.edit', compact('schema', 'programs', 'batches'));
    }

    public function update(ProgramSchemaRequest $request, ProgramSchema $programSchema)
    {
        $programSchema->update($request->validated());

        return redirect()->route('admin.program-schemas.index')->with('success', 'Program Schema berhasil diperbarui.');
    }

    public function destroy(ProgramSchema $programSchema)
    {
        $programSchema->delete();
        return redirect()->route('admin.program-schemas.index')->with('success', 'Program Schema berhasil dihapus.');
    }
}
