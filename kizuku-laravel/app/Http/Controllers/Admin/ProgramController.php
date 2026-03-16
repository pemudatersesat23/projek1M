<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Program;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'target_participants' => 'nullable|string',
            'duration' => 'nullable|string',
            'benefits' => 'nullable|string',
            'selection_flow' => 'nullable|string',
            'cost' => 'nullable|string',
            'faq' => 'nullable|array',
            'materi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Program::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dibuat.');
    }

    public function show(Program $program)
    {
        return view('admin.programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'target_participants' => 'nullable|string',
            'duration' => 'nullable|string',
            'benefits' => 'nullable|string',
            'selection_flow' => 'nullable|string',
            'cost' => 'nullable|string',
            'faq' => 'nullable|array',
            'materi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $program->update($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
