<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Batch extends Model
{
    use HasTranslations, AutoTranslate;
    public $translatable = [
        'nama_batch',
    ];

    protected $fillable = [
        'program_id',
        'nama_batch',
        'status',
        'tanggal_buka',
        'tanggal_tutup',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_estimasi_selesai',
        'kuota',
        'link_form',
        'cta_type',
        'whatsapp_link'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_estimasi_selesai' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
