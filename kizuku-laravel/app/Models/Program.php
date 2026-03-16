<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'nama_program',
        'slug',
        'deskripsi',
        'target_peserta',
        'durasi',
        'benefit',
        'alur_seleksi',
        'biaya',
        'faq',
        'materi',
        'brosur',
        'thumbnail_path',
        'status'
    ];

    protected $casts = [
        'faq' => 'array',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
