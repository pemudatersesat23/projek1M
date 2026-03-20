<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        foreach ($testimonials as $t) {
            \App\Models\Testimonial::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
