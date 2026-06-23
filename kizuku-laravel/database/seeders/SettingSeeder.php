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
                'value' => 'Jl. Bontotangnga No.47, Paccinongang, Kec. Somba Opu, Kabupaten Gowa, Sulawesi Selatan 90233',
                'type' => 'textarea',
            ],
            [
                'key' => 'whatsapp_number',
                'label' => 'Nomor WhatsApp (Contoh: 6281234567890)',
                'value' => '6282261888851',
                'type' => 'text',
            ],
            [
                'key' => 'facebook_url',
                'label' => 'Facebook URL',
                'value' => 'https://www.facebook.com/share/1BHHcLbuLP/?mibextid=wwXIfr',
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
                'key' => 'stats_alumni',
                'label' => 'Alumni Ditempatkan',
                'value' => '1000+',
                'type' => 'text',
            ],
            [
                'key' => 'stats_success',
                'label' => 'Tingkat Keberhasilan',
                'value' => '98%',
                'type' => 'text',
            ],
            [
                'key' => 'stats_years',
                'label' => 'Tahun Pengalaman',
                'value' => '10+',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
