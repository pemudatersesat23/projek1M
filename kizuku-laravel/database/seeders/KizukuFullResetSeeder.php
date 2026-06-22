<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Program;
use App\Models\Batch;
use App\Models\ProgramSchema;
use App\Models\Form;
use App\Models\FormField;
use App\Models\ProgramSection;
use Carbon\Carbon;

/**
 * KizukuFullResetSeeder
 * ─────────────────────────────────────────────────────────────────────────────
 * Mereset dan mengisi ulang semua data Program, Batch, Program Schema,
 * Program Section (konten halaman sesuai permintaan), dan Form Builder.
 *
 * Jalankan seeder ini secara mandiri:
 *   php artisan db:seed --class=KizukuFullResetSeeder
 */
class KizukuFullResetSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan auto-translate agar seeding cepat
        Program::disableAutoTranslate();
        ProgramSchema::disableAutoTranslate();
        FormField::disableAutoTranslate();

        $this->command->info('🗑  Membersihkan data lama...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('applicant_dynamic_files')->truncate();
        DB::table('applicant_form_answers')->truncate();
        DB::table('applicant_documents')->truncate();
        DB::table('applicants')->truncate();
        DB::table('forms')->truncate();
        DB::table('form_fields')->truncate();
        DB::table('program_schemas')->truncate();
        DB::table('batches')->truncate();
        DB::table('program_sections')->truncate();
        DB::table('programs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('   ✓ Semua data lama berhasil dihapus.');
        $this->command->newLine();

        // ─────────────────────────────────────────────────────
        //  STEP 1: Buat 5 Program (dengan kolom lengkap)
        // ─────────────────────────────────────────────────────
        $this->command->info('📚 Membuat 5 Program...');
        $programs = $this->createPrograms();
        $this->command->info('   ✓ ' . count($programs) . ' program berhasil dibuat.');
        $this->command->newLine();

        // ─────────────────────────────────────────────────────
        //  STEP 1.5: Buat Konten Halaman Program (Program Sections)
        // ─────────────────────────────────────────────────────
        $this->command->info('📄 Membuat Konten Halaman Program (Program Sections)...');
        $this->createProgramSections($programs);
        $this->command->info('   ✓ Konten halaman program berhasil dibuat.');
        $this->command->newLine();

        // ─────────────────────────────────────────────────────
        //  STEP 2: Buat 5 Batch Aktif (1 per program)
        // ─────────────────────────────────────────────────────
        $this->command->info('📅 Membuat Batch Aktif...');
        $activeBatches = $this->createBatches($programs);
        $this->command->info('   ✓ Batch aktif berhasil dibuat.');
        $this->command->newLine();

        // ─────────────────────────────────────────────────────
        //  STEP 3: Buat Program Schema (data saja, tidak diterapkan dulu)
        // ─────────────────────────────────────────────────────
        $this->command->info('🗂  Membuat Program Schema...');
        $this->createSchemas($programs);
        $this->command->info('   ✓ Schema berhasil dibuat.');
        $this->command->newLine();

        // ─────────────────────────────────────────────────────
        //  STEP 4: Buat Form + Form Fields (Form Builder)
        // ─────────────────────────────────────────────────────
        $this->command->info('📋 Membuat Form & Form Fields...');
        $this->createFormsAndFields($programs, $activeBatches);
        $this->command->info('   ✓ Form Builder berhasil dikonfigurasi.');
        $this->command->newLine();

        // Aktifkan kembali auto-translate setelah seeding
        Program::enableAutoTranslate();
        ProgramSchema::enableAutoTranslate();
        FormField::enableAutoTranslate();

        $this->command->info('🎉 Selesai! Semua data berhasil dibuat.');
        $this->command->table(
            ['Tabel', 'Jumlah'],
            [
                ['programs',        Program::count()],
                ['program_sections', ProgramSection::count()],
                ['batches',         Batch::count()],
                ['program_schemas', ProgramSchema::count()],
                ['forms',           Form::count()],
                ['form_fields',     FormField::count()],
            ]
        );
    }

    private function createPrograms(): array
    {
        $programsData = [
            [
                'nama_program' => ['id' => 'Tokutei Ginou', 'jp' => '特定技能'],
                'slug'         => 'tokutei-ginou-tg',
                'deskripsi'    => [
                    'id' => 'Program persiapan kerja ke Jepang melalui jalur Tokutei Ginou untuk peserta yang ingin bekerja pada bidang kerja tertentu di Jepang.',
                    'jp' => '日本の特定の職種で働きたい参加者を対象とした、特定技能制度による日本での就労準備プログラム。'
                ],
                'target_peserta' => ['id' => 'Calon peserta yang ingin bekerja ke Jepang, lulusan sekolah/universitas yang berminat pada program kerja Jepang, serta bersedia mengikuti proses pelatihan & kontrak kerja.'],
                'materi'       => ['id' => 'Bahasa Jepang dasar hingga kebutuhan kerja, Persiapan ujian Tokutei Ginou, Persiapan interview, Budaya kerja Jepang, Persiapan dokumen.'],
                'focus'        => ['id' => 'Bahasa Jepang, Skill kerja, Persiapan ujian, Persiapan interview.'],
                'output'       => ['id' => 'Peserta siap mengikuti proses kerja ke Jepang.'],
                'durasi'       => ['id' => '5 Bulan'],
                'biaya'        => ['id' => 'Informasi biaya dapat dikonsultasikan melalui admin Kizuku International Academy.'],
                'benefit'      => ['id' => "Mendapatkan pelatihan bahasa Jepang\nMendapatkan persiapan skill kerja\nMendapatkan persiapan ujian\nMendapatkan persiapan interview\nMendapatkan pendampingan proses seleksi\nMendapatkan arahan bidang kerja sesuai minat"],
                'status'       => 'aktif',
                'sort_order'   => 1,
            ],
            [
                'nama_program' => ['id' => 'Engineering / Gijinkoku', 'jp' => '技術・人文知識・国際業務 (エンジニア)'],
                'slug'         => 'engineer-jepang-gijinkoku',
                'deskripsi'    => [
                    'id' => 'Program persiapan kerja profesional ke Jepang untuk lulusan bidang teknik atau peserta dengan latar belakang keahlian tertentu.',
                    'jp' => '技術分野の卒業生または特定の専門スキルを持つ参加者を対象とした、日本での専門的・技術的就労準備プログラム。'
                ],
                'target_peserta' => ['id' => 'Lulusan jurusan teknik, universitas, sekolah tinggi, atau peserta dengan keahlian profesional yang ingin bekerja di perusahaan Jepang sesuai bidang keahlian.'],
                'materi'       => ['id' => 'Bahasa Jepang profesional, Etika dan budaya kerja Jepang, Latihan interview, Persiapan CV, Persiapan dokumen, Simulasi seleksi kerja.'],
                'focus'        => ['id' => 'Bahasa Jepang profesional, Persiapan interview, Budaya kerja Jepang, Persiapan kerja profesional.'],
                'output'       => ['id' => 'Peserta siap mengikuti proses kerja profesional ke Jepang.'],
                'durasi'       => ['id' => '6–7 Bulan'],
                'biaya'        => ['id' => 'Informasi biaya dapat dikonsultasikan melalui admin Kizuku International Academy.'],
                'benefit'      => ['id' => "Pelatihan bahasa Jepang profesional\nPersiapan interview perusahaan Jepang\nPengenalan budaya kerja Jepang\nPendampingan proses seleksi\nPersiapan dokumen kerja"],
                'status'       => 'aktif',
                'sort_order'   => 2,
            ],
            [
                'nama_program' => ['id' => 'Kenshusei / Magang Jepang', 'jp' => '技能実習生 (研修生)'],
                'slug'         => 'magang-jepang-kenshusei',
                'deskripsi'    => [
                    'id' => 'Program persiapan magang ke Jepang bagi peserta yang ingin mengikuti proses pelatihan dan penempatan magang di Jepang.',
                    'jp' => '日本での研修およびインターンシップ配置プロセスに参加したい生徒を対象とした、日本インターンシップ準備プログラム。'
                ],
                'target_peserta' => ['id' => 'Calon peserta yang ingin mengikuti magang Jepang, bersedia mengikuti pelatihan sebelum berangkat, bersedia ditempatkan di seluruh Jepang, serta siap mengikuti proses seleksi.'],
                'materi'       => ['id' => 'Bahasa Jepang dasar, Persiapan seleksi, Pengenalan budaya kerja Jepang, Persiapan fisik dan mental, Persiapan dokumen, Pembekalan keberangkatan.'],
                'focus'        => ['id' => 'Persiapan magang Jepang, Bahasa Jepang dasar, Persiapan fisik dan mental, Persiapan seleksi, Persiapan keberangkatan.'],
                'output'       => ['id' => 'Peserta siap mengikuti proses magang Jepang.'],
                'durasi'       => ['id' => 'Menyesuaikan program dan batch'],
                'biaya'        => ['id' => 'Informasi biaya dapat dikonsultasikan melalui admin Kizuku International Academy.'],
                'benefit'      => ['id' => "Mendapatkan pelatihan persiapan magang\nMendapatkan pembekalan bahasa Jepang\nMendapatkan pendampingan proses seleksi\nMendapatkan arahan persiapan dokumen\nMendapatkan pembekalan sebelum keberangkatan"],
                'status'       => 'aktif',
                'sort_order'   => 3,
            ],
            [
                'nama_program' => ['id' => 'Kursus Bahasa Jepang', 'jp' => '日本語コース'],
                'slug'         => 'kursus-bahasa-jepang',
                'deskripsi'    => [
                    'id' => 'Program kursus bahasa Jepang untuk peserta yang ingin belajar bahasa Jepang dari dasar, meningkatkan kemampuan percakapan, atau mempersiapkan ujian JLPT/JFT.',
                    'jp' => '基礎から日本語を学びたい、会話力を向上させたい、またはJLPT/JFT試験の準備をしたい受講生向けの日本語学習コース。'
                ],
                'target_peserta' => ['id' => 'Peserta yang ingin belajar bahasa Jepang dari dasar, mempersiapkan JLPT/JFT, berminat mengikuti program kerja ke Jepang, atau ingin meningkatkan kemampuan percakapan.'],
                'materi'       => ['id' => 'Hiragana, Katakana, Kanji, Kaiwa / percakapan, Persiapan JLPT, Persiapan TG, Persiapan Engineering.'],
                'focus'        => ['id' => 'Percakapan bahasa Jepang, Hiragana, Katakana, Kanji, Persiapan JLPT/JFT, Persiapan Tokutei Ginou/Engineering.'],
                'output'       => ['id' => 'Peserta mengalami peningkatan kemampuan bahasa Jepang sesuai target kelas yang dipilih.'],
                'durasi'       => ['id' => '1 Bulan / Fleksibel sesuai target belajar'],
                'biaya'        => ['id' => 'Informasi biaya dapat dikonsultasikan melalui admin Kizuku International Academy.'],
                'benefit'      => ['id' => "Belajar bahasa Jepang secara terarah\nBisa memilih kelas sesuai target\nMendapatkan pembelajaran dasar hingga persiapan ujian\nMendapatkan pembelajaran untuk kebutuhan kerja ke Jepang\nBisa memilih sistem kelas online atau offline"],
                'status'       => 'aktif',
                'sort_order'   => 4,
            ],
            [
                'nama_program' => ['id' => 'Engineer Jepang / Ex-Internship', 'jp' => 'エンジニア経験者 (Ex-Internship)'],
                'slug'         => 'engineer-jepang-ex-internship',
                'deskripsi'    => [
                    'id' => 'Program untuk peserta ex-internship yang ingin meningkatkan kemampuan bahasa dan mempersiapkan peluang kerja kembali ke Jepang.',
                    'jp' => '語学力を向上させ、再び日本での就職機会を準備したい元実習生（インターンシップ経験者）向けのプログラム。'
                ],
                'target_peserta' => ['id' => 'Peserta ex-internship atau pernah magang Jepang, ingin kembali bekerja ke Jepang, memiliki pengalaman kerja, atau berasal dari jurusan/bidang teknik tertentu.'],
                'materi'       => ['id' => 'Bahasa Jepang lanjutan sesuai kebutuhan kerja, Persiapan interview, Persiapan CV, Persiapan dokumen, Penguatan pengalaman kerja, Pengenalan kembali budaya kerja Jepang.'],
                'focus'        => ['id' => 'Upgrade bahasa Jepang, Persiapan kerja kembali ke Jepang, Persiapan interview, Persiapan dokumen, Penguatan pengalaman kerja.'],
                'output'       => ['id' => 'Peserta siap mengikuti peluang kerja kembali ke Jepang.'],
                'durasi'       => ['id' => '3 Bulan'],
                'biaya'        => ['id' => 'Informasi biaya dapat dikonsultasikan melalui admin Kizuku International Academy.'],
                'benefit'      => ['id' => "Mendapatkan upgrade bahasa Jepang\nMendapatkan persiapan kerja kembali ke Jepang\nMendapatkan pendampingan persiapan dokumen\nMendapatkan persiapan interview\nMendapatkan arahan sesuai pengalaman kerja"],
                'status'       => 'aktif',
                'sort_order'   => 5,
            ],
        ];

        $created = [];
        foreach ($programsData as $data) {
            $program = Program::create($data);
            $created[$program->slug] = $program;
        }

        return $created;
    }

    private function createProgramSections(array $programs): void
    {
        // 1. Tokutei Ginou
        $tg = $programs['tokutei-ginou-tg'];
        ProgramSection::create([
            'program_id' => $tg->id,
            'type'       => 'info_grid',
            'title'      => ['id' => 'Detail Informasi Program', 'jp' => 'プログラム詳細情報'],
            'items'      => [
                ['title' => 'Target Peserta', 'description' => 'Calon peserta yang ingin bekerja ke Jepang, lulusan sekolah/universitas yang berminat, bersedia mengikuti pelatihan.'],
                ['title' => 'Fokus Utama', 'description' => 'Bahasa Jepang, Skill kerja, Persiapan ujian, Persiapan interview.'],
                ['title' => 'Output Program', 'description' => 'Peserta siap mengikuti proses kerja ke Jepang.'],
                ['title' => 'Durasi Program', 'description' => '5 bulan']
            ],
            'sort_order' => 1,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $tg->id,
            'type'       => 'checklist',
            'title'      => ['id' => 'Benefit Program', 'jp' => 'プログラムの特典'],
            'items'      => [
                ['title' => 'Mendapatkan pelatihan bahasa Jepang'],
                ['title' => 'Mendapatkan persiapan skill kerja'],
                ['title' => 'Mendapatkan persiapan ujian'],
                ['title' => 'Mendapatkan persiapan interview'],
                ['title' => 'Mendapatkan pendampingan proses seleksi'],
                ['title' => 'Mendapatkan arahan bidang kerja sesuai minat']
            ],
            'sort_order' => 2,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $tg->id,
            'type'       => 'cards',
            'title'      => ['id' => '10 Sektor Bidang Kerja', 'jp' => '特定技能10職種'],
            'description'=> ['id' => 'Berikut adalah bidang kerja Tokutei Ginou yang dapat Anda pilih:'],
            'items'      => [
                ['title' => 'Pengolahan Makanan'],
                ['title' => 'Pertanian'],
                ['title' => 'Perawatan Lansia / Kaigo'],
                ['title' => 'Restoran / Layanan Makanan'],
                ['title' => 'Perhotelan'],
                ['title' => 'Konstruksi'],
                ['title' => 'Manufaktur Mesin & Peralatan'],
                ['title' => 'Otomotif'],
                ['title' => 'Peternakan'],
                ['title' => 'Pembersihan Gedung']
            ],
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // 2. Engineering / Gijinkoku
        $eng = $programs['engineer-jepang-gijinkoku'];
        ProgramSection::create([
            'program_id' => $eng->id,
            'type'       => 'info_grid',
            'title'      => ['id' => 'Detail Informasi Program', 'jp' => 'プログラム詳細情報'],
            'items'      => [
                ['title' => 'Target Peserta', 'description' => 'Lulusan jurusan teknik, universitas, sekolah tinggi, atau yang ingin berkarir profesional di Jepang.'],
                ['title' => 'Fokus Utama', 'description' => 'Bahasa Jepang profesional, Persiapan interview, Budaya kerja Jepang, Persiapan kerja profesional.'],
                ['title' => 'Output Program', 'description' => 'Peserta siap mengikuti proses kerja profesional ke Jepang.'],
                ['title' => 'Durasi Program', 'description' => '6–7 bulan']
            ],
            'sort_order' => 1,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $eng->id,
            'type'       => 'checklist',
            'title'      => ['id' => 'Benefit Program', 'jp' => 'プログラムの特典'],
            'items'      => [
                ['title' => 'Pelatihan bahasa Jepang profesional'],
                ['title' => 'Persiapan interview perusahaan Jepang'],
                ['title' => 'Pengenalan budaya kerja Jepang'],
                ['title' => 'Pendampingan proses seleksi'],
                ['title' => 'Persiapan dokumen kerja']
            ],
            'sort_order' => 2,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $eng->id,
            'type'       => 'cards',
            'title'      => ['id' => 'Jurusan Terkait', 'jp' => '関連学科'],
            'items'      => [
                ['title' => 'Teknik Sipil'],
                ['title' => 'Teknik Arsitektur'],
                ['title' => 'Teknik Mesin Elektro'],
                ['title' => 'Teknik Mesin'],
                ['title' => 'Teknik Informatika'],
                ['title' => 'Lainnya']
            ],
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // 3. Kenshusei / Magang Jepang
        $ken = $programs['magang-jepang-kenshusei'];
        ProgramSection::create([
            'program_id' => $ken->id,
            'type'       => 'info_grid',
            'title'      => ['id' => 'Detail Informasi Program', 'jp' => 'プログラム詳細情報'],
            'items'      => [
                ['title' => 'Target Peserta', 'description' => 'Calon peserta magang yang bersedia pelatihan sebelum berangkat dan ditempatkan di seluruh Jepang.'],
                ['title' => 'Fokus Utama', 'description' => 'Persiapan magang Jepang, Bahasa Jepang dasar, Persiapan fisik dan mental, Persiapan seleksi/keberangkatan.'],
                ['title' => 'Output Program', 'description' => 'Peserta siap mengikuti proses magang Jepang.'],
                ['title' => 'Durasi Program', 'description' => 'Menyesuaikan program dan batch']
            ],
            'sort_order' => 1,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $ken->id,
            'type'       => 'checklist',
            'title'      => ['id' => 'Benefit Program', 'jp' => 'プログラムの特典'],
            'items'      => [
                ['title' => 'Mendapatkan pelatihan persiapan magang'],
                ['title' => 'Mendapatkan pembekalan bahasa Jepang'],
                ['title' => 'Mendapatkan pendampingan proses seleksi'],
                ['title' => 'Mendapatkan arahan persiapan dokumen'],
                ['title' => 'Mendapatkan pembekalan sebelum keberangkatan']
            ],
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        // 4. Kursus Bahasa Jepang
        $kur = $programs['kursus-bahasa-jepang'];
        ProgramSection::create([
            'program_id' => $kur->id,
            'type'       => 'info_grid',
            'title'      => ['id' => 'Detail Informasi Program', 'jp' => 'プログラム詳細情報'],
            'items'      => [
                ['title' => 'Target Peserta', 'description' => 'Peserta yang ingin belajar dari dasar, mempersiapkan JLPT/JFT, atau meningkatkan kemampuan percakapan.'],
                ['title' => 'Fokus Utama', 'description' => 'Percakapan, Hiragana, Katakana, Kanji, Persiapan JLPT/JFT, Persiapan TG & Engineering.'],
                ['title' => 'Output Program', 'description' => 'Peserta mengalami peningkatan kemampuan bahasa Jepang sesuai target kelas.'],
                ['title' => 'Durasi Program', 'description' => '1 bulan / fleksibel sesuai target belajar']
            ],
            'sort_order' => 1,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $kur->id,
            'type'       => 'checklist',
            'title'      => ['id' => 'Benefit Program', 'jp' => 'プログラムの特典'],
            'items'      => [
                ['title' => 'Belajar bahasa Jepang secara terarah'],
                ['title' => 'Bisa memilih kelas sesuai target'],
                ['title' => 'Mendapatkan pembelajaran dasar hingga persiapan ujian'],
                ['title' => 'Mendapatkan pembelajaran untuk kebutuhan kerja ke Jepang'],
                ['title' => 'Bisa memilih sistem kelas online atau offline']
            ],
            'sort_order' => 2,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $kur->id,
            'type'       => 'cards',
            'title'      => ['id' => 'Pilihan Kelas & Sistem', 'jp' => '選べるクラスとシステム'],
            'items'      => [
                ['title' => 'Kelas N5'],
                ['title' => 'Kelas N4'],
                ['title' => 'Kelas N3'],
                ['title' => 'Kaiwa / Percakapan'],
                ['title' => 'Persiapan JLPT'],
                ['title' => 'Persiapan TG / Engineering'],
                ['title' => 'Sistem Online / Offline']
            ],
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // 5. Engineer Jepang / Ex-Internship
        $ex = $programs['engineer-jepang-ex-internship'];
        ProgramSection::create([
            'program_id' => $ex->id,
            'type'       => 'info_grid',
            'title'      => ['id' => 'Detail Informasi Program', 'jp' => 'プログラム詳細情報'],
            'items'      => [
                ['title' => 'Target Peserta', 'description' => 'Peserta ex-internship atau pernah magang Jepang yang ingin kembali bekerja ke Jepang.'],
                ['title' => 'Fokus Utama', 'description' => 'Upgrade bahasa Jepang, Persiapan kerja kembali ke Jepang, Persiapan interview/dokumen, Penguatan pengalaman.'],
                ['title' => 'Output Program', 'description' => 'Peserta siap mengikuti peluang kerja kembali ke Jepang.'],
                ['title' => 'Durasi Program', 'description' => '3 bulan']
            ],
            'sort_order' => 1,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $ex->id,
            'type'       => 'checklist',
            'title'      => ['id' => 'Benefit Program', 'jp' => 'プログラムの特典'],
            'items'      => [
                ['title' => 'Mendapatkan upgrade bahasa Jepang'],
                ['title' => 'Mendapatkan persiapan kerja kembali ke Jepang'],
                ['title' => 'Mendapatkan pendampingan persiapan dokumen'],
                ['title' => 'Mendapatkan persiapan interview'],
                ['title' => 'Mendapatkan arahan sesuai pengalaman kerja']
            ],
            'sort_order' => 2,
            'is_active'  => true,
        ]);
        ProgramSection::create([
            'program_id' => $ex->id,
            'type'       => 'cards',
            'title'      => ['id' => 'Jurusan / Bidang Keahlian', 'jp' => '対象学科・職種'],
            'items'      => [
                ['title' => 'Teknik Mesin'],
                ['title' => 'Teknik Elektro'],
                ['title' => 'Teknik Sipil'],
                ['title' => 'Lainnya']
            ],
            'sort_order' => 3,
            'is_active'  => true,
        ]);
    }

    private function createBatches(array $programs): array
    {
        $activeBatches = [];

        foreach ($programs as $slug => $program) {
            // Kita buat 1 batch AKTIF (status: dibuka)
            $activeBatch = $program->batches()->create([
                'nama_batch'    => ['id' => 'Batch ' . strtoupper(str_replace('-tg', '', str_replace('engineer-jepang-', '', str_replace('magang-jepang-', '', $slug)))) . ' Aktif - 2026', 'jp' => 'アクティブバッチ 2026年'],
                'status'        => 'dibuka',
                'tanggal_buka'  => Carbon::now()->subDays(5),
                'tanggal_tutup' => Carbon::now()->addDays(25),
                'tanggal_mulai' => Carbon::now()->addDays(35),
                'tanggal_selesai' => Carbon::now()->addMonths(6),
                'kuota'         => 50,
            ]);

            // Tambahkan juga 1 batch "akan datang" (sesuai request: "ada yang statusnya aktif dan akan datang")
            $program->batches()->create([
                'nama_batch'    => ['id' => 'Batch ' . strtoupper(str_replace('-tg', '', str_replace('engineer-jepang-', '', str_replace('magang-jepang-', '', $slug)))) . ' Mendatang - 2026/2027', 'jp' => '次回バッチ 2026/2027年'],
                'status'        => 'akan_dibuka',
                'tanggal_buka'  => Carbon::now()->addDays(30),
                'tanggal_tutup' => Carbon::now()->addDays(60),
                'tanggal_mulai' => Carbon::now()->addDays(70),
                'tanggal_selesai' => Carbon::now()->addMonths(6)->addDays(70),
                'kuota'         => 50,
            ]);

            $activeBatches[$slug] = $activeBatch;
        }

        return $activeBatches;
    }

    private function createSchemas(array $programs): void
    {
        $schemasData = [
            'tokutei-ginou-tg' => [
                [
                    'nama_skema'  => ['id' => 'Skema Reguler Mandiri', 'jp' => '自費一般スキーム'],
                    'slug'        => 'tg-reguler-mandiri',
                    'tipe'        => 'reguler',
                    'deskripsi'   => ['id' => 'Peserta membiayai sendiri biaya persiapan pelatihan bahasa & skill. Biaya dokumen keberangkatan dapat dibayar lunas secara mandiri.'],
                    'persyaratan' => ['id' => "- Lulusan SMA/SMK/D3/S1\n- Usia 18-35 tahun\n- Sehat jasmani & rohani"],
                    'harga'       => 15000000,
                    'status'      => 'aktif',
                    'sort_order'  => 1,
                ],
                [
                    'nama_skema'  => ['id' => 'Skema Dana Talangan Swasta', 'jp' => '立替融資スキーム'],
                    'slug'        => 'tg-dana-talangan',
                    'tipe'        => 'scholar_partnership',
                    'deskripsi'   => ['id' => 'Sebagian besar biaya pelatihan dan pengurusan visa dipinjamkan oleh LPK/Mitra Finansial, dan dicicil setelah bekerja di Jepang.'],
                    'persyaratan' => ['id' => "- Lulus seleksi berkas & wawancara internal\n- Memiliki penjamin orang tua/wali\n- Bersedia mengikuti aturan potongan gaji resmi"],
                    'harga'       => 0,
                    'status'      => 'aktif',
                    'sort_order'  => 2,
                ],
                [
                    'nama_skema'  => ['id' => 'Skema Beasiswa Mitra Penuh', 'jp' => '全額無償奨学金'],
                    'slug'        => 'tg-beasiswa-penuh',
                    'tipe'        => 'beasiswa',
                    'deskripsi'   => ['id' => 'Seluruh biaya dari pelatihan hingga pemberangkatan ditanggung penuh oleh Asosiasi Penerima (Kumiai) Jepang tanpa potongan gaji.'],
                    'persyaratan' => ['id' => "- Lulusan SMK Kesehatan/Keperawatan (khusus Kaigo) atau memiliki keahlian khusus\n- Lolos wawancara user Jepang langsung"],
                    'harga'       => 0,
                    'status'      => 'aktif',
                    'sort_order'  => 3,
                ],
            ],
            'engineer-jepang-gijinkoku' => [
                [
                    'nama_skema'  => ['id' => 'Skema Gijinkoku Mandiri', 'jp' => '一般技術ビザスキーム'],
                    'slug'        => 'gjk-reguler',
                    'tipe'        => 'reguler',
                    'deskripsi'   => ['id' => 'Pelatihan bahasa Jepang tingkat menengah & bimbingan pembuatan CV profesional jepang secara mandiri.'],
                    'persyaratan' => ['id' => "- Lulusan D3/S1 Teknik\n- IPK min. 2.75\n- Usia maksimal 35 tahun"],
                    'harga'       => 18000000,
                    'status'      => 'aktif',
                    'sort_order'  => 1,
                ],
                [
                    'nama_skema'  => ['id' => 'Skema Rekrutmen Sponsor Perusahaan', 'jp' => 'スポンサー企業マッチング'],
                    'slug'        => 'gjk-kemitraan',
                    'tipe'        => 'scholar_partnership',
                    'deskripsi'   => ['id' => 'Biaya pelatihan dan akomodasi ditanggung penuh oleh perusahaan penerima di Jepang sebagai komitmen kerja minimal 3 tahun.'],
                    'persyaratan' => ['id' => "- Pengalaman kerja sebagai Engineer di Indonesia min. 1 tahun\n- Lolos technical test dari sponsor Jepang"],
                    'harga'       => 0,
                    'status'      => 'aktif',
                    'sort_order'  => 2,
                ],
            ],
            'magang-jepang-kenshusei' => [
                [
                    'nama_skema'  => ['id' => 'Skema Subsidi Pemerintah (IM Japan)', 'jp' => '政府系技能実習制度'],
                    'slug'        => 'kns-subsidi-penuh',
                    'tipe'        => 'beasiswa',
                    'deskripsi'   => ['id' => 'Program resmi kolaborasi Depnaker Indonesia & IM Japan. Biaya pendaftaran dan pelatihan awal disubsidi pemerintah.'],
                    'persyaratan' => ['id' => "- Lulus tes fisik lari 3km, push up, sit up\n- Tinggi badan min 160 cm (Pria)\n- Tidak buta warna"],
                    'harga'       => 0,
                    'status'      => 'aktif',
                    'sort_order'  => 1,
                ],
            ],
            'kursus-bahasa-jepang' => [
                [
                    'nama_skema'  => ['id' => 'Paket Kelas Dasar N5-N4', 'jp' => '基礎日本語クラス'],
                    'slug'        => 'kursus-dasar',
                    'tipe'        => 'reguler',
                    'deskripsi'   => ['id' => 'Kelas reguler untuk pemula, mempelajari huruf Hiragana, Katakana, Kanji dasar, dan pola kalimat praktis sehari-hari.'],
                    'persyaratan' => ['id' => "- Terbuka untuk umum\n- Mengisi form pendaftaran"],
                    'harga'       => 2000000,
                    'status'      => 'aktif',
                    'sort_order'  => 1,
                ],
            ],
            'engineer-jepang-ex-internship' => [
                [
                    'nama_skema'  => ['id' => 'Skema Matching Alumni Cepat', 'jp' => '帰国実習生特別スキーム'],
                    'slug'        => 'ex-matching-cepat',
                    'tipe'        => 'reguler',
                    'deskripsi'   => ['id' => 'Pencocokan berkas cepat untuk alumni magang yang ingin kembali ke Jepang. Menghubungkan langsung dengan agen rekrutmen Jepang terpercaya.'],
                    'persyaratan' => ['id' => "- Sertifikat Senmonkyu / Sertifikat Evaluasi Magang 3 tahun\n- Usia maks. 38 tahun"],
                    'harga'       => 5000000,
                    'status'      => 'aktif',
                    'sort_order'  => 1,
                ],
            ],
        ];

        foreach ($schemasData as $slug => $schemas) {
            $program = $programs[$slug];
            foreach ($schemas as $schemaData) {
                $schemaData['program_id'] = $program->id;
                ProgramSchema::create($schemaData);
            }
        }
    }

    private function createFormsAndFields(array $programs, array $activeBatches): void
    {
        // 1. Form Program Tokutei Ginou / TG
        $this->buildFormTokuteiGinou($programs['tokutei-ginou-tg'], $activeBatches['tokutei-ginou-tg']);

        // 2. Form Program Engineering / Gijinkoku
        $this->buildFormEngineering($programs['engineer-jepang-gijinkoku'], $activeBatches['engineer-jepang-gijinkoku']);

        // 3. Form Program Kenshusei / Magang Jepang
        $this->buildFormKenshusei($programs['magang-jepang-kenshusei'], $activeBatches['magang-jepang-kenshusei']);

        // 4. Form Program Kursus Bahasa Jepang
        $this->buildFormKursus($programs['kursus-bahasa-jepang'], $activeBatches['kursus-bahasa-jepang']);

        // 5. Form Program Engineer Jepang / Ex-Internship
        $this->buildFormExInternship($programs['engineer-jepang-ex-internship'], $activeBatches['engineer-jepang-ex-internship']);
    }

    private function buildFormTokuteiGinou(Program $program, Batch $batch): void
    {
        $form = Form::create([
            'program_id'        => $program->id,
            'batch_id'          => $batch->id,
            'title'             => ['id' => 'Formulir Program Tokutei Ginou / TG', 'jp' => '特定技能（TG）申込フォーム'],
            'description'       => ['id' => 'Harap isi formulir ini dengan lengkap untuk mendaftar Program Tokutei Ginou.'],
            'success_message'   => ['id' => 'Pendaftaran Tokutei Ginou berhasil disimpan! Tim kami akan menghubungi Anda.'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Data Pribadi', $sort++);
        $this->addField($form, $program, 'nama_lengkap', 'text', 'Nama Lengkap sesuai KTP/Paspor', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', true, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'ttl', 'text', 'Tempat & Tanggal Lahir', true, $sort++);
        $this->addField($form, $program, 'alamat_domisili', 'textarea', 'Alamat Domisili Lengkap', true, $sort++);
        $this->addField($form, $program, 'nomor_hp', 'phone', 'Nomor HP / WhatsApp Aktif', true, $sort++);
        $this->addField($form, $program, 'email', 'email', 'Email Aktif', true, $sort++);

        // Bagian 2: Pendidikan & Keahlian
        $this->addSection($form, $program, 'Bagian 2: Pendidikan & Keahlian', $sort++);
        $this->addField($form, $program, 'pendidikan_terakhir', 'select', 'Pendidikan Terakhir', true, $sort++, [
            ['label' => ['id' => 'SMA/SMK/Sederajat'], 'value' => 'SMA/SMK/Sederajat'],
            ['label' => ['id' => 'Diploma D3'], 'value' => 'Diploma D3'],
            ['label' => ['id' => 'Sarjana S1'], 'value' => 'Sarjana S1'],
        ]);
        $this->addField($form, $program, 'jurusan', 'text', 'Jurusan / Program Studi', true, $sort++);
        $this->addField($form, $program, 'nama_sekolah', 'text', 'Nama Sekolah/Universitas', true, $sort++);
        $this->addField($form, $program, 'tahun_lulus', 'number', 'Tahun Lulus', true, $sort++);
        $this->addField($form, $program, 'kemampuan_bahasa', 'select', 'Kemampuan Bahasa Jepang', true, $sort++, [
            ['label' => ['id' => 'Belum Belajar'], 'value' => 'Belum Belajar'],
            ['label' => ['id' => 'Sedang Belajar'], 'value' => 'Sedang Belajar'],
            ['label' => ['id' => 'JLPT N5 / Setara'], 'value' => 'JLPT N5 / Setara'],
            ['label' => ['id' => 'JLPT N4 / Setara'], 'value' => 'JLPT N4 / Setara'],
            ['label' => ['id' => 'JLPT N3 atau lebih'], 'value' => 'JLPT N3 atau lebih'],
        ]);
        $this->addField($form, $program, 'bidang_tg', 'select', 'Bidang TG yang diminati', true, $sort++, [
            ['label' => ['id' => 'Pengolahan Makanan'], 'value' => 'Pengolahan Makanan'],
            ['label' => ['id' => 'Pertanian'], 'value' => 'Pertanian'],
            ['label' => ['id' => 'Perawatan Lansia / Kaigo'], 'value' => 'Perawatan Lansia / Kaigo'],
            ['label' => ['id' => 'Restoran / Layanan Makanan'], 'value' => 'Restoran / Layanan Makanan'],
            ['label' => ['id' => 'Perhotelan'], 'value' => 'Perhotelan'],
            ['label' => ['id' => 'Konstruksi'], 'value' => 'Konstruksi'],
            ['label' => ['id' => 'Manufaktur Mesin & Peralatan'], 'value' => 'Manufaktur Mesin & Peralatan'],
            ['label' => ['id' => 'Otomotif'], 'value' => 'Otomotif'],
            ['label' => ['id' => 'Peternakan'], 'value' => 'Peternakan'],
            ['label' => ['id' => 'Pembersihan Gedung'], 'value' => 'Pembersihan Gedung'],
        ]);
        $this->addField($form, $program, 'pengalaman_kerja', 'textarea', 'Pengalaman Kerja', true, $sort++);

        // Bagian 3: Dokumen
        $this->addSection($form, $program, 'Bagian 3: Dokumen', $sort++);
        $this->addField($form, $program, 'upload_ktp', 'file', 'Upload KTP', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_kk', 'file', 'Upload KK', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_foto', 'file', 'Upload Foto Terbaru', true, $sort++, null, ['jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'upload_ijazah', 'file', 'Upload Ijazah', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_sertifikat', 'file', 'Upload Sertifikat JLPT/Keterampilan', false, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'bukti_follow', 'file', 'Bukti Follow IG & TikTok @kizuku.academy', true, $sort++, null, ['jpg', 'jpeg', 'png']);

        // Bagian 4: Informasi Tambahan
        $this->addSection($form, $program, 'Bagian 4: Informasi Tambahan', $sort++);
        $this->addField($form, $program, 'bersedia_kontrak', 'radio', 'Bersedia kontrak minimal 3 tahun?', true, $sort++, [
            ['label' => ['id' => 'Ya, bersedia'], 'value' => 'Ya'],
            ['label' => ['id' => 'Tidak bersedia'], 'value' => 'Tidak']
        ]);
        $this->addField($form, $program, 'motivasi', 'textarea', 'Motivasi bekerja di Jepang', true, $sort++);
        $this->addField($form, $program, 'pernah_magang', 'radio', 'Pernah ikut magang Jepang sebelumnya?', true, $sort++, [
            ['label' => ['id' => 'Ya, pernah'], 'value' => 'Ya'],
            ['label' => ['id' => 'Belum pernah'], 'value' => 'Tidak']
        ]);

        // Bagian 5: Pernyataan
        $this->addSection($form, $program, 'Bagian 5: Pernyataan', $sort++);
        $this->addField($form, $program, 'pernyataan_data', 'radio', 'Pernyataan kebenaran data', true, $sort++, [
            ['label' => ['id' => 'Saya menyatakan seluruh data yang diisi adalah benar'], 'value' => 'Ya']
        ]);
        $this->addField($form, $program, 'bersedia_seleksi', 'radio', 'Bersedia ikut seleksi & pelatihan', true, $sort++, [
            ['label' => ['id' => 'Ya, bersedia'], 'value' => 'Ya'],
            ['label' => ['id' => 'Tidak bersedia'], 'value' => 'Tidak']
        ]);
        $this->addField($form, $program, 'siap_seleksi_makassar', 'radio', 'Siap seleksi offline di Makassar?', true, $sort++, [
            ['label' => ['id' => 'Ya, siap'], 'value' => 'Ya'],
            ['label' => ['id' => 'Tidak siap'], 'value' => 'Tidak']
        ]);
    }

    private function buildFormEngineering(Program $program, Batch $batch): void
    {
        $form = Form::create([
            'program_id'        => $program->id,
            'batch_id'          => $batch->id,
            'title'             => ['id' => 'Formulir Program Engineering / Gijinkoku', 'jp' => '技術・人文知識・国際業務 申込フォーム'],
            'description'       => ['id' => 'Harap isi formulir pendaftaran program Engineer profesional Jepang.'],
            'success_message'   => ['id' => 'Pendaftaran Program Engineering berhasil diterima!'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Data Pribadi', $sort++);
        $this->addField($form, $program, 'nama_lengkap', 'text', 'Nama Lengkap sesuai KTP/Paspor', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', true, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'ttl', 'text', 'Tempat & Tanggal Lahir', true, $sort++);
        $this->addField($form, $program, 'alamat_domisili', 'textarea', 'Alamat Domisili Lengkap', true, $sort++);
        $this->addField($form, $program, 'nomor_hp', 'phone', 'Nomor HP / WhatsApp Aktif', true, $sort++);
        $this->addField($form, $program, 'email', 'email', 'Email Aktif', true, $sort++);

        // Bagian 2: Pendidikan & Keahlian
        $this->addSection($form, $program, 'Bagian 2: Pendidikan & Keahlian', $sort++);
        $this->addField($form, $program, 'jurusan', 'select', 'Jurusan', true, $sort++, [
            ['label' => ['id' => 'Teknik Sipil'], 'value' => 'Teknik Sipil'],
            ['label' => ['id' => 'Teknik Arsitektur'], 'value' => 'Teknik Arsitektur'],
            ['label' => ['id' => 'Teknik Mesin Elektro'], 'value' => 'Teknik Mesin Elektro'],
            ['label' => ['id' => 'Teknik Mesin'], 'value' => 'Teknik Mesin'],
            ['label' => ['id' => 'Teknik Informatika'], 'value' => 'Teknik Informatika'],
            ['label' => ['id' => 'Lainnya'], 'value' => 'Lainnya'],
        ]);
        $this->addField($form, $program, 'nama_univ', 'text', 'Nama Universitas', true, $sort++);
        $this->addField($form, $program, 'tahun_lulus', 'number', 'Tahun Lulus', true, $sort++);
        $this->addField($form, $program, 'level_bahasa', 'select', 'Level Bahasa Jepang', true, $sort++, [
            ['label' => ['id' => 'Belum Belajar'], 'value' => 'Belum Belajar'],
            ['label' => ['id' => 'JLPT N5 / Setara'], 'value' => 'JLPT N5'],
            ['label' => ['id' => 'JLPT N4 / Setara'], 'value' => 'JLPT N4'],
            ['label' => ['id' => 'JLPT N3 / Setara'], 'value' => 'JLPT N3'],
            ['label' => ['id' => 'JLPT N2 atau lebih'], 'value' => 'JLPT N2 atau lebih'],
        ]);
        $this->addField($form, $program, 'pengalaman_kerja', 'textarea', 'Pengalaman Kerja', true, $sort++);

        // Bagian 3: Dokumen
        $this->addSection($form, $program, 'Bagian 3: Dokumen', $sort++);
        $this->addField($form, $program, 'upload_cv', 'file', 'Upload CV', true, $sort++, null, ['pdf', 'doc', 'docx']);
        $this->addField($form, $program, 'upload_ktp', 'file', 'Upload KTP', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_kk', 'file', 'Upload KK', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_foto', 'file', 'Upload Foto', true, $sort++, null, ['jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'upload_ijazah', 'file', 'Upload Ijazah', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_transkrip', 'file', 'Upload Transkrip', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_sertifikat', 'file', 'Upload Sertifikat JLPT/Keterampilan', false, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'bukti_follow', 'file', 'Bukti Follow IG & TikTok', true, $sort++, null, ['jpg', 'jpeg', 'png']);

        // Bagian 4: Informasi Tambahan
        $this->addSection($form, $program, 'Bagian 4: Informasi Tambahan', $sort++);
        $this->addField($form, $program, 'motivasi', 'textarea', 'Motivasi', true, $sort++);
        $this->addField($form, $program, 'pernah_magang', 'radio', 'Pernah ikut magang Jepang?', true, $sort++, [
            ['label' => ['id' => 'Ya, pernah'], 'value' => 'Ya'],
            ['label' => ['id' => 'Belum pernah'], 'value' => 'Tidak']
        ]);

        // Bagian 5: Pernyataan
        $this->addSection($form, $program, 'Bagian 5: Pernyataan', $sort++);
        $this->addField($form, $program, 'pernyataan_data', 'radio', 'Pernyataan kebenaran data', true, $sort++, [
            ['label' => ['id' => 'Saya menyatakan seluruh data yang diisi adalah benar dan valid'], 'value' => 'Ya']
        ]);
    }

    private function buildFormKenshusei(Program $program, Batch $batch): void
    {
        $form = Form::create([
            'program_id'        => $program->id,
            'batch_id'          => $batch->id,
            'title'             => ['id' => 'Formulir Program Kenshusei / Magang Jepang', 'jp' => '技能実習生（研修）申込フォーム'],
            'description'       => ['id' => 'Harap isi formulir pendaftaran program Pemagangan Teknis Jepang.'],
            'success_message'   => ['id' => 'Pendaftaran Magang Jepang berhasil dikirim!'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Data Pribadi', $sort++);
        $this->addField($form, $program, 'nama_lengkap', 'text', 'Nama Lengkap', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', true, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'ttl', 'text', 'TTL', true, $sort++);
        $this->addField($form, $program, 'alamat', 'textarea', 'Alamat', true, $sort++);
        $this->addField($form, $program, 'no_hp', 'phone', 'No HP', true, $sort++);
        $this->addField($form, $program, 'email', 'email', 'Email', true, $sort++);
        $this->addField($form, $program, 'tinggi_berat', 'text', 'Tinggi & Berat Badan', true, $sort++);
        $this->addField($form, $program, 'status_pernikahan', 'select', 'Status Pernikahan', true, $sort++, [
            ['label' => ['id' => 'Belum Menikah'], 'value' => 'Belum Menikah'],
            ['label' => ['id' => 'Menikah'], 'value' => 'Menikah'],
        ]);

        // Bagian 2: Pendidikan & Keahlian
        $this->addSection($form, $program, 'Bagian 2: Pendidikan & Keahlian', $sort++);
        $this->addField($form, $program, 'pendidikan_terakhir', 'select', 'Pendidikan Terakhir', true, $sort++, [
            ['label' => ['id' => 'SMA / SMK'], 'value' => 'SMA / SMK'],
            ['label' => ['id' => 'Diploma D3'], 'value' => 'Diploma D3'],
            ['label' => ['id' => 'Sarjana S1'], 'value' => 'Sarjana S1'],
        ]);
        $this->addField($form, $program, 'jurusan', 'text', 'Jurusan jika ada', false, $sort++);
        $this->addField($form, $program, 'tahun_lulus', 'number', 'Tahun Lulus', false, $sort++);
        $this->addField($form, $program, 'bidang_magang', 'select', 'Bidang Magang diminati', true, $sort++, [
            ['label' => ['id' => 'Pengolahan Makanan'], 'value' => 'Pengolahan Makanan'],
            ['label' => ['id' => 'Pertanian'], 'value' => 'Pertanian'],
            ['label' => ['id' => 'Konstruksi'], 'value' => 'Konstruksi'],
            ['label' => ['id' => 'Manufaktur/Pabrik'], 'value' => 'Manufaktur/Pabrik'],
        ]);
        $this->addField($form, $program, 'level_bahasa', 'select', 'Level Bahasa Jepang', true, $sort++, [
            ['label' => ['id' => 'Belum Belajar'], 'value' => 'Belum Belajar'],
            ['label' => ['id' => 'Sedang Belajar'], 'value' => 'Sedang Belajar'],
            ['label' => ['id' => 'N5'], 'value' => 'N5'],
            ['label' => ['id' => 'N4 ke atas'], 'value' => 'N4 ke atas'],
        ]);

        // Bagian 3: Dokumen
        $this->addSection($form, $program, 'Bagian 3: Dokumen', $sort++);
        $this->addField($form, $program, 'upload_ktp', 'file', 'Upload KTP', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_kk', 'file', 'Upload KK', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_ijazah', 'file', 'Upload Ijazah', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'upload_foto', 'file', 'Upload Foto', true, $sort++, null, ['jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'upload_sertifikat', 'file', 'Sertifikat Bahasa Jepang jika ada', false, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);
        $this->addField($form, $program, 'bukti_follow', 'file', 'Bukti Follow IG & TikTok', true, $sort++, null, ['jpg', 'jpeg', 'png']);

        // Bagian 4: Informasi Tambahan
        $this->addSection($form, $program, 'Bagian 4: Informasi Tambahan', $sort++);
        $this->addField($form, $program, 'bersedia_pelatihan', 'radio', 'Bersedia pelatihan sebelum berangkat?', true, $sort++, [
            ['label' => ['id' => 'Ya, bersedia'], 'value' => 'Ya'],
            ['label' => ['id' => 'Tidak bersedia'], 'value' => 'Tidak']
        ]);
        $this->addField($form, $program, 'bersedia_ditempatkan', 'radio', 'Bersedia ditempatkan di seluruh Jepang?', true, $sort++, [
            ['label' => ['id' => 'Ya, bersedia'], 'value' => 'Ya'],
            ['label' => ['id' => 'Tidak bersedia'], 'value' => 'Tidak']
        ]);
        $this->addField($form, $program, 'motivasi', 'textarea', 'Motivasi', true, $sort++);

        // Bagian 5: Pernyataan
        $this->addSection($form, $program, 'Bagian 5: Pernyataan', $sort++);
        $this->addField($form, $program, 'pernyataan_data', 'radio', 'Pernyataan kebenaran data', true, $sort++, [
            ['label' => ['id' => 'Saya menyatakan seluruh data yang diisi adalah benar'], 'value' => 'Ya']
        ]);
    }

    private function buildFormKursus(Program $program, Batch $batch): void
    {
        $form = Form::create([
            'program_id'        => $program->id,
            'batch_id'          => $batch->id,
            'title'             => ['id' => 'Formulir Pendaftaran Kursus Bahasa Jepang', 'jp' => '日本語コース申込フォーム'],
            'description'       => ['id' => 'Silakan isi formulir ini untuk pendaftaran kelas Kursus Bahasa Jepang.'],
            'success_message'   => ['id' => 'Pendaftaran Kursus Bahasa Jepang berhasil disimpan!'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Data Pribadi', $sort++);
        $this->addField($form, $program, 'nama_lengkap', 'text', 'Nama Lengkap', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', false, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'ttl', 'text', 'TTL', false, $sort++);
        $this->addField($form, $program, 'alamat', 'textarea', 'Alamat', false, $sort++);
        $this->addField($form, $program, 'no_hp', 'phone', 'No HP', true, $sort++);
        $this->addField($form, $program, 'email', 'email', 'Email', true, $sort++);

        // Bagian 2: Pilihan Program
        $this->addSection($form, $program, 'Bagian 2: Pilihan Program', $sort++);
        $this->addField($form, $program, 'pilihan_kelas', 'select', 'Pilihan Kelas', false, $sort++, [
            ['label' => ['id' => 'Kelas N5'], 'value' => 'Kelas N5'],
            ['label' => ['id' => 'N4'], 'value' => 'N4'],
            ['label' => ['id' => 'N3'], 'value' => 'N3'],
            ['label' => ['id' => 'Kaiwa'], 'value' => 'Kaiwa'],
            ['label' => ['id' => 'Persiapan JLPT'], 'value' => 'Persiapan JLPT'],
            ['label' => ['id' => 'Persiapan TG'], 'value' => 'Persiapan TG'],
            ['label' => ['id' => 'Persiapan Engineering'], 'value' => 'Persiapan Engineering'],
        ]);
        $this->addField($form, $program, 'sistem_kelas', 'radio', 'Sistem Kelas', false, $sort++, [
            ['label' => ['id' => 'Online'], 'value' => 'Online'],
            ['label' => ['id' => 'Offline'], 'value' => 'Offline']
        ]);
        $this->addField($form, $program, 'level_saat_ini', 'select', 'Level Saat Ini', true, $sort++, [
            ['label' => ['id' => 'Belum pernah belajar'], 'value' => 'Belum pernah belajar'],
            ['label' => ['id' => 'N5'], 'value' => 'N5'],
            ['label' => ['id' => 'N4'], 'value' => 'N4'],
            ['label' => ['id' => 'N3 atau lebih'], 'value' => 'N3 atau lebih'],
        ]);

        // Bagian 3: Tujuan Belajar
        $this->addSection($form, $program, 'Bagian 3: Tujuan Belajar', $sort++);
        $this->addField($form, $program, 'tujuan_kursus', 'textarea', 'Tujuan mengikuti kursus', true, $sort++);
        $this->addField($form, $program, 'target_jlpt', 'text', 'Target JLPT / Target Keberangkatan', false, $sort++);

        // Bagian 4: Pernyataan
        $this->addSection($form, $program, 'Bagian 4: Pernyataan', $sort++);
        $this->addField($form, $program, 'bersedia_aturan', 'radio', 'Bersedia mengikuti aturan kelas', true, $sort++, [
            ['label' => ['id' => 'Ya, saya bersedia mengikuti semua tata tertib kelas'], 'value' => 'Ya']
        ]);
    }

    private function buildFormExInternship(Program $program, Batch $batch): void
    {
        $form = Form::create([
            'program_id'        => $program->id,
            'batch_id'          => $batch->id,
            'title'             => ['id' => 'Formulir Program Engineer Jepang / Ex-Internship', 'jp' => 'エンジニア経験者（Ex-Internship）申込フォーム'],
            'description'       => ['id' => 'Harap isi formulir ini untuk pendaftaran eks-magang Jepang yang ingin berkarir sebagai Engineer.'],
            'success_message'   => ['id' => 'Pendaftaran program Ex-Internship berhasil disimpan!'],
            'status'            => 'published',
            'is_active'         => true,
            'accepts_responses' => true,
            'version'           => 1,
            'published_at'      => now(),
        ]);

        $sort = 1;

        // Bagian 1: Data Pribadi
        $this->addSection($form, $program, 'Bagian 1: Data Pribadi', $sort++);
        $this->addField($form, $program, 'nama_lengkap', 'text', 'Nama Lengkap sesuai KTP/Paspor', true, $sort++);
        $this->addField($form, $program, 'jenis_kelamin', 'radio', 'Jenis Kelamin', true, $sort++, [
            ['label' => ['id' => 'Laki-laki'], 'value' => 'Laki-laki'],
            ['label' => ['id' => 'Perempuan'], 'value' => 'Perempuan']
        ]);
        $this->addField($form, $program, 'ttl', 'text', 'Tempat & Tanggal Lahir', true, $sort++);
        $this->addField($form, $program, 'alamat_domisili', 'textarea', 'Alamat Domisili Lengkap', true, $sort++);
        $this->addField($form, $program, 'nomor_hp', 'phone', 'Nomor HP / WhatsApp Aktif', true, $sort++);
        $this->addField($form, $program, 'email', 'email', 'Email Aktif', true, $sort++);

        // Bagian 2: Pendidikan & Keahlian
        $this->addSection($form, $program, 'Bagian 2: Pendidikan & Keahlian', $sort++);
        $this->addField($form, $program, 'jurusan', 'select', 'Jurusan / Program Studi', true, $sort++, [
            ['label' => ['id' => 'Teknik Mesin'], 'value' => 'Teknik Mesin'],
            ['label' => ['id' => 'Teknik Elektro'], 'value' => 'Teknik Elektro'],
            ['label' => ['id' => 'Teknik Sipil'], 'value' => 'Teknik Sipil'],
            ['label' => ['id' => 'Lainnya'], 'value' => 'Lainnya'],
        ]);
        $this->addField($form, $program, 'nama_sekolah', 'text', 'Nama Sekolah/Universitas', true, $sort++);
        $this->addField($form, $program, 'tahun_lulus', 'number', 'Tahun Lulus', true, $sort++);
        $this->addField($form, $program, 'kemampuan_bahasa', 'select', 'Kemampuan Bahasa Jepang', true, $sort++, [
            ['label' => ['id' => 'Belum belajar'], 'value' => 'Belum belajar'],
            ['label' => ['id' => 'Sedang belajar'], 'value' => 'Sedang belajar'],
            ['label' => ['id' => 'JLPT N5'], 'value' => 'JLPT N5'],
            ['label' => ['id' => 'JLPT N4'], 'value' => 'JLPT N4'],
            ['label' => ['id' => 'JLPT N3 atau lebih'], 'value' => 'JLPT N3 atau lebih'],
        ]);
        $this->addField($form, $program, 'pengalaman_kerja', 'textarea', 'Pengalaman Kerja', false, $sort++);

        // Bagian 3: Dokumen Pendukung
        $this->addSection($form, $program, 'Bagian 3: Dokumen Pendukung', $sort++);
        $this->addField($form, $program, 'upload_cv', 'file', 'Upload CV PDF', true, $sort++, null, ['pdf']);
        $this->addField($form, $program, 'upload_ijazah', 'file', 'Upload Ijazah PDF/JPG', true, $sort++, null, ['pdf', 'jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'upload_transkrip', 'file', 'Upload Transkrip Nilai', true, $sort++, null, ['pdf', 'jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'upload_sertifikat', 'file', 'Upload Sertifikat JLPT/Keterampilan jika ada', false, $sort++, null, ['pdf', 'jpg', 'jpeg', 'png']);
        $this->addField($form, $program, 'bukti_follow', 'file', 'Bukti Follow IG & TikTok @kizuku.academy', true, $sort++, null, ['jpg', 'jpeg', 'png', 'pdf']);

        // Bagian 4: Informasi Tambahan
        $this->addSection($form, $program, 'Bagian 4: Informasi Tambahan', $sort++);
        $this->addField($form, $program, 'motivasi', 'textarea', 'Motivasi ingin bekerja di Jepang', true, $sort++);

        // Bagian 5: Pernyataan
        $this->addSection($form, $program, 'Bagian 5: Pernyataan', $sort++);
        $this->addField($form, $program, 'pernyataan_data', 'radio', 'Saya menyatakan seluruh data benar dan dapat dipertanggungjawabkan', true, $sort++, [
            ['label' => ['id' => 'Ya, saya menyatakan data yang diisi benar'], 'value' => 'Ya']
        ]);
    }

    private function addSection(Form $form, Program $program, string $title, int $sortOrder): void
    {
        FormField::create([
            'form_id'     => $form->id,
            'program_id'  => $program->id,
            'field_name'  => 'sec_' . Str::slug($title, '_'),
            'label'       => ['id' => $title],
            'type'        => 'section',
            'is_required' => false,
            'status'      => 'aktif',
            'sort_order'  => $sortOrder,
        ]);
    }

    private function addField(Form $form, Program $program, string $name, string $type, string $label, bool $required, int $sortOrder, ?array $options = null, ?array $fileTypes = null): void
    {
        FormField::create([
            'form_id'             => $form->id,
            'program_id'          => $program->id,
            'field_name'          => $name,
            'label'               => ['id' => $label],
            'type'                => $type,
            'is_required'         => $required,
            'options'             => $options,
            'accepted_file_types' => $fileTypes,
            'status'              => 'aktif',
            'sort_order'          => $sortOrder,
        ]);
    }
}
