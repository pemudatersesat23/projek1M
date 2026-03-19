<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$credentials = [
    ['email' => 'admin@kizuku.com', 'name' => 'Admin Kizuku'],
    ['email' => 'admin@kizuku.co.id', 'name' => 'Budi Administrator']
];

foreach ($credentials as $cred) {
    $user = User::updateOrCreate(
        ['email' => $cred['email']],
        [
            'name' => $cred['name'],
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]
    );
    echo "User {$cred['email']} fixed/created successfully.\n";
}

// Clean up garbled data if any
DB::table('users')->where('role', 'usern')->update(['role' => 'admin']);
echo "Cleanup done.\n";
