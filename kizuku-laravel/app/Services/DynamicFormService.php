<?php

namespace App\Services;

use App\Models\FormField;
use Illuminate\Support\Collection;

class DynamicFormService
{
    /**
     * Resolve and return all active form fields for a given program/schema combo.
     * Field umum (schema_id null) are always included.
     * If a schema is selected, schema-specific fields are also merged in.
     * The combined list is sorted by sort_order then id.
     */
    public function getFieldsFor(int $programId, ?int $schemaId = null): Collection
    {
        // 1. Field umum program (schema_id null)
        $generalFields = FormField::active()
            ->forProgram($programId)
            ->ordered()
            ->get();

        // 2. Field khusus schema (if schema selected)
        $schemaFields = collect();
        if ($schemaId) {
            $schemaFields = FormField::active()
                ->forSchema($schemaId)
                ->where('program_id', $programId)
                ->ordered()
                ->get();
        }

        // 3. Merge dan urutkan berdasarkan sort_order kemudian id
        return $generalFields
            ->concat($schemaFields)
            ->sortBy([['sort_order', 'asc'], ['id', 'asc']])
            ->values();
    }

    /**
     * Whether the resolved field set contains any file upload field.
     */
    public function hasFileFields(Collection $fields): bool
    {
        return $fields->some(fn($f) => $f->isFile());
    }
}
