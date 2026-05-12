<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'program_id' => 'required|exists:programs,id',
            'nama_batch' => 'required|string|max:255',
            'tanggal_buka' => 'nullable|date',
            'tanggal_tutup' => 'nullable|date|after_or_equal:tanggal_buka',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tanggal_estimasi_selesai' => 'nullable|date',
            'kuota' => 'nullable|integer|min:0',
            'status' => 'required|in:dibuka,diperpanjang,akan_dibuka,ditutup,berjalan,selesai',
            'cta_type' => 'nullable|in:internal_form,whatsapp,disabled',
            'whatsapp_link' => 'nullable|url',
            'link_form' => 'nullable|url',
        ];
    }
}
