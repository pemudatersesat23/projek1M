<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\HeroSection::create([
            'badge_text' => 'LPK Kizuku International Academy',
            'title' => 'Wujudkan Karier Impian Anda di Jepang',
            'subtitle' => 'Pelatihan intensif Bahasa Jepang dan persiapan kerja profesional.',
            'btn_primary_text' => 'Daftar Sekarang',
            'btn_primary_link' => '#program',
            'btn_secondary_text' => 'Konsultasi Gratis',
            'btn_secondary_link' => '#kontak',
            'is_active' => true,
        ]);

        \App\Models\HeroSection::create([
            'badge_text' => 'Program Tokutei Ginou (TG)',
            'title' => 'Peluang Kerja Sektor Food Processing',
            'subtitle' => 'Keberangkatan batch Maret 2025 sudah dibuka. Kuota terbatas!',
            'btn_primary_text' => 'Lihat Detail',
            'btn_primary_link' => '#program',
            'btn_secondary_text' => 'Tanya Admin',
            'btn_secondary_link' => '#kontak',
            'is_active' => true,
        ]);

        \App\Models\HeroSection::create([
            'badge_text' => 'Success Story Alumni',
            'title' => '98% Alumni Kami Berhasil Berangkat',
            'subtitle' => 'Bergabunglah dengan ribuan alumni yang sudah sukses di Jepang.',
            'btn_primary_text' => 'Testimoni',
            'btn_primary_link' => '#testimoni',
            'btn_secondary_text' => 'Hubungi Kami',
            'btn_secondary_link' => '#kontak',
            'is_active' => true,
        ]);
    }
}
