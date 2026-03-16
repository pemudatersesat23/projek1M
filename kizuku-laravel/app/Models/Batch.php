<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'program_id',
        'nama_batch',
        'status',
        'tanggal_buka',
        'tanggal_tutup',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'link_form'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
