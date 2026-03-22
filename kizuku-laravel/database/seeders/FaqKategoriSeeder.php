<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqKategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data faq sebelumnya supaya bersih
        Faq::truncate();

        $kategori1 = 'Pendaftaran & Administrasi';
        $faqs1 = [
            [
                'q' => 'Kapan pendaftaran gelombang selanjutnya dibuka?',
                'a' => 'Anda bisa terus pantau website ini di menu \'Program\', informasi pembukaan pendaftaran setiap batch/gelombang akan selalu di-update secara berkala oleh sistem.'
            ],
            [
                'q' => 'Dokumen asli fisik apa saja yang wajib disiapkan?',
                'a' => 'KTP, Kartu Keluarga, Akte Kelahiran, Ijazah terakhir lengkap, Passport (jika ada) dan pas foto terbaru. Untuk program tertentu (Tokutei Ginou) perlu menyiapkan sertifikat bahasa Jepang/Skill.'
            ],
            [
                'q' => 'Apakah pendaftaran web ini dikenai biaya?',
                'a' => 'Tidak dikenai biaya apapun. Pembuatan akun dan proses pengajuan biodata di website ini 100% gratis alias tidak berbayar.'
            ],
            [
                'q' => 'Saya salah mengisi data form pendaftaran, apa yang harus dipencet?',
                'a' => 'Sistem tidak mengizinkan peserta mengedit formulir secara manual demi keamanan data. Silakan hubungi admin kami melalui tombol Konsultasi WhatsApp agar data Anda dapat dikoreksi di database.'
            ],
            [
                'q' => 'Apakah saya perlu mengantar langsung berkas fisik?',
                'a' => 'Ya. Berkas asli dan fotokopi legalisir diperlukan untuk proses verifikasi serta validasi saat proses seleksi offline atau wawancara di kantor LPK Kizuku.'
            ]
        ];

        $kategori2 = 'Pelatihan & Asrama';
        $faqs2 = [
            [
                'q' => 'Dimana lokasi pelatihan LPK Kizuku diadakan?',
                'a' => 'Pusat pelatihan (Training Center) kami saat ini berlokasi di Kota Kendari, Sulawesi Tenggara. Alamat gmap persisnya bisa Anda temukan di footer website ini.'
            ],
            [
                'q' => 'Apakah semua siswa wajib masuk asrama (mess)?',
                'a' => 'Sangat disarankan bahkan diwajibkan untuk masuk asrama dalam periode tertentu agar fokus pembelajaran Bahasa Jepang, Disiplin, dan Fisik Anda bisa dipantau langsung 24 jam.'
            ],
            [
                'q' => 'Apa saja fasilitas yang didapatkan saat belajar disini?',
                'a' => 'Fasilitas meliputi ruang kelas multimedia, asrama nyaman, seragam, buku panduan belajar eksklusif, try-out simulasi wawancara Jepang, latihan fisik, hingga sertifikat praktek.'
            ],
            [
                'q' => 'Jadwal belajarnya dari jam berapa sampai jam berapa?',
                'a' => 'Pelatihan intensif dilakukan di hari kerja, dari jam 08:00 pagi hingga jam 16:00 sore. Di luar jam tersebut Anda tetap bisa belajar mandiri atau diskusi di asrama.'
            ],
            [
                'q' => 'Siapa yang akan menjadi mentor atau instruktur kami?',
                'a' => 'Instruktur/Sensei di LPK Kizuku adalah profesional berpengalaman yang sangat berkualitas. Sebagian besar menguasai JLPT N2/N3 dan merupakan Ex-Japan (telah nyata bekerja/magang di Jepang).'
            ]
        ];

        $kategori3 = 'Pemberangkatan & Pekerjaan';
        $faqs3 = [
            [
                'q' => 'Berapa lama waktu tunggu dari lulus wawancara hingga terbang?',
                'a' => 'Bergantung alur dokumen dan kaisha, tapi umumnya memakan waktu 4 hingga 8 bulan untuk proses turunnya CoE dari imigrasi Jepang, Visa, dan Medical Check Up akhir.'
            ],
            [
                'q' => 'Apakah visa serta tiket pesawat ditanggung pinjaman LPK?',
                'a' => 'Biaya pengurusan paspor, visa, dan tiket penerbangan Indonesia-Jepang dijelaskan saat pra-kontrak berlangsung. Sebagian perusahaan di Jepang memberikan fasilitas tiket gratis, namun ada juga yang tidak.'
            ],
            [
                'q' => 'Jika sakit saat di Jepang, apakah ditanggung perusahaan?',
                'a' => 'Tentu saja. Anda akan dilindungi Asuransi Kesehatan Sosial resmi dari pemerintah Jepang (Shakai Hoken) yang memfasilitasi 70% keringanan biaya berobat jalan hingga rawat inap.'
            ],
            [
                'q' => 'Boleh mencari pekerjaan sampingan (arubaito) di luar dinas?',
                'a' => 'Berbahaya! Untuk status Kenshusei/Jishusei (Magang) maupun Tokutei Ginou sangat dilarang secara hukum melakukan kerja sambilan atau arubaito ilegal. Hal ini bisa berujung pendeportasian.'
            ],
            [
                'q' => 'Apakah saya bisa pindah tempat kerja jika bosan?',
                'a' => 'Bergantung visa Anda. Visa magang murni mengikat Anda pada 1 perusahaan selama periode 3 tahun tersebut. Tapi khusus untuk peserta visa Tokutei Ginou diperbolehkan pindah kaisha (perusahaan) dengan aturan terlampir.'
            ]
        ];

        $order = 1;
        
        foreach ($faqs1 as $item) {
            $faq = new Faq();
            $faq->setTranslation('kategori', 'id', $kategori1);
            $faq->setTranslation('question', 'id', $item['q']);
            $faq->setTranslation('answer', 'id', $item['a']);
            $faq->order = $order++;
            $faq->is_active = true;
            $faq->save();
        }

        foreach ($faqs2 as $item) {
            $faq = new Faq();
            $faq->setTranslation('kategori', 'id', $kategori2);
            $faq->setTranslation('question', 'id', $item['q']);
            $faq->setTranslation('answer', 'id', $item['a']);
            $faq->order = $order++;
            $faq->is_active = true;
            $faq->save();
        }

        foreach ($faqs3 as $item) {
            $faq = new Faq();
            $faq->setTranslation('kategori', 'id', $kategori3);
            $faq->setTranslation('question', 'id', $item['q']);
            $faq->setTranslation('answer', 'id', $item['a']);
            $faq->order = $order++;
            $faq->is_active = true;
            $faq->save();
        }
    }
}
