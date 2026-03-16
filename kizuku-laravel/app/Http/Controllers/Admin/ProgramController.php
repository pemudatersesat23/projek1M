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
        $programs = Program::latest()->paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_peserta' => 'nullable|string',
            'durasi' => 'nullable|string',
            'benefit' => 'nullable|string',
            'alur_seleksi' => 'nullable|string',
            'biaya' => 'nullable|string',
            'faq' => 'nullable|array',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama_program);

        Program::create($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan.');
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
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_peserta' => 'nullable|string',
            'durasi' => 'nullable|string',
            'benefit' => 'nullable|string',
            'alur_seleksi' => 'nullable|string',
            'biaya' => 'nullable|string',
            'faq' => 'nullable|array',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->nama_program);

        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
