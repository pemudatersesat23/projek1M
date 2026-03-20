<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;
use App\Models\PartnerCampus;
use App\Models\Gallery;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Fasilitas
        $facilities = [
            [
                'nama' => 'Asrama Eksklusif',
                'image' => 'image/asrama.png',
                'urutan' => 1
            ],
            [
                'nama' => 'Ruang Kelas Modern',
                'image' => 'image/kelas.png',
                'urutan' => 2
            ],
            [
                'nama' => 'Laboratorium Bahasa',
                'image' => 'image/lab.png',
                'urutan' => 3
            ],
            [
                'nama' => 'Area Relax & Lounge',
                'image' => 'image/lounge.png',
                'urutan' => 4
            ],
        ];

        foreach ($facilities as $f) {
            Fasilitas::updateOrCreate(['nama' => $f['nama']], $f);
        }

        // 2. Partner Campus
        $campuses = [
            [
                'name' => 'Tokyo Global Academy',
                'logo' => 'image/kampus/logo-1.png',
                'banner' => 'image/kampus/banner-1.png',
                'description' => 'Kampus unggulan di pusat Tokyo yang berfokus pada pelatihan teknis dan bahasa Jepang bisnis.',
            ],
            [
                'name' => 'Osaka International Institute',
                'logo' => 'image/kampus/logo-2.png',
                'banner' => 'image/kampus/banner-2.png',
                'description' => 'Pusat pendidikan di Osaka yang menjembatani siswa dengan industri manufaktur dan teknologi terkemuka.',
            ],
            [
                'name' => 'Kyoto Language & Culture Center',
                'logo' => 'image/kampus/logo-1.png',
                'banner' => 'image/kampus/banner-2.png',
                'description' => 'Lembaga pendidikan yang memadukan pengajaran bahasa dengan pemahaman budaya Jepang yang mendalam.',
            ],
            [
                'name' => 'Nagoya Vocational School',
                'logo' => 'image/kampus/logo-2.png',
                'banner' => 'image/kampus/banner-1.png',
                'description' => 'Sekolah vokasi di Nagoya yang spesifik melatih tenaga kerja di bidang otomotif dan engineering.',
            ],
            [
                'name' => 'Fukuoka Tech Academy',
                'logo' => 'image/kampus/logo-1.png',
                'banner' => 'image/kampus/banner-1.png',
                'description' => 'Akademi teknologi di Fukuoka yang fokus pada pengembangan skill IT dan robotika.',
            ],
        ];

        foreach ($campuses as $c) {
            PartnerCampus::updateOrCreate(['name' => $c['name']], $c);
        }

        // 3. Gallery Placeholders
        $galleries = [
            ['title' => 'Suasana Belajar di Kelas', 'image' => 'galleries/demo1.jpg', 'order' => 1],
            ['title' => 'Sesi Praktek Kerja Kelompok', 'image' => 'galleries/demo2.jpg', 'order' => 2],
            ['title' => 'Wisuda Lulusan Angkatan VI', 'image' => 'galleries/demo3.jpg', 'order' => 3],
        ];

        foreach ($galleries as $g) {
            // Check if exists to avoid duplication
            if (!Gallery::where('title', $g['title'])->exists()) {
                Gallery::create(array_merge($g, ['is_active' => true]));
            }
        }
    }
}
