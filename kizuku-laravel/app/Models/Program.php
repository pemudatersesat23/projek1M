<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Program extends Model
{
    use HasTranslations, AutoTranslate;

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

    /**
     * Accessor: parse teks benefit menjadi array baris bersih.
     * Mendukung format: "- Item" atau "✓ Item" atau plain text per baris.
     * Gunakan: $program->benefitItems (otomatis locale-aware via HasTranslations)
     *
     * @return array<int, string>
     */
    public function getBenefitItemsAttribute(): array
    {
        $raw = $this->getTranslation('benefit', app()->getLocale());

        if (empty($raw)) {
            return [];
        }

        $lines = explode("\n", str_replace(['-', '✓'], '', $raw));

        return array_values(
            array_filter(
                array_map('trim', $lines)
            )
        );
    }

    /**
     * Accessor: parse teks alur_seleksi menjadi array baris bersih.
     * Mendukung format: "- Step" atau "> Step" atau plain text per baris.
     * Gunakan: $program->alurSeleksiItems
     *
     * @return array<int, string>
     */
    public function getAlurSeleksiItemsAttribute(): array
    {
        $raw = $this->getTranslation('alur_seleksi', app()->getLocale());

        if (empty($raw)) {
            return [];
        }

        $lines = explode("\n", str_replace(['-', '>'], '', $raw));

        return array_values(
            array_filter(
                array_map('trim', $lines)
            )
        );
    }
}
