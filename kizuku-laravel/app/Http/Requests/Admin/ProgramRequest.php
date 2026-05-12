<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming admin middleware already handles authorization
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_program'   => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'target_peserta' => 'nullable|string',
            'durasi'         => 'nullable|string',
            'benefit'        => 'nullable|string',
            'alur_seleksi'   => 'nullable|string',
            'biaya'          => 'nullable|string',
            'faq'            => 'nullable|array',
            'focus'          => 'nullable|string',
            'output'         => 'nullable|string',
            'materi'         => 'nullable|string',
            'video_url'      => 'nullable|string',
            'status'         => 'required|in:aktif,nonaktif',
            'is_featured'    => 'nullable',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
