<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiswaRequest extends FormRequest
{
    // Legacy request retained for the old Siswa flow. New registration data uses Applicant.
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'           => 'required|string|max:255',
            'wa'             => 'required|string|max:50',
            'email'          => 'nullable|email|max:255',
            'kota'           => 'required|string|max:255',
            'program'        => 'required|string|max:255',
            'status'         => 'nullable|string|max:50',
            'pendidikan'     => 'nullable|string|max:255',
            'catatan'        => 'nullable|string',
            'tgl_lahir'      => 'nullable|date',
            'payment_status' => 'nullable|string|max:50',
        ];
    }
}
