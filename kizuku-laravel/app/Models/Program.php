<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Program extends Model
{
    use HasTranslations;

    protected $fillable = [
        'nama_program',
        'slug',
        'deskripsi',
        'focus',
        'output',
        'target_peserta',
        'durasi',
        'benefit',
        'alur_seleksi',
        'biaya',
        'faq',
        'materi',
        'brosur',
        'thumbnail_path',
        'video_url',
        'status',
        'is_featured'
    ];

    public $translatable = [
        'nama_program',
        'deskripsi',
        'focus',
        'output',
        'target_peserta',
        'benefit',
        'alur_seleksi',
        'materi',
        'durasi',
        'biaya',
        'faq'
    ];

    protected $casts = [
        'faq' => 'array',
        'is_featured' => 'boolean',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function currentBatch()
    {
        return $this->batches()->where('status', 'dibuka')->first();
    }

    public function nextBatch()
    {
        return $this->batches()
            ->where('status', 'akan_dibuka')
            ->orderBy('tanggal_buka', 'asc')
            ->first();
    }
}
