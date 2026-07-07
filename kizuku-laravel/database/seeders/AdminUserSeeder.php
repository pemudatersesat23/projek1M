<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'umarfm18@gmail.com'],
            [
                'name'     => 'Umar Developer Kizuku',
                'password' => Hash::make('UmarDevlopKizuku18'),
                'role'     => 'admin',
            ]
        );
    }
}
