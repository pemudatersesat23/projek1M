<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class KizukuProgramFormsFromDocumentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->formDefinitions() as $slug => $definition) {
                $program = Program::where('slug', $slug)->first();

                if (! $program) {
                    throw new RuntimeException("Program dengan slug {$slug} tidak ditemukan.");
                }

                $form = $this->prepareForm($program, $definition);

                if (! $form->applicants()->exists()) {
                    FormField::withTrashed()
                        ->where('form_id', $form->id)
                        ->forceDelete();

                    foreach ($definition['fields'] as $index => $field) {
                        $form->fields()->create($this->fieldPayload(
                            $program->id,
                            $field,
                            $index + 1
                        ));
                    }
                }

                $program->batches()
                    ->whereIn('status', ['dibuka', 'diperpanjang'])
                    ->update([
                        'cta_type' => 'internal_form',
                        'link_form' => null,
                        'whatsapp_link' => null,
                    ]);
            }
        });
    }

    private function prepareForm(Program $program, array $definition): Form
    {
        $canonicalForm = Form::withTrashed()
            ->where('program_id', $program->id)
            ->whereNull('schema_id')
            ->whereNull('batch_id')
            ->get()
            ->first(fn (Form $form) => $form->getTranslation('title', 'id', false) === $definition['title']);

        Form::where('program_id', $program->id)
            ->whereNull('schema_id')
            ->whereNull('batch_id')
            ->when($canonicalForm, fn ($query) => $query->where('id', '!=', $canonicalForm->id))
            ->where(function ($query) {
                $query->where('status', 'published')
                    ->orWhere('is_active', true);
            })
            ->update([
                'status' => 'archived',
                'is_active' => false,
                'accepts_responses' => false,
            ]);

        if ($canonicalForm?->trashed()) {
            $canonicalForm->restore();
        }

        $form = $canonicalForm ?? new Form();
        $form->fill([
            'program_id' => $program->id,
            'schema_id' => null,
            'batch_id' => null,
            'title' => $this->translated($definition['title']),
            'description' => $this->translated($definition['description']),
            'success_message' => $this->translated(
                'Pendaftaran berhasil dikirim. Tim Kizuku akan menghubungi Anda melalui WhatsApp atau email.'
            ),
            'status' => 'published',
            'is_active' => true,
            'accepts_responses' => true,
            'version' => max(1, (int) ($form->version ?? 1)),
            'published_at' => now(),
        ]);
        $form->save();

        return $form;
    }

    private function fieldPayload(int $programId, array $field, int $sortOrder): array
    {
        return [
            'program_id' => $programId,
            'schema_id' => null,
            'label' => $this->translated($field['label']),
            'field_name' => $field['name'],
            'type' => $field['type'],
            'field_role' => $field['role'] ?? 'none',
            'placeholder' => isset($field['placeholder'])
                ? $this->translated($field['placeholder'])
                : null,
            'description' => isset($field['description'])
                ? $this->translated($field['description'])
                : null,
            'options' => isset($field['options'])
                ? $this->options($field['options'])
                : null,
            'accepted_file_types' => $field['accepted_file_types'] ?? null,
            'max_file_size' => $field['max_file_size'] ?? null,
            'is_required' => $field['required'] ?? false,
            'is_locked' => false,
            'settings' => $field['settings'] ?? null,
            'status' => 'aktif',
            'sort_order' => $sortOrder,
        ];
    }

    private function formDefinitions(): array
    {
        return [
            'tokutei-ginou-tg' => [
                'title' => 'Form Pendaftaran Tokutei Ginou (TG)',
                'description' => 'Lengkapi data diri, pendidikan, bidang pekerjaan, dan dokumen untuk mengikuti seleksi Tokutei Ginou.',
                'fields' => [
                    ...$this->personalFields(),
                    $this->section('pendidikan', 'Pendidikan dan Keahlian', 'graduation-cap'),
                    $this->choice('pendidikan_terakhir', 'Pendidikan Terakhir', ['SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2'], 'select', true, 'applicant_education'),
                    $this->text('jurusan', 'Jurusan / Program Studi'),
                    $this->text('nama_sekolah', 'Nama Sekolah / Universitas'),
                    $this->number('tahun_lulus', 'Tahun Lulus'),
                    $this->choice('kemampuan_bahasa_jepang', 'Kemampuan Bahasa Jepang', ['Belum belajar', 'Sedang belajar', 'JLPT N5', 'JLPT N4', 'JLPT N3 atau lebih']),
                    $this->choice('bidang_tg', 'Bidang Tokutei Ginou yang Diminati', [
                        'Pengolahan Makanan',
                        'Pertanian',
                        'Perawatan Lansia (Kaigo)',
                        'Restoran / Layanan Makanan',
                        'Perhotelan',
                        'Konstruksi',
                        'Manufaktur Mesin dan Peralatan',
                        'Otomotif',
                        'Peternakan',
                        'Pembersihan Gedung',
                    ], 'checkbox'),
                    $this->textarea('pengalaman_kerja', 'Pengalaman Kerja'),
                    ...$this->documentFields(['ktp', 'kk', 'foto', 'ijazah', 'sertifikat', 'bukti_follow']),
                    $this->section('tambahan', 'Informasi Tambahan', 'clipboard-list'),
                    $this->yesNo('bersedia_kontrak_tiga_tahun', 'Bersedia Menjalani Kontrak Minimal 3 Tahun?'),
                    $this->textarea('motivasi', 'Motivasi Bekerja di Jepang'),
                    $this->yesNo('pernah_magang_jepang', 'Pernah Mengikuti Program Magang Jepang Sebelumnya?'),
                    ...$this->declarationFields([
                        'Saya menyatakan seluruh data yang diberikan benar.',
                        'Saya bersedia mengikuti seluruh proses seleksi dan pelatihan.',
                        'Saya siap mengikuti seleksi offline di Makassar.',
                    ]),
                ],
            ],
            'engineer-jepang-gijinkoku' => [
                'title' => 'Form Pendaftaran Engineering / Gijinkoku',
                'description' => 'Lengkapi profil pendidikan teknik, kemampuan bahasa Jepang, pengalaman, dan dokumen pendukung.',
                'fields' => [
                    ...$this->personalFields(),
                    $this->section('pendidikan', 'Pendidikan dan Keahlian', 'graduation-cap'),
                    $this->choice('pendidikan_terakhir', 'Pendidikan Terakhir', ['D3', 'D4', 'S1', 'S2'], 'select', true, 'applicant_education'),
                    $this->choice('jurusan', 'Jurusan / Program Studi', [
                        'Teknik Sipil',
                        'Teknik Arsitektur',
                        'Teknik Mesin Elektro',
                        'Teknik Mesin',
                        'Teknik Informatika',
                        'Lainnya',
                    ]),
                    $this->text('nama_universitas', 'Nama Universitas'),
                    $this->number('tahun_lulus', 'Tahun Lulus'),
                    $this->choice('kemampuan_bahasa_jepang', 'Level Bahasa Jepang', ['Belum belajar', 'Sedang belajar', 'JLPT N5', 'JLPT N4', 'JLPT N3 atau lebih']),
                    $this->textarea('pengalaman_kerja', 'Pengalaman Kerja'),
                    ...$this->documentFields(['cv', 'ktp', 'kk', 'foto', 'ijazah', 'transkrip', 'sertifikat', 'bukti_follow']),
                    $this->section('tambahan', 'Informasi Tambahan', 'clipboard-list'),
                    $this->textarea('motivasi', 'Motivasi Bekerja sebagai Engineer di Jepang'),
                    $this->yesNo('pernah_magang_jepang', 'Pernah Mengikuti Program Magang Jepang Sebelumnya?'),
                    ...$this->declarationFields([
                        'Saya menyatakan seluruh data yang diberikan benar.',
                    ]),
                ],
            ],
            'kenshusei-jishussei-magang-jepang' => [
                'title' => 'Form Pendaftaran Kenshusei / Jishussei (Magang Jepang)',
                'description' => 'Lengkapi data pribadi, kondisi fisik, minat bidang magang, dan dokumen seleksi.',
                'fields' => [
                    ...$this->personalFields(),
                    $this->section('profil_tambahan', 'Profil Tambahan', 'user-round'),
                    $this->text('tinggi_berat_badan', 'Tinggi dan Berat Badan', true, 'Contoh: 170 cm / 60 kg'),
                    $this->choice('status_pernikahan', 'Status Pernikahan', ['Belum Menikah', 'Menikah', 'Cerai']),
                    $this->section('pendidikan', 'Pendidikan dan Program Magang', 'graduation-cap'),
                    $this->choice('pendidikan_terakhir', 'Pendidikan Terakhir', ['SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1'], 'select', true, 'applicant_education'),
                    $this->text('jurusan', 'Jurusan / Program Studi', false),
                    $this->number('tahun_lulus', 'Tahun Lulus', false),
                    $this->text('bidang_magang', 'Bidang Magang yang Diminati'),
                    $this->choice('kemampuan_bahasa_jepang', 'Level Bahasa Jepang', ['Belum belajar', 'Sedang belajar', 'JLPT N5', 'JLPT N4', 'JLPT N3 atau lebih']),
                    ...$this->documentFields(['ktp', 'kk', 'ijazah', 'foto', 'sertifikat', 'bukti_follow']),
                    $this->section('tambahan', 'Informasi Tambahan', 'clipboard-list'),
                    $this->yesNo('bersedia_pelatihan', 'Bersedia Mengikuti Pelatihan Sebelum Keberangkatan?'),
                    $this->yesNo('bersedia_ditempatkan', 'Bersedia Ditempatkan di Seluruh Wilayah Jepang?'),
                    $this->textarea('motivasi', 'Motivasi Mengikuti Program Magang Jepang'),
                    ...$this->declarationFields([
                        'Saya menyatakan seluruh data yang diberikan benar.',
                    ]),
                ],
            ],
            'kursus-bahasa-jepang' => [
                'title' => 'Form Pendaftaran Kursus Bahasa Jepang',
                'description' => 'Pilih kelas, sistem belajar, level saat ini, serta target belajar bahasa Jepang Anda.',
                'fields' => [
                    ...$this->personalFields(false),
                    $this->section('pilihan_program', 'Pilihan Program Kursus', 'book-open'),
                    $this->choice('pilihan_kelas', 'Pilihan Kelas', [
                        'N5',
                        'N4',
                        'N3',
                        'Kaiwa',
                        'Persiapan JLPT',
                        'Persiapan Tokutei Ginou',
                        'Persiapan Engineering',
                    ]),
                    $this->choice('sistem_kelas', 'Sistem Kelas', ['Online', 'Offline'], 'radio'),
                    $this->choice('level_saat_ini', 'Level Bahasa Jepang Saat Ini', ['Belum pernah belajar', 'Dasar', 'JLPT N5', 'JLPT N4', 'JLPT N3 atau lebih']),
                    $this->section('tujuan', 'Tujuan dan Target', 'target'),
                    $this->textarea('tujuan_kursus', 'Tujuan Mengikuti Kursus'),
                    $this->text('target_belajar', 'Target JLPT / Target Keberangkatan', false),
                    ...$this->declarationFields([
                        'Saya bersedia mengikuti aturan dan ketentuan kelas.',
                    ]),
                ],
            ],
            'engineer-jepang-ex-internship' => [
                'title' => 'Form Pendaftaran Engineer Jepang (Ex-Internship)',
                'description' => 'Formulir khusus alumni magang Jepang yang ingin melanjutkan karier sebagai engineer.',
                'fields' => [
                    ...$this->personalFields(),
                    $this->section('pendidikan', 'Pendidikan dan Pengalaman', 'graduation-cap'),
                    $this->choice('pendidikan_terakhir', 'Pendidikan Terakhir', ['SMA/SMK', 'D3', 'D4', 'S1', 'S2'], 'select', true, 'applicant_education'),
                    $this->choice('jurusan', 'Jurusan / Program Studi', ['Teknik Mesin', 'Teknik Elektro', 'Teknik Sipil', 'Lainnya']),
                    $this->text('nama_sekolah', 'Nama Sekolah / Universitas'),
                    $this->number('tahun_lulus', 'Tahun Lulus'),
                    $this->choice('kemampuan_bahasa_jepang', 'Kemampuan Bahasa Jepang', ['Belum belajar', 'Sedang belajar', 'JLPT N5', 'JLPT N4', 'JLPT N3 atau lebih']),
                    $this->textarea('pengalaman_kerja', 'Pengalaman Kerja (Perusahaan, Posisi, dan Durasi)'),
                    ...$this->documentFields(['cv', 'ijazah', 'transkrip', 'sertifikat', 'bukti_follow']),
                    $this->section('tambahan', 'Informasi Tambahan', 'clipboard-list'),
                    $this->textarea('motivasi', 'Motivasi Bekerja sebagai Engineer di Jepang'),
                    ...$this->declarationFields([
                        'Saya menyatakan seluruh data yang diberikan benar.',
                    ]),
                ],
            ],
        ];
    }

    private function personalFields(bool $allRequired = true): array
    {
        return [
            $this->section('data_pribadi', 'Data Pribadi', 'user'),
            $this->text('nama_lengkap', 'Nama Lengkap Sesuai KTP / Paspor', true, null, 'applicant_name'),
            $this->choice('jenis_kelamin', 'Jenis Kelamin', ['L' => 'Laki-laki', 'P' => 'Perempuan'], 'radio', $allRequired, 'applicant_gender'),
            $this->text('tempat_lahir', 'Tempat Lahir', $allRequired, null, 'applicant_pob'),
            $this->field('tanggal_lahir', 'Tanggal Lahir', 'date', $allRequired, 'applicant_birth_date'),
            $this->textarea('alamat_domisili', 'Alamat Domisili', $allRequired, null, 'applicant_address'),
            $this->field('nomor_whatsapp', 'Nomor HP / WhatsApp', 'phone', true, 'applicant_phone', [
                'placeholder' => 'Contoh: 081234567890',
            ]),
            $this->field('email', 'Email', 'email', true, 'applicant_email'),
        ];
    }

    private function documentFields(array $documents): array
    {
        $definitions = [
            'cv' => ['CV / Curriculum Vitae', true, ['pdf', 'doc', 'docx']],
            'ktp' => ['KTP', true, ['pdf', 'jpg', 'jpeg', 'png']],
            'kk' => ['Kartu Keluarga', true, ['pdf', 'jpg', 'jpeg', 'png']],
            'foto' => ['Foto Terbaru', true, ['jpg', 'jpeg', 'png']],
            'ijazah' => ['Ijazah Terakhir', true, ['pdf', 'jpg', 'jpeg', 'png']],
            'transkrip' => ['Transkrip Nilai', true, ['pdf', 'jpg', 'jpeg', 'png']],
            'sertifikat' => ['Sertifikat Bahasa Jepang / Keterampilan', false, ['pdf', 'jpg', 'jpeg', 'png']],
            'bukti_follow' => ['Bukti Follow Instagram dan TikTok @kizuku.academy', true, ['pdf', 'jpg', 'jpeg', 'png']],
        ];

        $fields = [$this->section('dokumen', 'Dokumen Pendukung', 'folder-open')];

        foreach ($documents as $document) {
            [$label, $required, $extensions] = $definitions[$document];
            $fields[] = $this->file("dokumen_{$document}", $label, $required, $extensions);
        }

        return $fields;
    }

    private function declarationFields(array $statements): array
    {
        $fields = [$this->section('pernyataan', 'Pernyataan', 'badge-check')];

        foreach ($statements as $index => $statement) {
            $fields[] = $this->choice(
                'pernyataan_' . ($index + 1),
                $statement,
                ['Saya setuju'],
                'checkbox'
            );
        }

        return $fields;
    }

    private function section(string $name, string $label, string $icon): array
    {
        return $this->field("section_{$name}", $label, 'section', false, 'none', [
            'settings' => [
                'section_icon' => $icon,
                'section_color' => '#0067A3',
            ],
        ]);
    }

    private function text(
        string $name,
        string $label,
        bool $required = true,
        ?string $placeholder = null,
        string $role = 'none'
    ): array {
        return $this->field($name, $label, 'text', $required, $role, array_filter([
            'placeholder' => $placeholder,
        ]));
    }

    private function textarea(
        string $name,
        string $label,
        bool $required = true,
        ?string $placeholder = null,
        string $role = 'none'
    ): array {
        return $this->field($name, $label, 'textarea', $required, $role, array_filter([
            'placeholder' => $placeholder,
        ]));
    }

    private function number(string $name, string $label, bool $required = true): array
    {
        return $this->field($name, $label, 'number', $required);
    }

    private function choice(
        string $name,
        string $label,
        array $options,
        string $type = 'select',
        bool $required = true,
        string $role = 'none'
    ): array {
        return $this->field($name, $label, $type, $required, $role, [
            'options' => $options,
        ]);
    }

    private function yesNo(string $name, string $label): array
    {
        return $this->choice($name, $label, ['Ya', 'Tidak'], 'radio');
    }

    private function file(
        string $name,
        string $label,
        bool $required,
        array $acceptedFileTypes
    ): array {
        return $this->field($name, $label, 'file', $required, 'none', [
            'accepted_file_types' => $acceptedFileTypes,
            'max_file_size' => 5120,
            'description' => 'Ukuran file maksimal 5 MB.',
        ]);
    }

    private function field(
        string $name,
        string $label,
        string $type,
        bool $required = true,
        string $role = 'none',
        array $extra = []
    ): array {
        return array_merge([
            'name' => $name,
            'label' => $label,
            'type' => $type,
            'required' => $required,
            'role' => $role,
        ], $extra);
    }

    private function options(array $labels): array
    {
        $options = [];

        foreach ($labels as $value => $label) {
            $options[] = [
                'value' => is_string($value)
                    ? $value
                    : str($label)->lower()->ascii()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->toString(),
                'label' => $this->translated($label),
            ];
        }

        return $options;
    }

    private function translated(string $value): array
    {
        return [
            'id' => $value,
            'jp' => $value,
        ];
    }
}
