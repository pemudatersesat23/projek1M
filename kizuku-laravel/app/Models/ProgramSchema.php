<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class ProgramSchema extends Model
{
    use HasTranslations, AutoTranslate, SoftDeletes;

    protected $fillable = [
        'program_id',
        'batch_id',
        'nama_skema',
        'slug',
        'tipe',
        'deskripsi',
        'persyaratan',
        'harga',
        'status',
        'sort_order'
    ];

    public $translatable = [
        'nama_skema',
        'deskripsi',
        'persyaratan'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // Relations
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'schema_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    // Helpers
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    public function isScholarship()
    {
        return in_array($this->tipe, ['beasiswa', 'scholar_partnership']);
    }

    public function formattedPrice()
    {
        return $this->harga > 0 ? 'Rp ' . number_format($this->harga, 0, ',', '.') : 'Gratis';
    }
}
