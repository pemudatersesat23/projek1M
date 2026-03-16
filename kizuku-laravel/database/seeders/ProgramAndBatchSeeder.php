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
                'deskripsi' => 'Program untuk tenaga kerja berketerampilan khusus di berbagai sektor industri di Jepang.',
                'target_peserta' => 'Lulusan SMA/SMK, usia 18-35 tahun, sehat jasmani dan rohani.',
                'durasi' => '5-6 Bulan Pelatihan',
                'benefit' => 'Gaji standar Jepang, asuransi, tempat tinggal, peluang karir jangka panjang.',
                'alur_seleksi' => 'Daftar -> Pelatihan Bahasa -> Ujian Skill & JFT -> Interview User -> COE & Visa -> Berangkat.',
                'biaya' => 'Rp 5.000.000 - Rp 15.000.000 (Sesuai Skema)',
                'faq' => [
                    ['q' => 'Apakah harus punya sertifikat JLPT?', 'a' => 'Cukup JFT-Basic A2 atau JLPT N4.'],
                    ['q' => 'Apakah ada pinjaman biaya?', 'a' => 'Tersedia skema cicilan untuk batch tertentu.']
                ],
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Engineering (Gijinkoku)',
                'deskripsi' => 'Penempatan kerja profesional untuk lulusan S1/D3 Teknik di perusahaan Jepang.',
                'target_peserta' => 'Lulusan D3/S1 Teknik (Mesin, Elektro, Sipil, IT, dll).',
                'durasi' => '6-8 Bulan Pelatihan',
                'benefit' => 'Status visa Engineer, gaji profesional, bisa membawa keluarga, jenjang karir manajerial.',
                'alur_seleksi' => 'Daftar -> Skrining CV -> Pelatihan Bahasa Pro -> Interview User -> Kontrak Kerja -> Berangkat.',
                'biaya' => 'Biaya penempatan kompetitif (Hubungi CS)',
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Kenshusei (Magang Jepang)',
                'deskripsi' => 'Program pemagangan industri untuk belajar sambil bekerja di Jepang selama 3 tahun.',
                'target_peserta' => 'Usia 18-26 tahun, lulusan SMA/SMK, memiliki disiplin tinggi.',
                'durasi' => '4-5 Bulan Pelatihan',
                'benefit' => 'Uang saku, tunjangan penyelesaian, sertifikat kompetensi internasional.',
                'alur_seleksi' => 'Seleksi Fisik -> Psikotes -> Pelatihan Terpusat -> Matching Perusahaan -> Berangkat.',
                'biaya' => 'Skema subsidi tersedia',
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Kursus Bahasa Jepang',
                'deskripsi' => 'Kelas intensif bahasa Jepang untuk persiapan ujian JLPT, JFT, atau kebutuhan hobi dan bisnis.',
                'target_peserta' => 'Umum, siswa, atau calon tenaga kerja.',
                'durasi' => '1-3 Bulan (Tergantung Tingkat)',
                'benefit' => 'Materi standar Jepang, pengajar berpengalaman, sertifikat kursus.',
                'alur_seleksi' => 'Pendaftaran -> Placement Test -> Pembayaran -> Mulai Kelas.',
                'biaya' => 'Mulai dari Rp 1.500.000',
                'status' => 'aktif',
            ],
            [
                'nama_program' => 'Ex-Internship (Engineer)',
                'deskripsi' => 'Program khusus bagi alumni magang (Ex-Japan) yang ingin kembali sebagai tenaga kerja profesional.',
                'target_peserta' => 'Alumni Magang Jepang (minimal 3 tahun), memiliki sertifikat JITCO/OTIT.',
                'durasi' => '2-3 Bulan Review',
                'benefit' => 'Proses lebih cepat, langsung interview user, sisa masa tinggal diperhitungkan.',
                'alur_seleksi' => 'Verifikasi Dokumen -> Interview -> Proses Dokumen -> Berangkat.',
                'biaya' => 'Biaya administrasi minimal',
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
                'nama_batch' => 'Batch 1 - Selesai',
                'status' => 'selesai',
                'tanggal_buka' => now()->subMonths(6),
                'tanggal_tutup' => now()->subMonths(5),
                'tanggal_mulai' => now()->subMonths(4),
                'tanggal_selesai' => now()->subMonth(),
            ]);

            Batch::create([
                'program_id' => $program->id,
                'nama_batch' => 'Batch 2 - Sedang Dibuka',
                'status' => 'dibuka',
                'tanggal_buka' => now()->subDays(10),
                'tanggal_tutup' => now()->addDays(20),
                'tanggal_mulai' => now()->addMonth(),
                'tanggal_selesai' => now()->addMonths(6),
                'kuota' => 25,
            ]);

            Batch::create([
                'program_id' => $program->id,
                'nama_batch' => 'Batch 3 - Akan Dibuka',
                'status' => 'akan_dibuka',
                'tanggal_buka' => now()->addMonths(2),
                'tanggal_tutup' => now()->addMonths(3),
                'tanggal_mulai' => now()->addMonths(4),
                'tanggal_selesai' => now()->addMonths(10),
            ]);
        }
    }
}
