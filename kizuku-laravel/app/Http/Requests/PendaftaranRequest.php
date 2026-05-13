<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\DynamicFormService;

class PendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ── System fields ──────────────────────────────────────────────
            'program_id' => ['required', Rule::exists('programs', 'id')->where('status', 'aktif')],

            'batch_id' => [
                'required',
                Rule::exists('batches', 'id')->where(function ($q) {
                    return $q->where('program_id', $this->input('program_id'))
                             ->whereIn('status', ['dibuka', 'diperpanjang']);
                }),
            ],

            'schema_id' => [
                'nullable',
                Rule::exists('program_schemas', 'id')->where(function ($q) {
                    return $q->where('program_id', $this->input('program_id'))
                             ->where('status', 'aktif')
                             ->whereNull('deleted_at');
                }),
            ],

            'form_id' => [
                'required',
                Rule::exists('forms', 'id')->where(function ($q) {
                    return $q->where('program_id', $this->input('program_id'))
                             ->where('status', 'published')
                             ->where('is_active', true)
                             ->where('accepts_responses', true)
                             ->whereNull('deleted_at');
                }),
            ],

            // ── Dynamic Form Builder payload (structure only) ────────────
            // Detailed field-level validation is handled by DynamicValidationService
            'dynamic_answers'   => 'nullable|array',
            'dynamic_files'     => 'nullable|array',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach (['program_id', 'batch_id', 'schema_id', 'form_id'] as $key) {
                if ($validator->errors()->has($key)) {
                    return;
                }
            }

            if (! $this->filled('program_id') || ! $this->filled('batch_id') || ! $this->filled('form_id')) {
                return;
            }

            $resolved = app(DynamicFormService::class)->resolveForm(
                (int) $this->input('program_id'),
                $this->filled('schema_id') ? (int) $this->input('schema_id') : null,
                $this->filled('batch_id') ? (int) $this->input('batch_id') : null,
            );

            if (! $resolved || (int) $resolved->id !== (int) $this->input('form_id')) {
                $validator->errors()->add('form_id', 'Formulir pendaftaran tidak sesuai dengan program, batch, atau skema yang dipilih.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'batch_id.exists'   => 'Gelombang pendaftaran tidak tersedia atau sudah ditutup.',
            'program_id.exists' => 'Program tidak ditemukan atau sudah tidak aktif.',
            'schema_id.exists'  => 'Skema tidak valid, tidak aktif, atau tidak sesuai program.',
        ];
    }
}
