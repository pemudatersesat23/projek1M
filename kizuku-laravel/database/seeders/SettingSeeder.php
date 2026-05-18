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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
