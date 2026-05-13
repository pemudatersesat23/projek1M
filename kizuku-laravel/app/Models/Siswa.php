<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Legacy model retained for backward compatibility. New registration data uses Applicant.
    protected $fillable = [
        'nama', 'wa', 'email', 'kota', 'program', 'batch_id',
        'status', 'pendidikan', 'catatan', 'tgl_lahir',
        'extra_fields', 'payment_status',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
