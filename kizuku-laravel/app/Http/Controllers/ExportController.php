<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $previewSiswas = Siswa::latest()->take(5)->get();

        return view('admin.export', compact('totalSiswa', 'previewSiswas'));
    }

    public function download(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_siswa_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($siswas) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, ['No', 'Nama', 'WhatsApp', 'Email', 'Kota', 'Program', 'Status', 'Pendidikan', 'Status Bayar', 'Tgl Daftar', 'Catatan']);

            // Data
            foreach ($siswas as $i => $s) {
                fputcsv($file, [
                    $i + 1,
                    $s->nama,
                    $s->wa,
                    $s->email ?? '-',
                    $s->kota,
                    $s->program,
                    $s->status,
                    $s->pendidikan ?? '-',
                    $s->payment_status ?? 'Pending',
                    $s->created_at->format('d/m/Y'),
                    $s->catatan ?? '-',
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
