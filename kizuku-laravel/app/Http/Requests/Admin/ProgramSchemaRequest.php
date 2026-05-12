<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramSchemaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('POST')) {
            if (empty($this->slug) && !empty($this->nama_skema)) {
                $this->merge([
                    'slug' => Str::slug($this->nama_skema)
                ]);
            } else if (!empty($this->slug)) {
                $this->merge([
                    'slug' => Str::slug($this->slug)
                ]);
            }
        } else {
            if (!empty($this->slug)) {
                $this->merge([
                    'slug' => Str::slug($this->slug)
                ]);
            } else {
                $this->request->remove('slug');
            }
        }
    }

    public function rules()
    {
        $schemaId = $this->route('program_schema') ? $this->route('program_schema')->id : null;

        return [
            'program_id' => 'required|exists:programs,id',
            'batch_id' => [
                'nullable',
                Rule::exists('batches', 'id')->where(function ($query) {
                    return $query->where('program_id', $this->program_id);
                })
            ],
            'nama_skema' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('program_schemas', 'slug')
                    ->where('program_id', $this->program_id)
                    ->ignore($schemaId)
            ],
            'tipe' => 'required|in:beasiswa,scholar_partnership,reguler',
            'deskripsi' => 'nullable|string',
            'persyaratan' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
            'sort_order' => 'nullable|integer',
        ];
    }
}
