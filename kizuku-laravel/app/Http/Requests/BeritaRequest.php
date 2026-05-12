<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'          => 'required|string|max:255',
            'status_publish' => 'required|in:draft,published',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'lokasi'         => 'nullable|string|max:255',
            'kategori'       => 'nullable|string|max:100',
            'isi'            => 'nullable|string',
        ];
    }
}
