<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$settings = [
    [
        'key' => 'whatsapp_number',
        'value' => '6281217549529',
        'label' => 'Nomor WhatsApp Admin',
        'type' => 'text'
    ],
    [
        'key' => 'admin_email',
        'value' => 'info@kizuku-academy.com',
        'label' => 'Email Admin',
        'type' => 'text'
    ],
    [
        'key' => 'office_address',
        'value' => 'Jl. Bontotangnga, Paccinongang, Kec. Somba Opu, Kabupaten Gowa, Sulawesi Selatan 90233',
        'label' => 'Alamat Kantor',
        'type' => 'textarea'
    ]
];

foreach ($settings as $s) {
    Setting::updateOrCreate(['key' => $s['key']], $s);
}

echo "Settings seeded successfully.\n";
