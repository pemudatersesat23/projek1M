<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder
 * ─────────────────────────────────────────────────────────────────────────────
 * Urutan seeding saat menjalankan `php artisan migrate:fresh --seed` atau
 * `php artisan db:seed`.
 *
 * Struktur seeder ini dibagi menjadi 2 kelompok:
 *
 *  [A] Data Statis Konten Website (tidak sering berubah)
 *      – SettingSeeder      : konfigurasi global website
 *      – DataSeeder         : fasilitas, partner kampus, galeri
 *      – AlurPendaftaranSeeder : langkah-langkah alur pendaftaran
 *      – KeunggulanSeeder   : keunggulan LPK Kizuku
 *      – FaqSeeder          : FAQ umum website
 *
 *  [B] Data Program & Peserta (dapat di-reset via KizukuFullResetSeeder)
 *      – KizukuFullResetSeeder : 5 program, batch, skema, form, 25 peserta
 *
 * Untuk reset HANYA data program/peserta tanpa membuang data statis, jalankan:
 *      php artisan db:seed --class=KizukuFullResetSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan AutoTranslate agar seeding tidak lambat akibat
        // panggilan Google Translate API.
        Program::disableAutoTranslate();
        Berita::disableAutoTranslate();

        // ──────────────────────────────────────────────────────────────────
        // [A] AKUN PENGGUNA (admin & user testing)
        // ──────────────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@kizuku.co.id'],
            [
                'name'     => 'Administrator Kizuku',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@kizuku.com'],
            [
                'name'     => 'Peserta Demo',
                'password' => Hash::make('user123'),
                'role'     => 'user',
            ]
        );

        // ──────────────────────────────────────────────────────────────────
        // [A] DATA STATIS WEBSITE
        // ──────────────────────────────────────────────────────────────────
        $this->call([
            SettingSeeder::class,
            DataSeeder::class,
            AlurPendaftaranSeeder::class,
            KeunggulanSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
        ]);

        // ──────────────────────────────────────────────────────────────────
        // [B] DATA PROGRAM, BATCH, SKEMA, FORM & PESERTA
        // ──────────────────────────────────────────────────────────────────
        $this->call(KizukuFullResetSeeder::class);

        // Aktifkan kembali setelah seeding selesai
        Program::enableAutoTranslate();
        Berita::enableAutoTranslate();
    }
}
