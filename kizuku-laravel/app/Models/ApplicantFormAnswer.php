<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantFormAnswer extends Model
{
    protected $fillable = [
        'applicant_id',
        'form_field_id',
        'value',
        'field_label_snapshot',
        'field_type_snapshot'
    ];

    protected $casts = [
        'value' => 'array',
        'field_label_snapshot' => 'array'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function formField()
    {
        return $this->belongsTo(FormField::class)->withTrashed();
    }
}
