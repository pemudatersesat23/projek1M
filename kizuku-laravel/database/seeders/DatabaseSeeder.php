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
            ['judul'=>'Pembukaan Batch Baru TG Maret 2025',          'kategori'=>'kat-info',   'emoji'=>'🎌', 'isi'=>'Kuota 30 peserta, daftar sekarang!'],
            ['judul'=>'25 Alumni Berhasil Berangkat Bulan Ini',       'kategori'=>'kat-alumni', 'emoji'=>'🏆', 'isi'=>'Selamat untuk para alumni!'],
            ['judul'=>'Diskon 20% Kelas Bahasa Jepang',              'kategori'=>'kat-promo',  'emoji'=>'🎓', 'isi'=>'Berlaku untuk pendaftar awal Maret.'],
            ['judul'=>'5 Tips Lolos Interview User Jepang',           'kategori'=>'kat-tips',   'emoji'=>'📚', 'isi'=>'Simak tips dari trainer berpengalaman.'],
            ['judul'=>'Kizuku Raih Akreditasi A Nasional',            'kategori'=>'kat-info',   'emoji'=>'✅', 'isi'=>'Pengakuan resmi kualitas pelatihan kami.'],
            ['judul'=>'MOU Baru dengan 15 Perusahaan Jepang',         'kategori'=>'kat-info',   'emoji'=>'🤝', 'isi'=>'Membuka lebih banyak peluang untuk 2025.'],
        ];
        foreach ($beritas as $b) { Berita::create($b); }
    }
}
