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
        'ttl',
        'alamat',
        'phone',
        'email',
        'pendidikan',
        'pengalaman',
        'motivasi',
        'status_seleksi'
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
