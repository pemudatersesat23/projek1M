<?php

namespace App\Services;

use App\Models\FormField;
use Illuminate\Support\Collection;

class DynamicFormService
{
    /**
     * Resolve the active published form for a given program, schema, and batch.
     * Fallback order:
     * 1. program_id + schema_id + batch_id
     * 2. program_id + schema_id + null batch_id
     * 3. program_id + null schema_id + null batch_id
     */
    public function resolveForm(int $programId, ?int $schemaId = null, ?int $batchId = null): ?\App\Models\Form
    {
        $query = \App\Models\Form::published()
            ->where('is_active', true)
            ->where('accepts_responses', true)
            ->where('program_id', $programId);

        // 1. Try exact match (program + schema + batch)
        $form = (clone $query)->where('schema_id', $schemaId)
            ->where('batch_id', $batchId)
            ->first();

        if ($form) return $form;

        // 2. Try program + schema (null batch)
        if ($batchId !== null) {
            $form = (clone $query)->where('schema_id', $schemaId)
                ->whereNull('batch_id')
                ->first();
            if ($form) return $form;
        }

        // 3. Try program only (null schema + null batch)
        if ($schemaId !== null) {
            $form = (clone $query)->whereNull('schema_id')
                ->whereNull('batch_id')
                ->first();
            if ($form) return $form;
        }

        return null;
    }

    /**
     * Get all active fields for a specific Form.
     */
    public function getFieldsForForm(\App\Models\Form $form): Collection
    {
        return $form->fields()->where('status', 'aktif')->orderBy('sort_order')->get();
    }

    /**
     * Resolve and return all active form fields for a given program/schema combo.
     * [LEGACY/COMPAT] This follows the old Task 4 logic but is updated to be aware of forms.
     */
    public function getFieldsFor(int $programId, ?int $schemaId = null): Collection
    {
        $form = $this->resolveForm($programId, $schemaId);
        
        if ($form) {
            return $this->getFieldsForForm($form);
        }

        return collect();
    }

    /**
     * Whether the resolved field set contains any file upload field.
     */
    public function hasFileFields(Collection $fields): bool
    {
        return $fields->some(fn($f) => $f->isFile());
    }
}
