<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'office_address',
                'label' => 'Alamat Kantor',
                'value' => 'Jl. Bontotangnga, Paccinongang, Kec. Somba Opu, Kabupaten Gowa, Sulawesi Selatan 90233',
                'type' => 'textarea',
            ],
            [
                'key' => 'whatsapp_number',
                'label' => 'Nomor WhatsApp (Contoh: 6281234567890)',
                'value' => '6281217549529',
                'type' => 'text',
            ],
            [
                'key' => 'admin_email',
                'label' => 'Email Admin',
                'value' => 'info@kizuku-academy.com',
                'type' => 'text',
            ],
            [
                'key' => 'office_hours',
                'label' => 'Jam Operasional',
                'value' => 'Senin – Sabtu, 08.00 – 17.00 WIB',
                'type' => 'text',
            ],
            [
                'key' => 'hero_slider_duration',
                'label' => 'Durasi Slider Hero (Detik)',
                'value' => '5',
                'type' => 'number',
            ],
            [
                'key' => 'instagram_link',
                'label' => 'Link Instagram',
                'value' => 'https://instagram.com/kizuku_academy',
                'type' => 'text',
            ],
            [
                'key' => 'tiktok_link',
                'label' => 'Link TikTok',
                'value' => 'https://tiktok.com/@kizuku_academy',
                'type' => 'text',
            ],
            [
                'key' => 'youtube_link',
                'label' => 'Link YouTube',
                'value' => 'https://youtube.com/@kizuku_academy',
                'type' => 'text',
            ],
            [
                'key' => 'alur_tag',
                'label' => 'Tagline Alur Pendaftaran',
                'value' => '✦ PROSES SELEKSI',
                'type' => 'text',
            ],
            [
                'key' => 'alur_title',
                'label' => 'Judul Alur Pendaftaran',
                'value' => 'Alur Pendaftaran & <br><span class="text-primary">Keberangkatan</span>',
                'type' => 'textarea',
            ],
            [
                'key' => 'alur_subtitle',
                'label' => 'Subjudul Alur Pendaftaran',
                'value' => 'Pahami setiap langkah perjalanannmu menuju karier sukses di Jepang bersama LPK Kizuku.',
                'type' => 'textarea',
            ],
            [
                'key' => 'alur_cta_title',
                'label' => 'Judul CTA Alur Pendaftaran',
                'value' => 'Mulai Langkah Pertama Anda Hari Ini',
                'type' => 'text',
            ],
            [
                'key' => 'alur_cta_btn',
                'label' => 'Teks Tombol CTA Alur Pendaftaran',
                'value' => 'Pilih Program & Daftar',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
