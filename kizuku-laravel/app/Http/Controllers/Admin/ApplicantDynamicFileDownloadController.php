<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ApplicantDynamicFile;
use Illuminate\Support\Facades\Storage;

class ApplicantDynamicFileDownloadController extends Controller
{
    /**
     * Serve a dynamic file download to an authenticated admin.
     * Only serves files owned by the given applicant.
     */
    public function download(Applicant $applicant, ApplicantDynamicFile $file)
    {
        // IDOR guard: file must belong to this applicant
        if ($file->applicant_id !== $applicant->id) {
            abort(403, 'Akses ditolak: file ini bukan milik pendaftar yang dimaksud.');
        }

        // File must exist on disk
        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        $downloadName = $file->original_name ?: basename($file->file_path);

        return Storage::disk('local')->download(
            $file->file_path,
            $downloadName,
            ['Content-Type' => $file->mime_type ?? 'application/octet-stream']
        );
    }
}
