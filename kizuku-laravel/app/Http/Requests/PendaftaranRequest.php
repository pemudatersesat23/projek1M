<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                             ->whereNull('deleted_at');
                }),
            ],

            // ── Dynamic Form Builder payload (structure only) ────────────
            // Detailed field-level validation is handled by DynamicValidationService
            'dynamic_answers'   => 'nullable|array',
            'dynamic_files'     => 'nullable|array',
        ];
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
