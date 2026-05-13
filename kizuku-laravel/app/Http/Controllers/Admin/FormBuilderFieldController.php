<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Http\Requests\Admin\FormFieldRequest;
use Illuminate\Http\Request;

class FormBuilderFieldController extends Controller
{
    public function store(FormFieldRequest $request, Form $form)
    {
        $validated = $request->validated();
        
        $maxOrder = FormField::where('form_id', $form->id)->max('sort_order') ?? 0;
        
        $field = FormField::create([
            'form_id' => $form->id,
            'program_id' => $form->program_id,
            'schema_id' => $form->schema_id,
            'label' => [
                'id' => $validated['label_id'],
                'jp' => $validated['label_jp'] ?? null,
            ],
            'field_name' => $validated['field_name'],
            'type' => $validated['type'],
            'field_role' => $validated['field_role'] ?? 'none',
            'placeholder' => [
                'id' => $validated['placeholder_id'] ?? null,
                'jp' => $validated['placeholder_jp'] ?? null,
            ],
            'description' => [
                'id' => $validated['description_id'] ?? null,
                'jp' => $validated['description_jp'] ?? null,
            ],
            'options' => isset($validated['options']) ? json_decode($validated['options'], true) : null,
            'accepted_file_types' => isset($validated['accepted_file_types']) ? json_decode($validated['accepted_file_types'], true) : null,
            'max_file_size' => $validated['max_file_size'] ?? null,
            'is_required' => $validated['is_required'] ?? false,
            'status' => $validated['status'] ?? 'aktif',
            'sort_order' => $maxOrder + 1,
        ]);

        return response()->json([
            'message' => 'Pertanyaan berhasil ditambahkan',
            'field' => $field
        ]);
    }

    public function update(FormFieldRequest $request, Form $form, FormField $field)
    {
        if ($field->form_id !== $form->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        $field->setTranslation('label', 'id', $validated['label_id']);
        if ($request->has('label_jp')) $field->setTranslation('label', 'jp', $validated['label_jp']);
        
        $field->type = $validated['type'];
        $field->field_role = $validated['field_role'] ?? 'none';
        
        if ($request->has('placeholder_id')) $field->setTranslation('placeholder', 'id', $validated['placeholder_id']);
        if ($request->has('placeholder_jp')) $field->setTranslation('placeholder', 'jp', $validated['placeholder_jp']);
        
        if ($request->has('description_id')) $field->setTranslation('description', 'id', $validated['description_id']);
        if ($request->has('description_jp')) $field->setTranslation('description', 'jp', $validated['description_jp']);

        if ($request->has('options')) $field->options = json_decode($validated['options'], true);
        if ($request->has('accepted_file_types')) $field->accepted_file_types = json_decode($validated['accepted_file_types'], true);
        if ($request->has('max_file_size')) $field->max_file_size = $validated['max_file_size'];
        
        $field->is_required = $validated['is_required'] ?? false;
        $field->status = $validated['status'] ?? 'aktif';

        if (!$field->is_locked && $request->has('field_name')) {
            $field->field_name = $validated['field_name'];
        }

        $field->save();

        return response()->json([
            'message' => 'Pertanyaan berhasil diperbarui',
            'field' => $field
        ]);
    }

    public function duplicate(Form $form, FormField $field)
    {
        if ($field->form_id !== $form->id) {
            abort(403, 'Unauthorized action.');
        }

        $newName = $field->field_name . '_copy';
        $counter = 2;
        while (FormField::withTrashed()->where('form_id', $form->id)->where('field_name', $newName)->exists()) {
            $newName = $field->field_name . '_copy_' . $counter;
            $counter++;
        }

        $label = $field->getTranslations('label');
        $label['id'] = ($label['id'] ?? '') . ' (Copy)';

        $newField = $field->replicate();
        $newField->field_name = $newName;
        $newField->label = $label;
        $newField->field_role = 'none'; // reset role to prevent duplicates
        $newField->is_locked = false;
        $newField->sort_order = $field->sort_order + 1;
        $newField->push();

        // Increment sort_order of all subsequent fields
        FormField::where('form_id', $form->id)
            ->where('sort_order', '>=', $newField->sort_order)
            ->where('id', '!=', $newField->id)
            ->increment('sort_order');

        return response()->json([
            'message' => 'Pertanyaan berhasil diduplikasi',
            'field' => $newField
        ]);
    }

    public function destroy(Form $form, FormField $field)
    {
        if ($field->form_id !== $form->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($field->is_locked) {
            return response()->json(['message' => 'Gagal menghapus: Pertanyaan ini dikunci oleh sistem.'], 403);
        }

        $field->delete(); // Soft delete

        return response()->json(['message' => 'Pertanyaan berhasil dihapus']);
    }

    public function reorder(Request $request, Form $form)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:form_fields,id']
        ]);

        $order = $request->input('order');
        foreach ($order as $index => $fieldId) {
            FormField::where('form_id', $form->id)->where('id', $fieldId)->update([
                'sort_order' => $index + 1
            ]);
        }

        return response()->json(['message' => 'Urutan berhasil diperbarui']);
    }
}
