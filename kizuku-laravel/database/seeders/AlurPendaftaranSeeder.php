<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlurPendaftaran;

class AlurPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'icon' => 'app_registration',
                'title' => [
                    'id' => 'Pendaftaran Online',
                    'jp' => 'オンライン登録'
                ],
                'description' => [
                    'id' => 'Pilih program yang sesuai dan isi formulir pendaftaran melalui website kami atau hubungi admin via WhatsApp.',
                    'jp' => '適切なプログラムを選択し、当社のウェブサイトから登録フォームに記入するか、WhatsApp経由で管理者に連絡してください。'
                ],
            ],
            [
                'icon' => 'verified_user',
                'title' => [
                    'id' => 'Proses Seleksi',
                    'jp' => '選考プロセス'
                ],
                'description' => [
                    'id' => 'Verifikasi dokumen, tes fisik ringan, dan interview awal untuk memastikan kesesuaian kamu dengan kriteria program.',
                    'jp' => 'プログラムの基準との適合性を確認するための書類確認、軽い体力テスト、初期面接。'
                ],
            ],
            [
                'icon' => 'school',
                'title' => [
                    'id' => 'Proses Pelatihan',
                    'jp' => '研修プロセス'
                ],
                'description' => [
                    'id' => 'Pelatihan intensif bahasa Jepang (N5-N3), pengenalan budaya kerja, dan pengasahan skill teknis sesuai bidang.',
                    'jp' => '日本語の集中研修（N5-N3）、労働文化の紹介、分野に応じた技術スキルの向上。'
                ],
            ],
            [
                'icon' => 'groups',
                'title' => [
                    'id' => 'Wawancara Perusahaan',
                    'jp' => '企業面接'
                ],
                'description' => [
                    'id' => 'Sesi interview langsung dengan perwakilan perusahaan/user dari Jepang untuk penempatan kerja.',
                    'jp' => '就職のための日本の企業/ユーザーの代表との直接面接セッション。'
                ],
            ],
            [
                'icon' => 'flight_takeoff',
                'title' => [
                    'id' => 'Proses Keberangkatan',
                    'jp' => '出発プロセス'
                ],
                'description' => [
                    'id' => 'Pengurusan dokumen COE/Visa, medical check-up akhir, dan persiapan teknis keberangkatan ke Jepang.',
                    'jp' => 'COE/ビザの書類手続き、最終健康診断、日本への出発の技術的準備。'
                ],
            ],
        ];

        foreach ($steps as $index => $step) {
            AlurPendaftaran::create([
                'icon' => $step['icon'],
                'title' => $step['title'],
                'description' => $step['description'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
