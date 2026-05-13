<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FormFieldRequest;
use App\Models\FormField;
use App\Models\Program;
use App\Models\ProgramSchema;

class FormFieldController extends Controller
{
    public function index()
    {
        $query = FormField::with(['program', 'schema'])->ordered();

        // Filter program
        if ($programId = request('program_id')) {
            $query->where('program_id', $programId);
        }

        // Filter schema
        if (request()->has('schema_id')) {
            $schemaId = request('schema_id');
            if ($schemaId === 'umum') {
                $query->whereNull('schema_id');
            } elseif ($schemaId) {
                $query->where('schema_id', $schemaId);
            }
        }

        // Filter type
        if ($type = request('type')) {
            $query->where('type', $type);
        }

        // Filter status
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $formFields = $query->paginate(25)->withQueryString();
        $programs   = Program::active()->ordered()->get();

        return view('admin.form_fields.index', compact('formFields', 'programs'));
    }

    public function create()
    {
        $formField = new FormField();
        $programs  = Program::active()->ordered()->get();
        $schemas   = collect();

        // If program filter in URL, pre-load its schemas
        if ($programId = request('program_id')) {
            $schemas = ProgramSchema::where('program_id', $programId)->active()->ordered()->get();
        }

        return view('admin.form_fields.create', compact('formField', 'programs', 'schemas'));
    }

    public function store(FormFieldRequest $request)
    {
        $data = $request->validated();

        if (isset($data['options']) && is_string($data['options'])) {
            $data['options'] = json_decode($data['options'], true);
        }
        if (isset($data['accepted_file_types']) && is_string($data['accepted_file_types'])) {
            $data['accepted_file_types'] = json_decode($data['accepted_file_types'], true);
        }

        // Normalise: clear options/file fields for irrelevant types
        if (!in_array($data['type'], config('dynamic_forms.choice_field_types'))) {
            $data['options'] = null;
        }
        if ($data['type'] !== config('dynamic_forms.file_field_type')) {
            $data['accepted_file_types'] = null;
            $data['max_file_size']       = null;
        }

        // Wrap label/placeholder/description as translatable JSON
        $data['label']       = ['id' => $data['label_id'] ?? '', 'jp' => $data['label_jp'] ?? ''];
        $data['placeholder'] = ['id' => $data['placeholder_id'] ?? '', 'jp' => $data['placeholder_jp'] ?? ''];
        $data['description'] = ['id' => $data['description_id'] ?? '', 'jp' => $data['description_jp'] ?? ''];

        // Strip pseudo fields
        unset($data['label_id'], $data['label_jp'], $data['placeholder_id'], $data['placeholder_jp'],
              $data['description_id'], $data['description_jp']);

        FormField::create($data);

        return redirect()->route('admin.form-fields.index')
            ->with('success', 'Field berhasil dibuat.');
    }

    public function edit(FormField $formField)
    {
        $programs = Program::active()->ordered()->get();
        $schemas  = ProgramSchema::where('program_id', $formField->program_id)->active()->ordered()->get();

        return view('admin.form_fields.edit', compact('formField', 'programs', 'schemas'));
    }

    public function update(FormFieldRequest $request, FormField $formField)
    {
        $data = $request->validated();

        if (isset($data['options']) && is_string($data['options'])) {
            $data['options'] = json_decode($data['options'], true);
        }
        if (isset($data['accepted_file_types']) && is_string($data['accepted_file_types'])) {
            $data['accepted_file_types'] = json_decode($data['accepted_file_types'], true);
        }

        // field_name is immutable — prevent HTTP manipulation even if readonly in UI
        unset($data['field_name']);

        if (!in_array($data['type'], config('dynamic_forms.choice_field_types'))) {
            $data['options'] = null;
        }
        if ($data['type'] !== config('dynamic_forms.file_field_type')) {
            $data['accepted_file_types'] = null;
            $data['max_file_size']       = null;
        }

        $data['label']       = ['id' => $data['label_id'] ?? '', 'jp' => $data['label_jp'] ?? ''];
        $data['placeholder'] = ['id' => $data['placeholder_id'] ?? '', 'jp' => $data['placeholder_jp'] ?? ''];
        $data['description'] = ['id' => $data['description_id'] ?? '', 'jp' => $data['description_jp'] ?? ''];

        unset($data['label_id'], $data['label_jp'], $data['placeholder_id'], $data['placeholder_jp'],
              $data['description_id'], $data['description_jp']);

        $formField->update($data);

        return redirect()->route('admin.form-fields.index')
            ->with('success', 'Field berhasil diperbarui.');
    }

    public function destroy(FormField $formField)
    {
        $formField->delete(); // SoftDelete only
        return redirect()->route('admin.form-fields.index')
            ->with('success', 'Field berhasil dinonaktifkan (soft delete).');
    }

    /**
     * AJAX: Return schemas for a given program_id.
     */
    public function schemasForProgram()
    {
        $programId = request('program_id');
        $schemas   = ProgramSchema::where('program_id', $programId)
            ->where('status', 'aktif')
            ->whereNull('deleted_at')
            ->orderBy('sort_order')
            ->get(['id', 'nama_skema']);

        return response()->json($schemas);
    }
}
