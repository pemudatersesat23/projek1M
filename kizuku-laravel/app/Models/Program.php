<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Program extends Model
{
    use HasTranslations, AutoTranslate, SoftDeletes;

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
        'materi',
        'brosur',
        'thumbnail_path',
        'video_url',
        'status',
        'is_featured',
        'sort_order',
        'has_schema'
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
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'has_schema'  => 'boolean'
    ];

    // Relations
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatches()
    {
        return $this->hasMany(Batch::class)->whereIn('status', ['dibuka', 'diperpanjang', 'akan_dibuka']);
    }

    public function programSchemas()
    {
        return $this->hasMany(ProgramSchema::class);
    }

    public function activeSchemas()
    {
        return $this->hasMany(ProgramSchema::class)->where('status', 'aktif')->orderBy('sort_order');
    }

    public function sections()
    {
        return $this->hasMany(ProgramSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activeSections()
    {
        return $this->hasMany(ProgramSection::class)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function formFields()
    {
        return $this->hasMany(FormField::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helpers
    public function currentOpenBatch()
    {
        return $this->batches()->whereIn('status', ['dibuka', 'diperpanjang'])->first();
    }

    public function latestAvailableBatch()
    {
        return $this->activeBatches()->orderBy('tanggal_buka', 'asc')->first() 
            ?? $this->batches()->latest('created_at')->first();
    }

    public function hasActiveSchemas()
    {
        return $this->has_schema && $this->activeSchemas()->count() > 0;
    }

    public function registrationStatusLabel()
    {
        $batch = $this->latestAvailableBatch();
        return $batch ? $batch->frontendStatusLabel() : 'Ditutup';
    }

    public function registrationStatusClass()
    {
        $batch = $this->latestAvailableBatch();
        return $batch ? $batch->frontendStatusClass() : 'bg-gray-100 text-gray-800';
    }

    public function isRegistrationOpen()
    {
        $batch = $this->latestAvailableBatch();
        return $batch ? $batch->isRegistrationEnabled() : false;
    }

    // Accessors
    public function getBenefitItemsAttribute(): array
    {
        $raw = $this->getTranslation('benefit', app()->getLocale());
        if (empty($raw)) return [];
        return array_values(array_filter(array_map('trim', explode("\n", str_replace(['-', '✓'], '', $raw)))));
    }

    public function getAlurSeleksiItemsAttribute(): array
    {
        $raw = $this->getTranslation('alur_seleksi', app()->getLocale());
        if (empty($raw)) return [];
        return array_values(array_filter(array_map('trim', explode("\n", str_replace(['-', '>'], '', $raw)))));
    }
}
