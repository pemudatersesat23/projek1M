<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Http\Requests\Admin\ProgramRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function store(ProgramRequest $request)
    {
        $validated = $request->validated();
        
        $data = $this->prepareProgramData($validated, $request);

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

    public function update(ProgramRequest $request, Program $program)
    {
        $validated = $request->validated();
        
        $data = $this->prepareProgramData($validated, $request, $program);

        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        if ($program->thumbnail_path && Storage::disk('public')->exists($program->thumbnail_path)) {
            Storage::disk('public')->delete($program->thumbnail_path);
        }
        
        $program->delete();
        
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
    
    /**
     * Helper to prepare data for create and update.
     * 
     * @param array $validated
     * @param ProgramRequest $request
     * @param Program|null $program
     * @return array
     */
    private function prepareProgramData(array $validated, ProgramRequest $request, ?Program $program = null): array
    {
        // Set fixed attributes
        $validated['slug'] = Str::slug($validated['nama_program']);
        $validated['is_featured'] = $request->has('is_featured');
        
        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if updating
            if ($program && $program->thumbnail_path && Storage::disk('public')->exists($program->thumbnail_path)) {
                Storage::disk('public')->delete($program->thumbnail_path);
            }
            
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('programs/thumbnails', 'public');
        }
        
        // Remove thumbnail key so it doesn't cause Model fillable error if not needed
        unset($validated['thumbnail']);
        
        return $validated;
    }
}

