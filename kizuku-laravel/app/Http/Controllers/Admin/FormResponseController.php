<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FormResponseController extends Controller
{
    /**
     * List all responses (applicants) submitted via a specific form.
     */
    public function index(Request $request, Form $form)
    {
        $form->loadMissing(['program', 'schema', 'batch']);

        $query = Applicant::where('form_id', $form->id)
            ->with([
                'program',
                'batch',
                'programSchema',
                'form',
                'dynamicAnswers.formField',
                'dynamicFiles.formField',
            ]);

        // --- Optional search ---
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama',  'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        // --- Optional status filter ---
        if ($request->filled('status')) {
            $query->where('status_seleksi', $request->status);
        }

        $responses = $query->latest()->paginate(20)->withQueryString();

        $totalResponses = Applicant::where('form_id', $form->id)->count();

        return view('admin.forms.responses.index', compact('form', 'responses', 'totalResponses'));
    }

    /**
     * Export responses for one form as CSV.
     */
    public function exportCsv(Form $form)
    {
        $form->loadMissing(['program', 'schema', 'batch']);

        $fields = $form->fields()
            ->where('status', 'aktif')
            ->where('type', '!=', 'section')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $applicants = Applicant::where('form_id', $form->id)
            ->with([
                'program',
                'batch',
                'programSchema',
                'dynamicAnswers.formField',
                'dynamicFiles.formField',
            ])
            ->latest()
            ->get();

        $baseHeaders = [
            'submitted_at',
            'applicant_id',
            'applicant_name',
            'email',
            'phone',
            'program',
            'batch',
            'schema',
            'form_title',
            'form_version',
        ];

        $dynamicHeaders = $fields
            ->map(fn ($field) => $this->fieldHeader($field))
            ->all();

        $filename = sprintf('responses_%s_%s.csv', $form->id, now()->toDateString());

        return response()->streamDownload(function () use ($applicants, $fields, $baseHeaders, $dynamicHeaders, $form) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM keeps Japanese and Indonesian characters readable in Excel.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_merge($baseHeaders, $dynamicHeaders));

            foreach ($applicants as $applicant) {
                $answersByField = $applicant->dynamicAnswers->keyBy('form_field_id');
                $filesByField = $applicant->dynamicFiles->groupBy('form_field_id');

                $row = [
                    $applicant->created_at?->format('Y-m-d H:i:s') ?? '',
                    $applicant->id,
                    $applicant->nama ?? '',
                    $applicant->email ?? '',
                    $applicant->phone ?? '',
                    $this->localizedValue($applicant->program?->nama_program),
                    $this->localizedValue($applicant->batch?->nama_batch),
                    $this->localizedValue($applicant->programSchema?->nama_skema),
                    $this->localizedValue($applicant->form_title_snapshot ?: $form->title),
                    $applicant->form_version_snapshot ?? $form->version ?? '',
                ];

                foreach ($fields as $field) {
                    if ($field->isFile()) {
                        $row[] = $this->formatFileNamesForCsv($filesByField->get($field->id, collect()));
                        continue;
                    }

                    $answer = $answersByField->get($field->id);
                    $row[] = $this->formatValueForCsv($answer?->value);
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Show a single response detail.
     * Ensures the applicant belongs to the given form (403 otherwise).
     */
    public function show(Form $form, Applicant $applicant)
    {
        // Security: applicant must belong to this form
        if ((int) $applicant->form_id !== (int) $form->id) {
            abort(403, 'Response ini bukan bagian dari formulir yang dimaksud.');
        }

        $applicant->loadMissing([
            'program',
            'batch',
            'programSchema',
            'dynamicAnswers.formField',
            'dynamicFiles.formField',
            'form',
        ]);

        return view('admin.forms.responses.show', compact('form', 'applicant'));
    }

    private function fieldHeader($field): string
    {
        $label = $field->getTranslation('label', 'id', false)
            ?: $field->getTranslation('label', app()->getLocale(), false)
            ?: null;

        return $this->localizedValue($label) ?: $field->field_name;
    }

    private function formatValueForCsv(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_array($value)) {
            return collect(Arr::flatten($value))
                ->map(fn ($item) => $this->formatValueForCsv($item))
                ->filter(fn ($item) => $item !== '')
                ->implode(', ');
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '';
    }

    private function formatFileNamesForCsv($files): string
    {
        return collect($files)
            ->pluck('original_name')
            ->filter()
            ->map(fn ($name) => 'Uploaded: ' . $this->formatValueForCsv($name))
            ->implode(', ');
    }

    private function localizedValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_array($value)) {
            return $value['id']
                ?? $value[app()->getLocale()]
                ?? collect($value)->filter()->first()
                ?? '';
        }

        return $this->formatValueForCsv($value);
    }
}
