<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'program_id', 'name', 'status',
        'registration_start', 'registration_end',
        'class_start', 'class_estimate_end',
        'quota', 'link_form'
    ];

    protected $casts = [
        'registration_start' => 'date',
        'registration_end' => 'date',
        'class_start' => 'date',
        'class_estimate_end' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function registrants()
    {
        return $this->hasMany(Siswa::class);
    }
}
