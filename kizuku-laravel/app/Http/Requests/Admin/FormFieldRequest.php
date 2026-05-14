<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $formFieldId = $this->route('form_field')?->id ?? $this->route('field')?->id;
        $programId   = $this->input('program_id');
        $schemaId    = $this->input('schema_id') ?: null;
        $type        = $this->input('type');
        $choiceTypes = config('dynamic_forms.choice_field_types', ['select', 'radio', 'checkbox']);
        $blocked     = config('dynamic_forms.blocked_file_extensions', []);
        $formId      = $this->route('form')?->id ?? $this->input('form_id');

        $rules = [
            'form_id'    => ['nullable', 'exists:forms,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'schema_id'  => [
                'nullable',
                Rule::exists('program_schemas', 'id')->where(function ($q) use ($programId) {
                    return $q->where('program_id', $programId)
                             ->where('status', 'aktif')
                             ->whereNull('deleted_at');
                }),
            ],

            // Multilingual label split fields
            'label_id' => ['required', 'string', 'max:255'],
            'label_jp' => ['nullable', 'string', 'max:255'],
            'placeholder_id' => ['nullable', 'string', 'max:255'],
            'placeholder_jp' => ['nullable', 'string', 'max:255'],
            'description_id' => ['nullable', 'string', 'max:500'],
            'description_jp' => ['nullable', 'string', 'max:500'],

            'field_name' => [
                'required',
                'string',
                'max:64',
                'regex:/^[a-z][a-z0-9_]*$/',
                function ($attribute, $value, $fail) use ($formFieldId, $programId, $schemaId, $formId) {
                    if ($formId) {
                        // Task 5C logic: Check uniqueness within the exact same form_id
                        $existsInForm = \App\Models\FormField::withTrashed()
                            ->where('form_id', $formId)
                            ->where('field_name', $value)
                            ->when($formFieldId, fn($q) => $q->where('id', '!=', $formFieldId))
                            ->exists();

                        if ($existsInForm) {
                            $fail('Field name "' . $value . '" sudah digunakan di dalam form ini.');
                        }
                    } else {
                        // Legacy logic (Task 4C backward compatibility)
                        $existsInScope = \App\Models\FormField::withTrashed()
                            ->where('program_id', $programId)
                            ->where('field_name', $value)
                            ->when($schemaId, fn($q) => $q->where('schema_id', $schemaId),
                                              fn($q) => $q->whereNull('schema_id'))
                            ->when($formFieldId, fn($q) => $q->where('id', '!=', $formFieldId))
                            ->exists();

                        if ($existsInScope) {
                            $fail('Field name "' . $value . '" sudah digunakan dalam lingkup program dan skema yang sama.');
                            return;
                        }

                        if ($schemaId) {
                            $existsInProgram = \App\Models\FormField::withTrashed()
                                ->where('program_id', $programId)
                                ->where('field_name', $value)
                                ->whereNull('schema_id')
                                ->when($formFieldId, fn($q) => $q->where('id', '!=', $formFieldId))
                                ->exists();

                            if ($existsInProgram) {
                                $fail('Field name "' . $value . '" sudah digunakan sebagai field umum program. Tidak boleh bentrok.');
                            }
                        }
                    }
                },
            ],
            'field_role' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($formFieldId, $formId) {
                    if ($value && $value !== 'none' && $formId) {
                        $exists = \App\Models\FormField::where('form_id', $formId)
                            ->where('field_role', $value)
                            ->when($formFieldId, fn($q) => $q->where('id', '!=', $formFieldId))
                            ->exists();
                        if ($exists) {
                            $fail("Role field '{$value}' hanya boleh digunakan satu kali dalam form ini.");
                        }
                    }
                }
            ],

            'type'       => ['required', Rule::in(config('dynamic_forms.allowed_field_types'))],
            'is_required'=> ['boolean'],
            'status'     => ['required', Rule::in(['aktif', 'nonaktif'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'max_file_size' => ['nullable', 'integer', 'min:1'],
            'settings' => ['nullable', 'array'],
            'settings.section_icon' => ['nullable', 'string', 'max:50'],
            'settings.section_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ];

        // Type-specific rules
        if (in_array($type, $choiceTypes)) {
            $rules['options'] = [
                'required',
                'json',
                function ($attribute, $value, $fail) {
                    $parsed = json_decode($value, true);
                    if (!is_array($parsed) || empty($parsed)) {
                        $fail('Options harus berupa array JSON yang valid dan tidak kosong.');
                        return;
                    }
                    $values = [];
                    foreach ($parsed as $i => $option) {
                        if (empty($option['value'])) {
                            $fail("Option ke-" . ($i + 1) . " harus memiliki 'value'.");
                            return;
                        }
                        if (empty($option['label'])) {
                            $fail("Option ke-" . ($i + 1) . " harus memiliki 'label'.");
                            return;
                        }
                        if (in_array($option['value'], $values)) {
                            $fail("Option value '{$option['value']}' duplikat.");
                            return;
                        }
                        $values[] = $option['value'];
                    }
                },
            ];
            $rules['accepted_file_types'] = ['nullable'];
        } elseif ($type === 'file') {
            $rules['accepted_file_types'] = [
                'nullable',
                'json',
                function ($attribute, $value, $fail) use ($blocked) {
                    if (empty($value)) return;
                    $parsed = json_decode($value, true);
                    if (!is_array($parsed)) { $fail('accepted_file_types harus JSON array.'); return; }
                    foreach ($parsed as $ext) {
                        if (in_array(strtolower($ext), $blocked)) {
                            $fail("Ekstensi '{$ext}' tidak diizinkan untuk alasan keamanan.");
                        }
                    }
                },
            ];
            $rules['options'] = ['nullable'];
        } else {
            $rules['options']             = ['nullable'];
            $rules['accepted_file_types'] = ['nullable'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'label_id'    => 'Label (Indonesia)',
            'label_jp'    => 'Label (Jepang)',
            'field_name'  => 'Field Name',
            'program_id'  => 'Program',
            'schema_id'   => 'Schema',
            'options'     => 'Options',
            'type'        => 'Tipe Field',
        ];
    }
}
