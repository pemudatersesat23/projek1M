<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        $totalApplicants = Applicant::count();
        $previewApplicants = Applicant::with(['program', 'batch', 'programSchema'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.export', compact('totalApplicants', 'previewApplicants'));
    }

    public function download(Request $request)
    {
        $query = Applicant::with(['program', 'batch', 'programSchema']);

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->filled('status')) {
            $query->where('status_seleksi', $request->status);
        }

        $applicants = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_applicants_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($applicants) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No',
                'Nama',
                'WhatsApp',
                'Email',
                'Program',
                'Batch',
                'Schema',
                'Status Seleksi',
                'Tanggal Daftar',
            ]);

            foreach ($applicants as $i => $applicant) {
                fputcsv($file, [
                    $i + 1,
                    $applicant->nama ?? '-',
                    $applicant->phone ?? '-',
                    $applicant->email ?? '-',
                    $applicant->program?->nama_program ?? '-',
                    $applicant->batch?->nama_batch ?? '-',
                    $applicant->programSchema?->nama_skema ?? '-',
                    $applicant->status_seleksi ?? '-',
                    $applicant->created_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
