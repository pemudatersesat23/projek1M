<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Batch extends Model
{
    use HasTranslations, AutoTranslate, SoftDeletes;
    
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

    // Relations
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function programSchemas()
    {
        return $this->hasMany(ProgramSchema::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    // Helpers
    public function isOpen()
    {
        return $this->status === 'dibuka';
    }

    public function isExtended()
    {
        return $this->status === 'diperpanjang';
    }

    public function isUpcoming()
    {
        return $this->status === 'akan_dibuka';
    }

    public function isClosed()
    {
        return in_array($this->status, ['ditutup', 'berjalan', 'selesai']);
    }

    public function isRegistrationEnabled()
    {
        return in_array($this->status, ['dibuka', 'diperpanjang']);
    }

    public function frontendStatusLabel()
    {
        return match($this->status) {
            'dibuka' => 'Aktif',
            'diperpanjang' => 'Diperpanjang',
            'akan_dibuka' => 'Segera Dibuka',
            'berjalan' => 'Sedang Berjalan',
            'selesai' => 'Selesai',
            default => 'Ditutup',
        };
    }

    public function frontendStatusClass()
    {
        return match($this->status) {
            'dibuka', 'diperpanjang' => 'bg-green-100 text-green-800',
            'akan_dibuka' => 'bg-blue-100 text-blue-800',
            'berjalan' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function ctaLabel()
    {
        if ($this->cta_type === 'whatsapp') {
            return __('messages.home.konsultasi') ?? 'Konsultasi';
        }
        return $this->isRegistrationEnabled() ? 'Daftar Sekarang' : 'Pendaftaran Ditutup';
    }

    public function ctaUrl()
    {
        if ($this->cta_type === 'whatsapp' && !empty($this->whatsapp_link)) {
            return $this->whatsapp_link;
        }
        if ($this->isRegistrationEnabled() && $this->program) {
            return route('programs.show', $this->program->slug) . '#registration-section';
        }
        return '#';
    }
}
