<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private const STATUS_OPTIONS = ['baru', 'review', 'lolos', 'tidak_lolos', 'interview'];

    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $query = $this->applicantQuery($filters);

        $totalApplicants = (clone $query)->count();
        $previewApplicants = $query
            ->latest()
            ->take(5)
            ->get();
        $programs = Program::orderBy('sort_order')->orderBy('id')->get();
        $batches = Batch::with('program')->latest()->get();

        return view('admin.export', compact('totalApplicants', 'previewApplicants', 'filters', 'programs', 'batches'));
    }

    public function download(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $applicants = $this->applicantQuery($filters)->latest()->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data_applicants_' . date('Y-m-d') . '.xls"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($applicants) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<thead><tr>';

            foreach ([
                'No',
                'Nama',
                'WhatsApp',
                'Email',
                'Program',
                'Batch',
                'Schema',
                'Status Seleksi',
                'Tanggal Daftar',
            ] as $header) {
                echo '<th>' . e($header) . '</th>';
            }

            echo '</tr></thead><tbody>';

            foreach ($applicants as $i => $applicant) {
                echo '<tr>';
                foreach ([
                    $i + 1,
                    $applicant->nama ?? '-',
                    $applicant->phone ?? '-',
                    $applicant->email ?? '-',
                    $this->localizedValue($applicant->program?->nama_program),
                    $this->localizedValue($applicant->batch?->nama_batch),
                    $this->localizedValue($applicant->programSchema?->nama_skema),
                    $applicant->status_seleksi ?? '-',
                    $applicant->created_at?->format('d/m/Y H:i') ?? '-',
                ] as $cell) {
                    echo '<td>' . e((string) ($cell ?: '-')) . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table>';
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'batch_id' => ['nullable', 'integer', 'exists:batches,id'],
            'status' => ['nullable', Rule::in(self::STATUS_OPTIONS)],
        ]);
    }

    private function applicantQuery(array $filters)
    {
        return Applicant::with(['program', 'batch', 'programSchema'])
            ->when(! empty($filters['program_id']), fn ($query) => $query->where('program_id', $filters['program_id']))
            ->when(! empty($filters['batch_id']), fn ($query) => $query->where('batch_id', $filters['batch_id']))
            ->when(! empty($filters['status']), fn ($query) => $query->where('status_seleksi', $filters['status']));
    }

    private function localizedValue(mixed $value): string
    {
        if (is_array($value)) {
            return $value['id'] ?? collect($value)->filter()->first() ?? '-';
        }

        return $value ? (string) $value : '-';
    }
}
