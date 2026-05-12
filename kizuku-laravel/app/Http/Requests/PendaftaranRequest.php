<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pendidikan' => 'required|string|max:255',
            'pengalaman_kerja' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'batch_id' => 'required|exists:batches,id',
            'schema_id' => [
                'nullable',
                Rule::exists('program_schemas', 'id')->where(function ($query) {
                    return $query
                        ->where('program_id', $this->program_id)
                        ->where('status', 'aktif')
                        ->whereNull('deleted_at');
                })
            ],
            
            // Dynamic Fields (Stored in additional_data JSON column)
            'additional_data' => 'nullable|array',

            // File Validation
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cv' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transkrip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bukti_sosmed' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
