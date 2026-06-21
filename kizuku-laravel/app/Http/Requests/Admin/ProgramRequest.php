<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\ProgramSection;

class ProgramRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('POST')) {
            // Generate slug from nama_program if slug is empty (usually during create)
            if (empty($this->slug) && !empty($this->nama_program)) {
                $this->merge([
                    'slug' => Str::slug($this->nama_program)
                ]);
            } else if (!empty($this->slug)) {
                $this->merge([
                    'slug' => Str::slug($this->slug)
                ]);
            }
        } else {
            // During update, only process slug if explicitly provided
            if (!empty($this->slug)) {
                $this->merge([
                    'slug' => Str::slug($this->slug)
                ]);
            } else {
                $this->request->remove('slug');
            }
        }

        $this->merge([
            'is_featured' => $this->has('is_featured') ? true : false,
            'has_schema' => $this->has('has_schema') ? true : false,
        ]);
    }

    public function rules()
    {
        $programId = $this->route('program') ? $this->route('program')->id : null;

        return [
            'nama_program' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:programs,slug,' . $programId,
            'deskripsi' => 'nullable|string',
            'focus' => 'nullable|string',
            'output' => 'nullable|string',
            'target_peserta' => 'nullable|string',
            'durasi' => 'nullable|string',
            'benefit' => 'nullable|string',
            'alur_seleksi' => 'nullable|string',
            'biaya' => 'nullable|string',
            'materi' => 'nullable|string',
            'brosur' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url',
            'status' => 'required|in:aktif,nonaktif',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'has_schema' => 'boolean',
            'sections' => 'nullable|array',
            'sections.*.type' => ['nullable', Rule::in(array_keys(ProgramSection::TYPES))],
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.description' => 'nullable|string',
            'sections.*.sort_order' => 'nullable|integer',
            'sections.*.is_active' => 'nullable|boolean',
            'sections.*.items' => 'nullable|array',
            'sections.*.items.*.title' => 'nullable|string|max:255',
            'sections.*.items.*.description' => 'nullable|string',
            'sections.*.items.*.icon' => 'nullable|string|max:80',
        ];
    }
}
