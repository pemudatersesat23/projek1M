<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantDocument;
use Illuminate\Support\Facades\Storage;

class ApplicantDocumentDownloadController extends Controller
{
    public function download(Applicant $applicant, ApplicantDocument $document, string $field)
    {
        if ($document->applicant_id !== $applicant->id) {
            abort(403, 'Akses ditolak: dokumen ini bukan milik pendaftar yang dimaksud.');
        }

        $allowedFields = [
            'ktp',
            'kk',
            'foto',
            'ijazah',
            'sertifikat',
            'cv',
            'transkrip',
            'bukti_sosmed',
        ];

        if (! in_array($field, $allowedFields, true)) {
            abort(404);
        }

        $path = $document->{$field};
        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return Storage::disk('public')->download($path);
    }
}
