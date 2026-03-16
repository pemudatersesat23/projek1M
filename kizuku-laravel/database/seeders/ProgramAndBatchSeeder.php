<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Batch;
use Illuminate\Support\Str;

class ProgramAndBatchSeeder extends Seeder
{
    public function run(): void
    {
        $programsData = [
            [
                'name' => 'Tokutei Ginou (TG)',
                'explanation' => 'Program untuk tenaga kerja berketerampilan khusus di berbagai sektor industri di Jepang.',
                'target_participants' => 'Lulusan SMA/SMK, usia 18-35 tahun, sehat jasmani dan rohani.',
                'duration' => '5-6 Bulan Pelatihan',
                'benefits' => 'Gaji standar Jepang, asuransi, tempat tinggal, peluang karir jangka panjang.',
                'selection_flow' => 'Daftar -> Pelatihan Bahasa -> Ujian Skill & JFT -> Interview User -> COE & Visa -> Berangkat.',
                'cost' => 'Rp 5.000.000 - Rp 15.000.000 (Sesuai Skema)',
                'faq' => [
                    ['q' => 'Apakah harus punya sertifikat JLPT?', 'a' => 'Cukup JFT-Basic A2 atau JLPT N4.'],
                    ['q' => 'Apakah ada pinjaman biaya?', 'a' => 'Tersedia skema cicilan untuk batch tertentu.']
                ],
                'status' => 'aktif',
            ],
            [
                'name' => 'Engineering (Gijinkoku)',
                'explanation' => 'Penempatan kerja profesional untuk lulusan S1/D3 Teknik di perusahaan Jepang.',
                'target_participants' => 'Lulusan D3/S1 Teknik (Mesin, Elektro, Sipil, IT, dll).',
                'duration' => '6-8 Bulan Pelatihan',
                'benefits' => 'Status visa Engineer, gaji profesional, bisa membawa keluarga, jenjang karir manajerial.',
                'selection_flow' => 'Daftar -> Skrining CV -> Pelatihan Bahasa Pro -> Interview User -> Kontrak Kerja -> Berangkat.',
                'cost' => 'Biaya penempatan kompetitif (Hubungi CS)',
                'status' => 'aktif',
            ],
            [
                'name' => 'Kenshusei (Magang Jepang)',
                'explanation' => 'Program pemagangan industri untuk belajar sambil bekerja di Jepang selama 3 tahun.',
                'target_participants' => 'Usia 18-26 tahun, lulusan SMA/SMK, memiliki disiplin tinggi.',
                'duration' => '4-5 Bulan Pelatihan',
                'benefits' => 'Uang saku, tunjangan penyelesaian, sertifikat kompetensi internasional.',
                'selection_flow' => 'Seleksi Fisik -> Psikotes -> Pelatihan Terpusat -> Matching Perusahaan -> Berangkat.',
                'cost' => 'Skema subsidi tersedia',
                'status' => 'aktif',
            ],
            [
                'name' => 'Kursus Bahasa Jepang',
                'explanation' => 'Kelas intensif bahasa Jepang untuk persiapan ujian JLPT, JFT, atau kebutuhan hobi dan bisnis.',
                'target_participants' => 'Umum, siswa, atau calon tenaga kerja.',
                'duration' => '1-3 Bulan (Tergantung Tingkat)',
                'benefits' => 'Materi standar Jepang, pengajar berpengalaman, sertifikat kursus.',
                'selection_flow' => 'Pendaftaran -> Placement Test -> Pembayaran -> Mulai Kelas.',
                'cost' => 'Mulai dari Rp 1.500.000',
                'status' => 'aktif',
            ],
            [
                'name' => 'Ex-Internship (Engineer)',
                'explanation' => 'Program khusus bagi alumni magang (Ex-Japan) yang ingin kembali sebagai tenaga kerja profesional.',
                'target_participants' => 'Alumni Magang Jepang (minimal 3 tahun), memiliki sertifikat JITCO/OTIT.',
                'duration' => '2-3 Bulan Review',
                'benefits' => 'Proses lebih cepat, langsung interview user, sisa masa tinggal diperhitungkan.',
                'selection_flow' => 'Verifikasi Dokumen -> Interview -> Proses Dokumen -> Berangkat.',
                'cost' => 'Biaya administrasi minimal',
                'status' => 'aktif',
            ]
        ];

        foreach ($programsData as $data) {
            $data['slug'] = Str::slug($data['name']);
            $program = Program::create($data);

            // Create sample batches for each program
            Batch::create([
                'program_id' => $program->id,
                'name' => 'Batch 1 - Selesai',
                'status' => 'selesai',
                'registration_start' => now()->subMonths(6),
                'registration_end' => now()->subMonths(5),
                'class_start' => now()->subMonths(4),
                'class_estimate_end' => now()->subMonth(),
            ]);

            Batch::create([
                'program_id' => $program->id,
                'name' => 'Batch 2 - Sedang Dibuka',
                'status' => 'dibuka',
                'registration_start' => now()->subDays(10),
                'registration_end' => now()->addDays(20),
                'class_start' => now()->addMonth(),
                'class_estimate_end' => now()->addMonths(6),
                'quota' => 25,
            ]);

            Batch::create([
                'program_id' => $program->id,
                'name' => 'Batch 3 - Akan Dibuka',
                'status' => 'akan_dibuka',
                'registration_start' => now()->addMonths(2),
                'registration_end' => now()->addMonths(3),
                'class_start' => now()->addMonths(4),
                'class_estimate_end' => now()->addMonths(10),
            ]);
        }
    }
}
