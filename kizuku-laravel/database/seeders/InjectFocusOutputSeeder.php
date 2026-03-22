<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class InjectFocusOutputSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tokutei Ginou (TG)
        $tg = Program::where('slug', 'tokutei-ginou-tg')->first();
        if ($tg) {
            $tg->setTranslation('focus', 'id', 'Bahasa Jepang, Skill Kerja, Persiapan Ujian, Interview');
            $tg->setTranslation('output', 'id', 'Siap kerja ke Jepang melalui seleksi User JFT/SSW');
            $tg->save();
        }

        // 2. Engineer Jepang (Gijinkoku)
        $eng = Program::where('slug', 'engineer-jepang-gijinkoku')->first();
        if ($eng) {
            $eng->setTranslation('focus', 'id', 'Bahasa Jepang Profesional, Interview Perusahaan, Budaya Kerja Jepang');
            $eng->setTranslation('output', 'id', 'Kesiapan mental dan kebahasaan yang kokoh untuk bekerja tingkat profesional di perusahaan Jepang');
            $eng->save();
        }

        // 3. Ex-Internship
        $ex = Program::where('slug', 'engineer-jepang-ex-internship')->first();
        if ($ex) {
            $ex->setTranslation('focus', 'id', 'Upgrade Tata Bahasa, Persiapan Kerja Kembali ke Jepang, Refreshing Etos Kerja');
            $ex->setTranslation('output', 'id', 'Dapat kembali bekerja ke Jepang dengan status visa dan tingkatan gaji yang lebih tinggi');
            $ex->save();
        }

        // 4. Kursus Bahasa Jepang
        $kursus = Program::where('slug', 'kursus-bahasa-jepang')->first();
        if ($kursus) {
            $kursus->setTranslation('focus', 'id', 'Percakapan (Kaiwa), Hiragana, Katakana, Kanji, Persiapan JLPT/JFT Basic');
            $kursus->setTranslation('output', 'id', 'Siap menghadapai ujian standarisasi level bahasa Jepang (JLPT/JFT) secara memuaskan');
            $kursus->save();
        }

        // 5. Kenshusei (Magang Jepang) (Optional fill just in case)
        $magang = Program::where('slug', 'kenshusei-jishussei-magang-jepang')->first();
        if ($magang) {
            $magang->setTranslation('focus', 'id', 'Penguatan Fisik & Mental, Bela Diri, Kedisiplinan ala Jepang, Bahasa Dasar');
            $magang->setTranslation('output', 'id', 'Terbentuknya fisik dan kebiasaan tangguh sebelum diterbangkan sebagai peserta magang murni ke Jepang');
            $magang->save();
        }
    }
}
