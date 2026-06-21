<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Program;
use App\Models\ProgramSchema;
use App\Models\Form;
use App\Models\FormField;
use App\Models\ApplicantFormAnswer;
use Carbon\Carbon;

class UserScenarioSeeder extends Seeder
{
    private const PEOPLE = [
        ['Ahmad Budi', 'L', 'Jakarta'],
        ['Siti Aminah', 'P', 'Bandung'],
        ['Bagus Saputra', 'L', 'Surabaya'],
        ['Dewi Ratnasari', 'P', 'Semarang'],
        ['Eko Prasetyo', 'L', 'Yogyakarta'],
        ['Fitri Ani', 'P', 'Malang'],
        ['Gilang Ramadhan', 'L', 'Medan'],
        ['Hani Farida', 'P', 'Palembang'],
        ['Irfan Hakim', 'L', 'Makassar'],
        ['Jihan Fahira', 'P', 'Bali'],
    ];

    public function run()
    {
        // 1. Hapus Semua Data Pendaftar, Batch, dan Schema
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ApplicantFormAnswer::truncate();
        Applicant::truncate();
        Batch::truncate();
        ProgramSchema::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil semua 5 program
        $programs = Program::active()->get();

        foreach ($programs as $index => $program) {
            
            // --- MEMBUAT PROGRAM SCHEMA ---
            // Kita buat 2 skema untuk setiap program agar user paham fungsinya.
            // Contoh Skema: "Jalur Reguler" dan "Jalur Beasiswa Khusus"
            $schemaReguler = ProgramSchema::create([
                'program_id' => $program->id,
                'nama_skema' => [
                    'id' => 'Jalur Reguler',
                    'jp' => 'レギュラーコース',
                ],
                'slug' => \Illuminate\Support\Str::slug($program->slug . '-jalur-reguler'),
                'tipe' => 'reguler',
                'deskripsi' => [
                    'id' => 'Pendaftaran jalur umum untuk seluruh calon peserta.',
                    'jp' => '全応募者向けの一般登録コース。',
                ],
                'status' => 'aktif',
            ]);

            $schemaBeasiswa = ProgramSchema::create([
                'program_id' => $program->id,
                'nama_skema' => [
                    'id' => 'Jalur Beasiswa',
                    'jp' => '奨学金コース',
                ],
                'slug' => \Illuminate\Support\Str::slug($program->slug . '-jalur-beasiswa'),
                'tipe' => 'beasiswa',
                'deskripsi' => [
                    'id' => 'Jalur pendaftaran khusus bagi yang memiliki prestasi akademik.',
                    'jp' => '学業成績優秀者向けの特別登録コース。',
                ],
                'status' => 'aktif',
            ]);


            // --- MEMBUAT BATCH (GELOMBANG) ---
            // 1 Batch Aktif (Dibuka)
            $batchAktif = Batch::create([
                'program_id' => $program->id,
                'nama_batch' => [
                    'id' => 'Gelombang 1 - 2026',
                    'jp' => '第1バッチ - 2026',
                ],
                'tanggal_buka' => Carbon::now()->subDays(10),
                'tanggal_tutup' => Carbon::now()->addDays(20),
                'kuota' => 50,
                'status' => 'dibuka',
            ]);

            // 1 Batch Akan Datang
            $batchAkanDatang = Batch::create([
                'program_id' => $program->id,
                'nama_batch' => [
                    'id' => 'Gelombang 2 - 2026',
                    'jp' => '第2バッチ - 2026',
                ],
                'tanggal_buka' => Carbon::now()->addDays(30),
                'tanggal_tutup' => Carbon::now()->addDays(60),
                'kuota' => 50,
                'status' => 'akan_dibuka',
            ]);


            // --- MENCARI FORM UNTUK DIISI PENDAFTAR ---
            $form = Form::where('program_id', $program->id)
                ->published()
                ->active()
                ->acceptsResponses()
                ->whereNull('schema_id')
                ->whereNull('batch_id')
                ->latest('id')
                ->first();

            if (!$form) {
                // Jika tidak ada form, kita tidak bisa mengisi pendaftar, lewati saja
                continue;
            }

            $fields = $form->fields()->where('status', 'aktif')->orderBy('sort_order')->get();


            // --- MENGINPUTKAN 5 PENDAFTAR KE BATCH AKTIF ---
            for ($i = 0; $i < 5; $i++) {
                $personIndex = ($index * 5 + $i) % count(self::PEOPLE);
                $person = self::PEOPLE[$personIndex];

                // Membagi pendaftar ke skema reguler dan beasiswa
                $schemaId = ($i % 2 == 0) ? $schemaReguler->id : $schemaBeasiswa->id;

                $applicant = Applicant::create([
                    'program_id' => $program->id,
                    'schema_id' => $schemaId,
                    'batch_id' => $batchAktif->id,
                    'nama' => $person[0],
                    'jenis_kelamin' => $person[1],
                    'tempat_lahir' => $person[2],
                    'tanggal_lahir' => Carbon::now()->subYears(rand(20, 25))->format('Y-m-d'),
                    'alamat' => 'Jl. Merdeka No. ' . rand(1, 100) . ', ' . $person[2],
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email' => strtolower(str_replace(' ', '', $person[0])) . '@example.com',
                    'pendidikan' => 'S1 Teknik Informatika',
                    'pengalaman_kerja' => 'Fresh Graduate',
                    'status_seleksi' => 'baru',
                    'form_id' => $form->id,
                    'form_version_snapshot' => $form->version,
                    'form_title_snapshot' => $form->getTranslations('title'),
                ]);

                // Mengisi jawaban dinamis
                $answers = $this->answersFor($fields, $person, $i, $applicant->email);

                foreach ($fields as $field) {
                    if ($field->type === 'section') continue;

                    if ($field->type === 'file') {
                        $this->createTestFile($applicant, $field, $i);
                        continue;
                    }

                    ApplicantFormAnswer::create([
                        'applicant_id' => $applicant->id,
                        'form_field_id' => $field->id,
                        'value' => $answers[$field->field_name] ?? null,
                        'field_type_snapshot' => $field->type,
                        'field_label_snapshot' => $field->getTranslations('label'),
                    ]);
                }
            }
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
            'applicant_address' => "Jl. Contoh Kizuku No. {$sequence}, {$person[2]}, Indonesia",
            'applicant_phone' => '0812' . str_pad((string) (7000000 + $sequence), 7, '0', STR_PAD_LEFT),
            'applicant_email' => $email,
        ];

        if (isset($roleAnswers[$field->field_role])) {
            return $roleAnswers[$field->field_role];
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

        return $field->type === 'textarea' ? 'Keterangan otomatis untuk pengisian form' : 'Jawaban dummy ' . $sequence;
    }

    private function optionValue(FormField $field, int $sequence): string
    {
        $options = collect($field->options ?? [])->pluck('value')->filter()->values();
        return (string) ($options->get($sequence % max(1, $options->count())) ?? '');
    }

    private function createTestFile(Applicant $applicant, FormField $field, int $sequence): void
    {
        $isPhoto = $field->field_name === 'dokumen_foto';
        $extension = $isPhoto ? 'jpg' : 'pdf';
        $mimeType = $isPhoto ? 'image/jpeg' : 'application/pdf';
        $safeFieldName = \Illuminate\Support\Str::slug($field->field_name, '_');
        $originalName = "{$safeFieldName}_{$sequence}.{$extension}";
        $path = "private/registrations/{$applicant->id}/demo_{$field->id}_{$originalName}";
        
        $content = $isPhoto
            ? base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=')
            : "%PDF-1.4\n% Dokumen uji Kizuku\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";

        \Illuminate\Support\Facades\Storage::disk('local')->put($path, $content);

        \App\Models\ApplicantDynamicFile::create([
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
