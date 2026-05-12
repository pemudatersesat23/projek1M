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
        'pengalaman_kerja',
        'status_seleksi',
        'additional_data'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
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
