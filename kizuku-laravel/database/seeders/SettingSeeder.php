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
                'value' => 'Jl. Contoh No. 123, Kota Anda, Indonesia',
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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
