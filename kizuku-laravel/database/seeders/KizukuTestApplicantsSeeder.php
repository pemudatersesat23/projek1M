<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\ApplicantDynamicFile;
use App\Models\ApplicantFormAnswer;
use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class KizukuTestApplicantsSeeder extends Seeder
{
    private const APPLICANTS_PER_PROGRAM = 7;

    private const STATUSES = [
        'baru',
        'review',
        'interview',
        'lolos',
        'tidak_lolos',
        'baru',
        'review',
    ];

    private const PEOPLE = [
        ['Andi Pratama', 'L', 'Makassar'],
        ['Nur Aisyah Ramadhani', 'P', 'Gowa'],
        ['Fajar Hidayat', 'L', 'Maros'],
        ['Siti Rahmawati', 'P', 'Parepare'],
        ['Muhammad Rizky Akbar', 'L', 'Bone'],
        ['Dewi Lestari', 'P', 'Bulukumba'],
        ['Arman Saputra', 'L', 'Palopo'],
        ['Nadia Maharani', 'P', 'Makassar'],
        ['Ilham Maulana', 'L', 'Takalar'],
        ['Putri Ananda', 'P', 'Soppeng'],
    ];

    public function run(): void
    {
        $this->removePreviousTestApplicants();

        $programs = Program::active()
            ->ordered()
            ->whereIn('slug', [
                'tokutei-ginou-tg',
                'engineer-jepang-gijinkoku',
                'kenshusei-jishussei-magang-jepang',
                'kursus-bahasa-jepang',
                'engineer-jepang-ex-internship',
            ])
            ->get();

        if ($programs->count() !== 5) {
            throw new RuntimeException('Lima program aktif Kizuku belum tersedia.');
        }

        foreach ($programs as $programIndex => $program) {
            $batch = $program->batches()
                ->whereIn('status', ['dibuka', 'diperpanjang'])
                ->latest('tanggal_buka')
                ->first();
            $form = Form::where('program_id', $program->id)
                ->published()
                ->active()
                ->acceptsResponses()
                ->whereNull('schema_id')
                ->whereNull('batch_id')
                ->latest('id')
                ->first();

            if (! $batch || ! $form) {
                throw new RuntimeException("Batch atau formulir aktif untuk {$program->slug} tidak ditemukan.");
            }

            $fields = $form->fields()
                ->where('status', 'aktif')
                ->ordered()
                ->get();

            for ($index = 0; $index < self::APPLICANTS_PER_PROGRAM; $index++) {
                $person = self::PEOPLE[($programIndex * 2 + $index) % count(self::PEOPLE)];
                $sequence = $programIndex * self::APPLICANTS_PER_PROGRAM + $index + 1;
                $email = sprintf('demo.%s.%02d@kizuku.test', $program->slug, $index + 1);
                $answers = $this->answersFor($fields, $person, $sequence, $email);

                DB::transaction(function () use (
                    $program,
                    $batch,
                    $form,
                    $fields,
                    $person,
                    $sequence,
                    $index,
                    $answers
                ) {
                    $applicant = Applicant::create([
                        'program_id' => $program->id,
                        'schema_id' => null,
                        'batch_id' => $batch->id,
                        'form_id' => $form->id,
                        'nama' => $person[0],
                        'jenis_kelamin' => $person[1],
                        'tempat_lahir' => $person[2],
                        'tanggal_lahir' => now()->subYears(19 + ($sequence % 10))->subDays($sequence * 11)->toDateString(),
                        'alamat' => "Jl. Contoh Kizuku No. {$sequence}, {$person[2]}, Sulawesi Selatan",
                        'phone' => '0812' . str_pad((string) (7000000 + $sequence), 7, '0', STR_PAD_LEFT),
                        'email' => $answers['email'],
                        'pendidikan' => $this->educationLabel($fields, $answers),
                        'pengalaman_kerja' => "Pengalaman kerja atau organisasi selama " . (($sequence % 4) + 1) . ' tahun.',
                        'status_seleksi' => self::STATUSES[$index],
                        'additional_data' => [
                            'seed_source' => 'kizuku_test_applicants',
                            'test_sequence' => $sequence,
                        ],
                        'form_version_snapshot' => $form->version,
                        'form_title_snapshot' => $form->getTranslations('title'),
                    ]);

                    foreach ($fields->where('type', '!=', 'section') as $field) {
                        if ($field->isFile()) {
                            $this->createTestFile($applicant, $field, $sequence);
                            continue;
                        }

                        ApplicantFormAnswer::create([
                            'applicant_id' => $applicant->id,
                            'form_field_id' => $field->id,
                            'value' => $answers[$field->field_name] ?? '',
                            'field_label_snapshot' => $field->getTranslations('label'),
                            'field_type_snapshot' => $field->type,
                        ]);
                    }

                    $submittedAt = now()->subDays(($sequence * 2) % 34)->subMinutes($sequence * 7);
                    $applicant->timestamps = false;
                    $applicant->forceFill([
                        'created_at' => $submittedAt,
                        'updated_at' => $submittedAt,
                    ])->save();
                });
            }
        }
    }

    private function removePreviousTestApplicants(): void
    {
        $applicants = Applicant::where('email', 'like', 'demo.%@kizuku.test')
            ->with('dynamicFiles')
            ->get();

        foreach ($applicants as $applicant) {
            foreach ($applicant->dynamicFiles as $file) {
                Storage::disk('local')->delete($file->file_path);
            }
            $applicant->delete();
        }
    }

    private function answersFor($fields, array $person, int $sequence, string $email): array
    {
        $answers = [];

        foreach ($fields->where('type', '!=', 'section')->where('type', '!=', 'file') as $field) {
            $answers[$field->field_name] = $this->answerFor($field, $person, $sequence, $email);
        }

        return $answers;
    }

    private function answerFor(FormField $field, array $person, int $sequence, string $email): mixed
    {
        $roleAnswers = [
            'applicant_name' => $person[0],
            'applicant_gender' => $person[1],
            'applicant_pob' => $person[2],
            'applicant_birth_date' => now()->subYears(19 + ($sequence % 10))->subDays($sequence * 11)->toDateString(),
            'applicant_address' => "Jl. Contoh Kizuku No. {$sequence}, {$person[2]}, Sulawesi Selatan",
            'applicant_phone' => '0812' . str_pad((string) (7000000 + $sequence), 7, '0', STR_PAD_LEFT),
            'applicant_email' => $email,
        ];

        if (isset($roleAnswers[$field->field_role])) {
            return $roleAnswers[$field->field_role];
        }

        if ($field->field_role === 'applicant_education') {
            return $this->optionValue($field, $sequence);
        }

        if (in_array($field->type, ['select', 'radio'], true)) {
            return $this->optionValue($field, $sequence);
        }

        if ($field->type === 'checkbox') {
            $values = collect($field->options ?? [])->pluck('value')->values();

            return $values->count() > 1
                ? $values->slice($sequence % 2, min(2, $values->count()))->values()->all()
                : $values->take(1)->all();
        }

        if ($field->type === 'date') {
            return now()->subYears(19 + ($sequence % 10))->subDays($sequence * 11)->toDateString();
        }

        if ($field->type === 'number') {
            return (string) (2017 + ($sequence % 8));
        }

        return match ($field->field_name) {
            'email' => "demo.contact.{$sequence}@example.com",
            'jurusan' => ['Teknik Mesin', 'Teknik Elektro', 'Teknik Sipil', 'Teknik Informatika'][$sequence % 4],
            'nama_sekolah', 'nama_universitas' => 'Universitas Negeri Makassar',
            'tinggi_berat_badan' => (160 + ($sequence % 16)) . ' cm / ' . (50 + ($sequence % 21)) . ' kg',
            'bidang_magang' => ['Pengolahan Makanan', 'Pertanian', 'Konstruksi', 'Manufaktur'][$sequence % 4],
            'target_belajar' => 'Lulus JLPT N4 dan berangkat ke Jepang tahun 2027',
            default => $field->type === 'textarea'
                ? 'Saya ingin meningkatkan kompetensi, disiplin, dan pengalaman profesional melalui program Kizuku.'
                : 'Data uji ' . Str::headline($field->field_name) . " {$sequence}",
        };
    }

    private function optionValue(FormField $field, int $sequence): string
    {
        $options = collect($field->options ?? [])->pluck('value')->filter()->values();

        return (string) ($options->get($sequence % max(1, $options->count())) ?? '');
    }

    private function educationLabel($fields, array $answers): string
    {
        $field = $fields->firstWhere('field_role', 'applicant_education');
        $value = $field ? ($answers[$field->field_name] ?? null) : null;
        $option = collect($field?->options ?? [])->firstWhere('value', $value);

        return $option['label']['id'] ?? $value ?? 'SMA/SMK';
    }

    private function createTestFile(Applicant $applicant, FormField $field, int $sequence): void
    {
        $isPhoto = $field->field_name === 'dokumen_foto';
        $extension = $isPhoto ? 'jpg' : 'pdf';
        $mimeType = $isPhoto ? 'image/jpeg' : 'application/pdf';
        $safeFieldName = Str::slug($field->field_name, '_');
        $originalName = "{$safeFieldName}_{$sequence}.{$extension}";
        $path = "private/registrations/{$applicant->id}/demo_{$field->id}_{$originalName}";
        $content = $isPhoto
            ? base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=')
            : "%PDF-1.4\n% Dokumen uji Kizuku\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";

        Storage::disk('local')->put($path, $content);

        ApplicantDynamicFile::create([
            'applicant_id' => $applicant->id,
            'form_field_id' => $field->id,
            'file_path' => $path,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size' => max(1, (int) ceil(strlen($content) / 1024)),
            'field_label_snapshot' => $field->getTranslations('label'),
            'field_type_snapshot' => $field->type,
        ]);
    }
}
