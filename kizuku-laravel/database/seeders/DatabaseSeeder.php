<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══ ADMIN USER ═══
        User::updateOrCreate(
            ['email' => 'admin@kizuku.co.id'],
            [
                'name'     => 'Budi Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // ═══ REGULAR USER (untuk testing) ═══
        User::updateOrCreate(
            ['email' => 'user@kizuku.com'],
            [
                'name'     => 'Rini Peserta Kizuku',
                'password' => Hash::make('user123'),
                'role'     => 'user',
            ]
        );

        // ═══ DATA PROGRAM & BATCH ═══
        $this->call(ProgramAndBatchSeeder::class);

        // ═══ DATA BERITA ═══
        $beritas = [
            [
                'judul' => ['id' => 'Pembukaan Batch Baru TG Maret 2025', 'jp' => '2025年3月特定技能新バッチ開講'],
                'kategori' => 'kat-info',
                'isi' => ['id' => 'Kuota 30 peserta, daftar sekarang!', 'jp' => '定員30名、今すぐお申し込みください！']
            ],
            [
                'judul' => ['id' => '25 Alumni Berhasil Berangkat Bulan Ini', 'jp' => '今月25名の卒業生が日本へ出発'],
                'kategori' => 'kat-alumni',
                'isi' => ['id' => 'Selamat untuk para alumni!', 'jp' => '卒業生の皆さん、おめでとうございます！']
            ],
            [
                'judul' => ['id' => 'Diskon 20% Kelas Bahasa Jepang', 'jp' => '日本語クラス20％割引キャンペーン'],
                'kategori' => 'kat-promo',
                'isi' => ['id' => 'Berlaku untuk pendaftar awal Maret.', 'jp' => '3月初旬の登録者に適用されます。']
            ],
            [
                'judul' => ['id' => '5 Tips Lolos Interview User Jepang', 'jp' => '日本人面接に合格するための5つのコツ'],
                'kategori' => 'kat-tips',
                'isi' => ['id' => 'Simak tips dari trainer berpengalaman.', 'jp' => '経験豊富なトレーナーのアドバイスをチェック。']
            ],
            [
                'judul' => ['id' => 'Kizuku Raih Akreditasi A Nasional', 'jp' => 'Kizukuが国家認定Aランクを取得'],
                'kategori' => 'kat-info',
                'isi' => ['id' => 'Pengakuan resmi kualitas pelatihan kami.', 'jp' => '私たちの研修品質が公式に認められました。']
            ],
            [
                'judul' => ['id' => 'MOU Baru dengan 15 Perusahaan Jepang', 'jp' => '日本企業15社と新たにMOUを締結'],
                'kategori' => 'kat-info',
                'isi' => ['id' => 'Membuka lebih banyak peluang untuk 2025.', 'jp' => '2025年に向けてさらなる機会を創出します。']
            ],
        ];
        foreach ($beritas as $b) { Berita::create($b); }

        // ═══ DATA TESTIMONIAL ═══
        $testimonials = [
            [
                'name'    => 'Andika Prasetya',
                'role'    => 'Alumni TG - Food Processing (Aomori, Jepang)',
                'content' => 'Berkat LPK Kizuku, saya bisa bekerja di Aomori. Pelatihannya sangat lengkap dari bahasa sampai budaya kerja.',
                'stars'   => 5,
                'is_active' => true,
            ],
            [
                'name'    => 'Sari Wijaya',
                'role'    => 'Alumni Magang - Pertanian (Hokkaido, Jepang)',
                'content' => 'Dulu saya ragu, tapi bimbingan di sini sangat membantu sampai keberangkatan. Instrukturnya sangat sabar.',
                'stars'   => 5,
                'is_active' => true,
            ],
            [
                'name'    => 'Bagus Kurnia',
                'role'    => 'Alumni Engineer - IT Developer (Tokyo, Jepang)',
                'content' => 'Latar belakang S1 saya sangat dihargai. Sekarang saya di Tokyo menjadi pengembang sistem profesional.',
                'stars'   => 5,
                'is_active' => true,
            ],
            [
                'name'    => 'Diana Putri',
                'role'    => 'Alumni Magang - Caregiver (Nagoya, Jepang)',
                'content' => 'Pelatihan bahasanya intensif, sangat membantu saat interview dengan user. Fasilitas asramanya juga nyaman.',
                'stars'   => 5,
                'is_active' => true,
            ],
            [
                'name'    => 'Eko Santoso',
                'role'    => 'Alumni TG - Restoran (Osaka, Jepang)',
                'content' => 'Terima kasih Kizuku sudah mewujudkan cita-cita saya bekerja di Osaka. Prosesnya transparan dan cepat.',
                'stars'   => 5,
                'is_active' => true,
            ],
        ];
        foreach ($testimonials as $t) { \App\Models\Testimonial::create($t); }
    }
}
