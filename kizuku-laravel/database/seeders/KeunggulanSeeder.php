<?php

namespace Database\Seeders;

use App\Models\Keunggulan;
use Illuminate\Database\Seeder;

class KeunggulanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'icon' => 'verified_user',
                'title' => [
                    'id' => 'Sending Organization Resmi',
                    'jp' => '公認送り出し機関',
                ],
                'description' => [
                    'id' => 'Lembaga resmi yang terdaftar dan memiliki izin operasional pemagangan serta penempatan kerja ke Jepang.',
                    'jp' => '日本への技能実習および就労派遣の正式な許可を持つ公認機関です。',
                ],
                'order' => 1
            ],
            [
                'icon' => 'groups',
                'title' => [
                    'id' => 'Ribuan Peserta Diberangkatkan',
                    'jp' => '数千人の派遣実績',
                ],
                'description' => [
                    'id' => 'Telah memberangkatkan lebih dari 1000 alumni yang kini sukses berkarir di berbagai prefektur di Jepang.',
                    'jp' => 'これまでに1,000人以上の卒業生を派遣し、日本の各県で活躍しています。',
                ],
                'order' => 2
            ],
            [
                'icon' => 'handshake',
                'title' => [
                    'id' => 'Jaringan Perusahaan Jepang',
                    'jp' => '広大な日本企業ネットワーク',
                ],
                'description' => [
                    'id' => 'Bekerjasama dengan ribuan mitra industri dari berbagai sektor mulai dari manufaktur hingga perhotelan.',
                    'jp' => '製造業から宿泊業まで、数千の産業パートナーと提携しています。',
                ],
                'order' => 3
            ],
            [
                'icon' => 'school',
                'title' => [
                    'id' => 'Pelatihan Terintegrasi',
                    'jp' => '統合されたトレーニング',
                ],
                'description' => [
                    'id' => 'Pelatihan bahasa, budaya, kedisiplinan, hingga skill teknis yang dirancang khusus sesuai standar industri Jepang.',
                    'jp' => '日本の産業基準に合わせた語学、文化、規律、技術スキルの統合研修。',
                ],
                'order' => 4
            ],
        ];

        foreach ($data as $item) {
            Keunggulan::create($item);
        }
    }
}
