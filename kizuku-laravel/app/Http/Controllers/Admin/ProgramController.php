<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramSection;
use App\Http\Requests\Admin\ProgramRequest;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::withCount(['batches', 'programSchemas'])
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);
            
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(ProgramRequest $request)
    {
        $validated = $request->validated();
        $sections = $validated['sections'] ?? [];
        
        $data = $this->prepareProgramData($validated, $request);

        $program = Program::create($data);
        $this->syncSections($program, $sections);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function show(Program $program)
    {
        return view('admin.programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $program->load('sections');

        return view('admin.programs.edit', compact('program'));
    }

    public function update(ProgramRequest $request, Program $program)
    {
        $validated = $request->validated();
        $sections = $validated['sections'] ?? [];
        
        $data = $this->prepareProgramData($validated, $request, $program);

        $program->update($data);
        $this->syncSections($program, $sections);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        // Program uses SoftDeletes, so we keep the files intact.
        $program->delete();
        
        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }
    
    /**
     * Helper to prepare data for create and update.
     */
    private function prepareProgramData(array $validated, ProgramRequest $request, ?Program $program = null): array
    {
        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            if ($program && $program->thumbnail_path && Storage::disk('public')->exists($program->thumbnail_path)) {
                Storage::disk('public')->delete($program->thumbnail_path);
            }
            $validated['thumbnail_path'] = $request->file('thumbnail')->store('programs/thumbnails', 'public');
        }
        
        // Handle Brosur Upload
        if ($request->hasFile('brosur')) {
            if ($program && $program->brosur && Storage::disk('public')->exists($program->brosur)) {
                Storage::disk('public')->delete($program->brosur);
            }
            $validated['brosur'] = $request->file('brosur')->store('programs/brosur', 'public');
        }
        
        // Clean up uploaded files array keys
        unset($validated['thumbnail']);
        unset($validated['sections']);
        
        return $validated;
    }

    private function syncSections(Program $program, array $sections): void
    {
        // Gunakan forceDelete() bukan delete() karena sections adalah konten yang
        // sepenuhnya dikelola via syncSections — tidak perlu soft-delete audit trail.
        // Dengan soft delete, setiap edit program mengakumulasi baris "ghost" di DB.
        $program->sections()->withTrashed()->forceDelete();

        foreach ($sections as $index => $section) {
            $type = $section['type'] ?? 'text';

            if (!array_key_exists($type, ProgramSection::TYPES)) {
                continue;
            }

            $items = $this->cleanSectionItems($section['items'] ?? []);
            $title = trim((string) ($section['title'] ?? ''));
            $description = trim((string) ($section['description'] ?? ''));

            if ($title === '' && $description === '' && empty($items)) {
                continue;
            }

            $program->sections()->create([
                'type'        => $type,
                'title'       => $title === '' ? [] : ['id' => $title],
                'description' => $description === '' ? [] : ['id' => $description],
                'items'       => ['id' => $items],
                'settings'    => [],
                'sort_order'  => (int) ($section['sort_order'] ?? $index),
                'is_active'   => filter_var($section['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    private function cleanSectionItems(array $items): array
    {
        return collect($items)
            ->map(function ($item) {
                return [
                    'title' => trim((string) ($item['title'] ?? '')),
                    'description' => trim((string) ($item['description'] ?? '')),
                    'icon' => trim((string) ($item['icon'] ?? '')),
                ];
            })
            ->filter(fn ($item) => $item['title'] !== '' || $item['description'] !== '')
            ->values()
            ->all();
    }
}
