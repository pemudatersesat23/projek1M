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
            // ── Fixed core fields ──────────────────────────────────────────
            'nama'            => 'required|string|max:255',
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'    => 'required|string|max:255',
            'tanggal_lahir'   => 'required|date',
            'alamat'          => 'required|string',
            'phone'           => 'required|string|max:20',
            'email'           => 'required|email|max:255',
            'pendidikan'      => 'required|string|max:255',
            'pengalaman_kerja'=> 'nullable|string',

            // ── Program / Batch / Schema ownership ────────────────────────
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

            // ── Legacy JSON dynamic (kept for backward compat) ───────────
            'additional_data' => 'nullable|array',

            // ── Dynamic Form Builder payload (structure only) ────────────
            // Detailed field-level validation is handled by DynamicValidationService
            'dynamic_answers'   => 'nullable|array',
            'dynamic_files'     => 'nullable|array',

            // ── Fixed document uploads ────────────────────────────────────
            'ktp'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kk'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'ijazah'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'sertifikat'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cv'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transkrip'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bukti_sosmed'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
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
