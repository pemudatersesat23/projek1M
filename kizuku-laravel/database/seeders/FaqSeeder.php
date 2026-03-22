<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'q' => 'Apa syarat utama mengikuti program ke Jepang?',
                'a' => 'Syarat utamanya adalah minimal lulusan SMA/SMK sederajat, usia 18-35 tahun (tergantung program), memiliki fisik yang sehat (tidak bertato/bertindik), serta lulus seleksi internal dari kami.'
            ],
            [
                'q' => 'Berapa lama proses pelatihan dan persiapan keberangkatan?',
                'a' => 'Lama pelatihan bergantung pada program yang diambil. Untuk magang (Kenshusei) biasanya 4-6 bulan persiapan bahasa dan fisik. Sedangkan untuk Tokutei Ginou dan Engineering bervariasi antara 3-7 bulan.'
            ],
            [
                'q' => 'Berapa biaya program ke Jepang dari awal hingga berangkat?',
                'a' => 'Biaya bervariasi tergantung tipe program. LPK Kizuku mengutamakan transparansi biaya sejak awal. Kami tidak memiliki dana talangan namun terdapat skema cicilan sesuai aturan yang berlaku. Silakan hubungi admin untuk detail biaya pastinya.'
            ],
            [
                'q' => 'Apakah calon peserta dari luar daerah/pulau bisa ikut?',
                'a' => 'Sangat bisa! Kami menerima pendaftar dari seluruh Indonesia. Bagi siswa dari luar daerah Makassar dan Kendari, pendaftaran dan bimbingan bisa dilakukan hingga saatnya seleksi offline.'
            ],
            [
                'q' => 'Apakah yang belum pernah belajar Bahasa Jepang bisa mendaftar?',
                'a' => 'Tentu. LPK Kizuku International Academy akan mendidik Anda dari nol hingga mencapai level bahasa Jepang (N5/N4) yang dipersyaratkan untuk wawancara kerja.'
            ],
            [
                'q' => 'Bagaimana jika saya gagal dalam seleksi wawancara user?',
                'a' => 'Jangan khawatir, tim kami akan mengevaluasi kekurangan Anda. Anda akan terus diberikan pelatihan sampai Anda siap untuk diikutkan dalam jadwal wawancara perusahaan berikutnya.'
            ],
            [
                'q' => 'Berapa lama kontrak kerja di Jepang per periodenya?',
                'a' => 'Kontrak kerja rata-rata adalah 3 tahun untuk magang dan Tokutei Ginou (bisa diperpanjang hingga 5 tahun). Sedangkan untuk Profesional/Engineer (Gijinkoku) kontrak bisa lebih panjang tanpa batas waktu maksimal.'
            ],
            [
                'q' => 'Apakah gaji di Jepang cukup untuk dikirim ke keluarga?',
                'a' => 'Sangat cukup. Rata-rata gaji pokok di Jepang berkisar Rp 15.000.000 hingga Rp 25.000.000 per bulan, belum termasuk lembur. Setelah dipotong biaya hidup dasar (apartemen, asuransi), mayoritas siswa masih bisa menabung belasan juta.'
            ],
            [
                'q' => 'Apakah LPK Kizuku resmi dan teregistrasi?',
                'a' => 'LPK Kizuku International Academy adalah mitra resmi dan teregistrasi. Kami bekerja sama dengan banyak perusahaan dan pihak berwenang di Jepang untuk menjamin keselamatan dan hak Anda.'
            ],
            [
                'q' => 'Bagaimana cara mendaftar program?',
                'a' => 'Anda bisa melakukan pendaftaran secara online melalui halaman "Alur Pendaftaran" di website ini atau Anda bisa klik ikon WhatsApp untuk konsultasi pendaftaran secara langsung.'
            ]
        ];

        foreach ($faqs as $index => $item) {
            $faq = new Faq();
            $faq->setTranslation('question', 'id', $item['q']);
            $faq->setTranslation('answer', 'id', $item['a']);
            $faq->order = $index + 1;
            $faq->is_active = true;
            $faq->save();
        }
    }
}
