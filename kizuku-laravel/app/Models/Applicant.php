<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'program_id',
        'batch_id',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'phone',
        'email',
        'pendidikan',
        'jurusan_ipk',
        'pengalaman',
        'pengalaman_kerja',
        'motivasi',
        'status_seleksi',
        // New specialized fields
        'tinggi_badan',
        'berat_badan',
        'kondisi_mata',
        'tato',
        'merokok',
        'bidang_ssw',
        'level_bahasa_jepang',
        'ipk',
        'shift_kursus',
        'additional_data'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tato' => 'boolean',
        'merokok' => 'boolean',
        'additional_data' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function document()
    {
        return $this->hasOne(ApplicantDocument::class);
    }
}
