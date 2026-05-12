<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDynamicFile extends Model
{
    protected $fillable = [
        'applicant_id',
        'form_field_id',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'field_label_snapshot',
        'field_type_snapshot'
    ];

    protected $casts = [
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

    public function readableSize()
    {
        $bytes = $this->size * 1024; // size in KB
        if ($bytes == 0) return '0.00 B';

        $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes, 1024));

        return round($bytes/pow(1024, $e), 2) . ' ' . $s[$e];
    }

    public function isOwnedByApplicant($applicantId)
    {
        return $this->applicant_id == $applicantId;
    }
}
