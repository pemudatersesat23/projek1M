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
        User::create([
            'name'     => 'Admin Kizuku',
            'email'    => 'admin@kizuku.com',
            'password' => Hash::make('admin123'),
        ]);

        // ═══ DATA SISWA ═══
        $siswas = [
            ['nama'=>'Rizki Pratama',   'wa'=>'081234567890', 'email'=>'rizki@email.com',  'kota'=>'Surabaya',   'program'=>'Tokutei Ginou (TG)',   'status'=>'Berangkat', 'pendidikan'=>'SMA/SMK', 'catatan'=>'Lulus N4, berangkat Maret 2025'],
            ['nama'=>'Andi Setiawan',   'wa'=>'082345678901', 'email'=>'andi@email.com',   'kota'=>'Bandung',    'program'=>'Engineering',          'status'=>'Berangkat', 'pendidikan'=>'D3',      'catatan'=>'Penempatan Osaka'],
            ['nama'=>'Siti Nurhaliza',  'wa'=>'083456789012', 'email'=>'siti@email.com',   'kota'=>'Jakarta',    'program'=>'Returnee / Ex Jepang', 'status'=>'Aktif',     'pendidikan'=>'S1',      'catatan'=>'Target upgrade N3'],
            ['nama'=>'Budi Santoso',    'wa'=>'084567890123', 'email'=>'budi@email.com',   'kota'=>'Malang',     'program'=>'Kelas Bahasa Jepang',  'status'=>'Aktif',     'pendidikan'=>'SMA/SMK', 'catatan'=>'Level N5'],
            ['nama'=>'Dewi Rahayu',     'wa'=>'085678901234', 'email'=>'dewi@email.com',   'kota'=>'Yogyakarta', 'program'=>'Tokutei Ginou (TG)',   'status'=>'Proses',    'pendidikan'=>'SMA/SMK', 'catatan'=>'Menunggu jadwal interview'],
            ['nama'=>'Fajar Kurniawan', 'wa'=>'086789012345', 'email'=>'fajar@email.com',  'kota'=>'Medan',      'program'=>'Engineering',          'status'=>'Aktif',     'pendidikan'=>'S1',      'catatan'=>'Background teknik sipil'],
            ['nama'=>'Ayu Permatasari', 'wa'=>'087890123456', 'email'=>'ayu@email.com',    'kota'=>'Semarang',   'program'=>'Kelas Bahasa Jepang',  'status'=>'Lulus',     'pendidikan'=>'D3',      'catatan'=>'Lulus JLPT N4'],
            ['nama'=>'Hendra Wijaya',   'wa'=>'088901234567', 'email'=>'hendra@email.com', 'kota'=>'Solo',       'program'=>'Tokutei Ginou (TG)',   'status'=>'Proses',    'pendidikan'=>'SMA/SMK', 'catatan'=>'Dokumen sedang diproses'],
        ];
        foreach ($siswas as $s) { Siswa::create($s); }

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
