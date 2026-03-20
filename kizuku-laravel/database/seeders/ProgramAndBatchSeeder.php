<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Batch;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ProgramAndBatchSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin for 'admins' table
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'nama_lengkap' => 'Administrator Kizuku',
            ]
        );

        // Create Admin for 'users' table (compatible with Breeze & Middleware)
        User::updateOrCreate(
            ['email' => 'admin@kizuku.co.id'],
            [
                'name' => 'Admin Kizuku',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $programsData = [
            [
                'nama_program' => 'Tokutei Ginou (TG)',
                'deskripsi' => 'Program untuk tenaga kerja berketerampilan khusus yang memungkinkan Anda bekerja di berbagai sektor industri di Jepang dengan status tinggal Specified Skilled Worker.',
                'target_peserta' => 'Lulusan SMA/SMK/S1, usia 18-35 tahun, sehat jasmani dan rohani.',
                'durasi' => '5-6 Bulan Pelatihan',
                'materi' => 'Bahasa Jepang (JLPT N4/JFT-Basic A2), Skill Spesifik Sektor Industri, Persiapan Interview.',
                'benefit' => "Gaji standar Jepang (Rp 18-25jt/bulan)\nKontrak kerja hingga 5 tahun\nFasilitas tempat tinggal & asuransi\nPeluang pindah sektor kerja",
                'alur_seleksi' => "Pendaftaran & Skrining\nPelatihan Bahasa & Skill\nUjian JFT/JLPT & Skill Assessment\nInterview User Jepang\nProses COE & Visa\nKeberangkatan",
                'biaya' => 'Rp 15.000.000 (Opsi cicilan tersedia)',
                'faq' => [
                    ['q' => 'Apakah harus bisa Bahasa Jepang?', 'a' => 'Tidak wajib, kami akan latih dari nol sampai level N4.'],
                    ['q' => 'Sektor apa saja yang tersedia?', 'a' => 'Pengolahan makanan, Pertanian, Perawatan Lansia, Restoran, dll.']
                ],
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Engineer Jepang (Gijinkoku)',
                'deskripsi' => 'Penempatan profesional bagi lulusan D3/S1 Teknik untuk berkarir sebagai tenaga ahli (Engineer) di perusahaan manufaktur dan teknologi terkemuka di Jepang.',
                'target_peserta' => 'Lulusan D3/S1 Teknik (Mesin, Elektro, IT, Sipil, dll).',
                'durasi' => '6-8 Bulan Pelatihan Intensif',
                'materi' => 'Bahasa Jepang Level Pro (N3/N2), Etika Kerja Jepang (Business Manners), Technical Interview Preparation.',
                'benefit' => "Status Visa Gijinkoku (Professional)\nGaji setara Engineer Lokal Jepang\nBisa membawa keluarga (Dependent Visa)\nJenjang karir manajerial jangka panjang",
                'alur_seleksi' => "Pendaftaran & Verifikasi Ijazah\nInternal Interview & Placement Test\nPelatihan Bahasa Jepang Intensif\nUser Matching & Interview\nKontrak Kerja & COE\nVisa & Departure",
                'biaya' => 'Biaya penempatan (Sesuai kualifikasi)',
                'faq' => [
                    ['q' => 'Apakah butuh pengalaman kerja?', 'a' => 'Fresh graduate dipersilakan melamar, namun pengalaman kerja menjadi nilai tambah.'],
                    ['q' => 'Apakah visa ini bisa diperpanjang?', 'a' => 'Bisa, visa ini tidak memiliki batas maksimal tinggal selama kontrak berlanjut.']
                ],
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Kenshusei / Jishussei (Magang Jepang)',
                'deskripsi' => 'Program pemagangan teknis untuk belajar sambil bekerja di industri Jepang guna meningkatkan keterampilan dan etos kerja standar internasional.',
                'target_peserta' => 'Usia 18-26 tahun, lulusan SMA/SMK sederajat, tinggi badan proporsional.',
                'durasi' => '4-5 Bulan Pelatihan Terpusat',
                'materi' => 'Bahasa Jepang Dasar (N5), Fisik, Mental, dan Disiplin (FMD), Pengenalan Budaya Kerja.',
                'benefit' => "Gratis biaya keberangkatan (Skema Subsidi)\nUang saku bulanan\nTunjangan penyelesaian program (Nenkin)\nSertifikat kompetensi dari Jepang",
                'alur_seleksi' => "Seleksi Fisik & Administrasi\nPsikotes & Medical Check-up\nPelatihan Pra-Pemberangkatan\nMatching dengan Perusahaan Jepang\nProses Dokumen Pelatihan\nKeberangkatan",
                'biaya' => 'Mulai Rp 0 (Skema subsidi/dana talangan)',
                'faq' => [
                    ['q' => 'Berapa lama kontrak magang?', 'a' => 'Umumnya 3 tahun, bisa diperpanjang hingga 5 tahun.'],
                    ['q' => 'Apakah dapat makan dan tempat tinggal?', 'a' => 'Tempat tinggal disediakan, uang makan ditanggung peserta dari uang saku.']
                ],
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Kursus Bahasa Jepang (Offline)',
                'deskripsi' => 'Kelas belajar bahasa Jepang tatap muka secara intensif untuk berbagai kebutuhan, mulai dari persiapan ujian hingga percakapan harian.',
                'target_peserta' => 'Umum, pelajar, atau calon tenaga kerja mandiri.',
                'durasi' => '1-3 Bulan per Tingkat',
                'materi' => 'Huruf (Hiragana, Katakana, Kanji), Tata Bahasa, Percakapan (Kaiwa), Listening (Choukai).',
                'benefit' => "Sertifikat resmi dari LPK Kizuku\nModul pembelajaran lengkap\nPengajar berpengalaman/Native speaker\nKonsultasi karir ke Jepang gratis",
                'alur_seleksi' => "Pendaftaran Online/Offline\nTes Penempatan (Placement Test)\nPembayaran Administrasi\nMemulai Kelas",
                'biaya' => 'Mulai dari Rp 1.500.000',
                'faq' => [
                    ['q' => 'Apakah ada kelas malam?', 'a' => 'Ya, tersedia jadwal kelas pagi dan malam.'],
                    ['q' => 'Apakah bisa untuk persiapan JLPT?', 'a' => 'Ya, kurikulum kami disesuaikan dengan standar ujian JLPT/JFT.']
                ],
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Engineer Jepang (Ex-Internship)',
                'deskripsi' => 'Program khusus bagi alumni Magang Jepang (Ex-Japan) yang ingin kembali berkarir di Jepang dengan status tenaga ahli berdasarkan pengalaman kerja sebelumnya.',
                'target_peserta' => 'Alumni Magang Jepang (minimal 3 tahun) yang memiliki ijazah D3/S1 Teknik.',
                'durasi' => '2-3 Bulan Review & Refreshment',
                'materi' => 'Pemantapan Bahasa Jepang, Sertifikasi Ulang (jika diperlukan), Technical Interview Skill.',
                'benefit' => "Proses keberangkatan jauh lebih cepat\nLangsung masuk ke posisi tenaga ahli\nGaji lebih tinggi berdasarkan pengalaman\nKesempatan membawa keluarga",
                'alur_seleksi' => "Validasi Dokumen Ex-Japan (JITCO/OTIT)\nInterview Skrining Internal\nUpdate Dokumen & Refreshment Training\nInterview dengan User Jepang\nProses COE Khusus Ex-Japan\nKeberangkatan",
                'biaya' => 'Biaya administrasi minimal',
                'faq' => [
                    ['q' => 'Apakah butuh sertifikat Senmonkyu?', 'a' => 'Disarankan ada, namun sertifikat penyelesaian magang lengkap sudah cukup.'],
                    ['q' => 'Apakah ijazah non-teknik bisa?', 'a' => 'Untuk visa Engineer, wajib memiliki latar belakang pendidikan linier (Teknik).']
                ],
                'status' => 'aktif',
            ]
        ];

        foreach ($programsData as $data) {
            $data['slug'] = Str::slug($data['nama_program']);
            $program = Program::updateOrCreate(
                ['nama_program' => $data['nama_program']],
                $data
            );

            // Clear existing batches to avoid duplicates if re-seeding
            $program->batches()->delete();

            // Create sample batches for each program
            Batch::create([
                'program_id' => $program->id,
                'nama_batch' => [
                    'id' => 'Batch 1 - Selesai',
                    'jp' => '第1バッチ - 終了',
                ],
                'status' => 'selesai',
                'tanggal_buka' => now()->subMonths(6),
                'tanggal_tutup' => now()->subMonths(5),
                'tanggal_mulai' => now()->subMonths(4),
                'tanggal_selesai' => now()->subMonth(),
            ]);

            Batch::create([
                'program_id' => $program->id,
                'nama_batch' => [
                    'id' => 'Batch 2 - Sedang Dibuka',
                    'jp' => '第2バッチ - 募集中',
                ],
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(10),
                'tanggal_tutup' => now()->addDays(20),
                'tanggal_mulai' => now()->addMonth(),
                'tanggal_selesai' => now()->addMonths(6),
                'kuota' => 25,
            ]);

            Batch::create([
                'program_id' => $program->id,
                'nama_batch' => [
                    'id' => 'Batch 3 - Akan Dibuka',
                    'jp' => '第3バッチ - まもなく',
                ],
                'status' => 'akan_dibuka',
                'tanggal_buka' => now()->addMonths(2),
                'tanggal_tutup' => now()->addMonths(3),
                'tanggal_mulai' => now()->addMonths(4),
                'tanggal_selesai' => now()->addMonths(10),
            ]);
        }
    }
}
