<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $programId = $this->input('program_id') ?? $this->route('form')?->program_id;

        return [
            'program_id' => ['required', 'exists:programs,id'],
            'schema_id' => [
                'nullable',
                Rule::exists('program_schemas', 'id')->where(function ($q) use ($programId) {
                    return $q->where('program_id', $programId);
                }),
            ],
            'batch_id' => [
                'nullable',
                Rule::exists('batches', 'id')->where(function ($q) use ($programId) {
                    return $q->where('program_id', $programId);
                }),
            ],
            
            // JSON Multilingual fields
            'title_id' => ['required', 'string', 'max:255'],
            'title_jp' => ['nullable', 'string', 'max:255'],
            
            'description_id' => ['nullable', 'string'],
            'description_jp' => ['nullable', 'string'],
            
            'success_message_id' => ['nullable', 'string'],
            'success_message_jp' => ['nullable', 'string'],
            
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'is_active' => ['nullable', 'boolean'],
            'accepts_responses' => ['nullable', 'boolean'],
        ];
    }
}
