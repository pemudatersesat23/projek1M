<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Batch;
use App\Models\ProgramSchema;
use App\Models\Form;
use App\Models\FormField;

class CustomProgramSchemaSeeder extends Seeder
{
    public function run(): void
    {
        // Disable AutoTranslate to speed up
        Program::disableAutoTranslate();
        ProgramSchema::disableAutoTranslate();
        FormField::disableAutoTranslate();

        // 1. Cari program selain TG, misalnya Engineering / Gijinkoku
        $program = Program::where('slug', 'engineer-jepang-gijinkoku')->first();
        if (!$program) {
            $this->command->error('Program Engineering / Gijinkoku tidak ditemukan. Harap jalankan KizukuFullResetSeeder terlebih dahulu.');
            return;
        }

        // Aktifkan Fitur Schema pada Program
        $program->update(['has_schema' => true]);

        // 2. Cari batch aktif dari program ini
        $batch = $program->batches()->where('status', 'dibuka')->first();
        if (!$batch) {
            $batch = $program->batches()->first();
        }

        // 3. Buat Skema Baru untuk Engineering
        $schema = ProgramSchema::create([
            'program_id'  => $program->id,
            'nama_skema'  => ['id' => 'Skema Khusus Alumni Magang (Engineering)', 'jp' => '元実習生特別エンジニアスキーム'],
            'slug'        => 'eng-alumni-magang',
            'tipe'        => 'scholar_partnership',
            'deskripsi'   => ['id' => 'Skema khusus untuk alumni magang Jepang (Ex-Kenshusei) yang ingin kembali ke Jepang dengan visa Engineer/Gijinkoku.'],
            'persyaratan' => ['id' => "- Memiliki sertifikat evaluasi magang 3 tahun atau Senmonkyu\n- Pendidikan minimal D3/S1 Teknik"],
            'harga'       => 10000000, // 10 Juta
            'status'      => 'aktif',
            'sort_order'  => 10,
        ]);

        $this->command->info('✓ Skema Baru Berhasil Dibuat: ' . $schema->getTranslation('nama_skema', 'id'));

        // 4. Buat Formulir Khusus untuk Skema ini
        $form = Form::create([
            'program_id'        => $program->id,
            'schema_id'         => $schema->id,
            'batch_id'          => $batch ? $batch->id : null,
            'title'             => ['id' => 'Formulir Pendaftaran Skema Khusus Alumni (Engineering)', 'jp' => '元実習生特別エンジニア申込フォーム'],
            'description'       => ['id' => 'Silakan isi data berikut secara lengkap. Semua role diatur ke "None" untuk membuktikan fitur Smart Guessing.'],
            'success_message'   => ['id' => 'Selamat, pendaftaran skema khusus alumni berhasil dikirim!'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        // 5. Tambahkan Field Pertanyaan ke Formulir (dengan ROLE = 'none' untuk membuktikan fallback kita)
        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Biodata Pendaftar', $sort++);
        $this->addField($form, $program, 'nama_pendaftar', 'text', 'Nama Lengkap Anda', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', true, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'tanggal_lahir', 'date', 'Tanggal Lahir Anda', true, $sort++);
        $this->addField($form, $program, 'kontak_email', 'email', 'Alamat Email Aktif', true, $sort++);
        $this->addField($form, $program, 'nomor_wa', 'phone', 'Nomor WhatsApp / HP', true, $sort++);
        $this->addField($form, $program, 'alamat_lengkap', 'textarea', 'Alamat Tinggal Sekarang', true, $sort++);

        // Bagian 2: Riwayat Magang
        $this->addSection($form, $program, 'Bagian 2: Riwayat Magang Jepang', $sort++);
        $this->addField($form, $program, 'bidang_magang_sebelumnya', 'text', 'Bidang Magang Sebelumnya di Jepang', true, $sort++);
        $this->addField($form, $program, 'nama_perusahaan_jepang', 'text', 'Nama Perusahaan/Kumiai Jepang Dahulu', true, $sort++);
        $this->addField($form, $program, 'durasi_magang', 'select', 'Durasi Magang Sebelumnya', true, $sort++, [
            ['label' => ['id' => '1 Tahun'], 'value' => '1 Tahun'],
            ['label' => ['id' => '3 Tahun'], 'value' => '3 Tahun'],
            ['label' => ['id' => '5 Tahun'], 'value' => '5 Tahun'],
        ]);
        $this->addField($form, $program, 'upload_sertifikat_senmonkyu', 'file', 'Upload Sertifikat Senmonkyu / Evaluasi Magang', true, $sort++, null, ['pdf', 'jpg', 'png']);

        $this->command->info('✓ Formulir Khusus & Field Pertanyaan Berhasil Dikonfigurasi!');

        // Re-enable AutoTranslate
        Program::enableAutoTranslate();
        ProgramSchema::enableAutoTranslate();
        FormField::enableAutoTranslate();
    }

    private function addSection(Form $form, Program $program, string $title, int $sort): void
    {
        FormField::create([
            'form_id' => $form->id,
            'program_id' => $program->id,
            'schema_id' => $form->schema_id,
            'label' => ['id' => $title, 'jp' => $title],
            'field_name' => 'section_' . uniqid(),
            'type' => 'section',
            'field_role' => 'none',
            'is_required' => 0,
            'sort_order' => $sort,
            'status' => 'aktif',
        ]);
    }

    private function addField(Form $form, Program $program, string $name, string $type, string $label, bool $req, int $sort, ?array $options = null, ?array $exts = null): void
    {
        $data = [
            'form_id' => $form->id,
            'program_id' => $program->id,
            'schema_id' => $form->schema_id,
            'label' => ['id' => $label, 'jp' => $label],
            'field_name' => $name,
            'type' => $type,
            'field_role' => 'none', // Sesuai request, set ke none semuanya!
            'is_required' => $req ? 1 : 0,
            'sort_order' => $sort,
            'status' => 'aktif',
        ];

        if ($options) {
            $data['options'] = $options;
        }

        if ($exts) {
            $data['accepted_file_types'] = $exts;
            $data['max_file_size'] = 2048;
        }

        FormField::create($data);
    }
}
