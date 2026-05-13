<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Program;
use App\Models\ProgramSchema;
use App\Models\Batch;
use App\Models\FormField;
use App\Http\Requests\Admin\FormRequest;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $query = Form::with(['program', 'schema', 'batch'])->withCount('fields');
        
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
        if ($request->filled('schema_id')) {
            $query->where('schema_id', $request->schema_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $forms = $query->orderBy('created_at', 'desc')->paginate(20);
        $programs = Program::active()->get();

        return view('admin.forms.index', compact('forms', 'programs'));
    }

    public function create()
    {
        $programs = Program::active()->get();
        return view('admin.forms.create', compact('programs'));
    }

    public function store(FormRequest $request)
    {
        $validated = $request->validated();
        
        $form = Form::create([
            'program_id' => $validated['program_id'],
            'schema_id' => $validated['schema_id'] ?? null,
            'batch_id' => $validated['batch_id'] ?? null,
            'title' => [
                'id' => $validated['title_id'],
                'jp' => $validated['title_jp'] ?? null,
            ],
            'description' => [
                'id' => $validated['description_id'] ?? null,
                'jp' => $validated['description_jp'] ?? null,
            ],
            'success_message' => [
                'id' => $validated['success_message_id'] ?? null,
                'jp' => $validated['success_message_jp'] ?? null,
            ],
            'status' => 'draft',
            'is_active' => true,
            'accepts_responses' => false,
            'version' => 1,
        ]);

        return redirect()->route('admin.forms.builder', $form->id)->with('success', 'Form berhasil dibuat, silakan tambahkan pertanyaan.');
    }

    public function builder(Form $form)
    {
        $form->load(['program', 'schema', 'batch', 'fields' => function($q) {
            $q->orderBy('sort_order', 'asc');
        }]);

        return view('admin.forms.builder', compact('form'));
    }

    public function update(FormRequest $request, Form $form)
    {
        $validated = $request->validated();
        
        $form->update([
            'program_id' => $validated['program_id'],
            'schema_id' => $validated['schema_id'] ?? null,
            'batch_id' => $validated['batch_id'] ?? null,
            'title' => [
                'id' => $validated['title_id'],
                'jp' => $validated['title_jp'] ?? null,
            ],
            'description' => [
                'id' => $validated['description_id'] ?? null,
                'jp' => $validated['description_jp'] ?? null,
            ],
            'success_message' => [
                'id' => $validated['success_message_id'] ?? null,
                'jp' => $validated['success_message_jp'] ?? null,
            ],
        ]);

        return response()->json(['message' => 'Metadata form berhasil disimpan']);
    }

    public function preview(Form $form)
    {
        $form->load(['program', 'schema', 'batch', 'fields' => function($q) {
            $q->orderBy('sort_order', 'asc');
        }]);

        return view('admin.forms.preview', compact('form'));
    }

    public function publish(Form $form)
    {
        // Publish validation
        $fields = $form->fields;
        $activeFields = $fields->where('status', 'aktif');

        if ($activeFields->isEmpty()) {
            return response()->json(['message' => 'Gagal publish: Form harus memiliki minimal 1 field aktif.'], 422);
        }

        $hasName = $activeFields->where('field_role', 'applicant_name')->count();
        if ($hasName !== 1) {
            return response()->json(['message' => 'Gagal publish: Form harus memiliki tepat satu field dengan role "applicant_name".'], 422);
        }

        $hasContact = $activeFields->whereIn('field_role', ['applicant_email', 'applicant_phone'])->isNotEmpty();
        if (!$hasContact) {
            return response()->json(['message' => 'Gagal publish: Form harus meminta Email atau Nomor WhatsApp.'], 422);
        }

        $uniqueNames = $activeFields->pluck('field_name')->unique()->count() === $activeFields->count();
        if (!$uniqueNames) {
            return response()->json(['message' => 'Gagal publish: Terdapat duplikasi Field Name (nama variabel).'], 422);
        }
        
        // Prevent multiple active published forms for same program+schema+batch
        $exists = Form::where('program_id', $form->program_id)
            ->where('schema_id', $form->schema_id)
            ->where('batch_id', $form->batch_id)
            ->where('id', '!=', $form->id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->exists();
            
        if ($exists) {
            return response()->json(['message' => 'Gagal publish: Sudah ada form yang dipublish dan aktif untuk skema/batch ini.'], 422);
        }

        // Choice options validation
        $choiceTypes = config('dynamic_forms.choice_field_types', ['select', 'radio', 'checkbox']);
        foreach ($activeFields->whereIn('type', $choiceTypes) as $f) {
            if (empty($f->options) || !is_array($f->options)) {
                return response()->json(['message' => "Gagal publish: Pertanyaan '{$f->field_name}' tidak memiliki opsi pilihan yang valid."], 422);
            }
        }

        // Increment version if it was ever published before? Just publish for now.
        $form->update([
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'published_at' => now()
        ]);

        return response()->json(['message' => 'Form berhasil dipublish!', 'status' => 'published']);
    }

    public function draft(Form $form)
    {
        $form->update([
            'status' => 'draft',
            'is_active' => false,
            'accepts_responses' => false,
        ]);

        return response()->json(['message' => 'Form dikembalikan ke draft.', 'status' => 'draft']);
    }

    public function archive(Form $form)
    {
        $form->update([
            'status' => 'archived',
            'is_active' => false,
            'accepts_responses' => false,
        ]);

        return response()->json(['message' => 'Form berhasil diarsipkan.', 'status' => 'archived']);
    }
}
