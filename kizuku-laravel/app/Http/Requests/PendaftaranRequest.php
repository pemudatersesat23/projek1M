<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'jurusan_ipk' => 'nullable|string|max:255',
            'pengalaman' => 'nullable|string',
            'pengalaman_kerja' => 'nullable|string',
            'motivasi' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'batch_id' => 'required|exists:batches,id',
            // File Validation
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
