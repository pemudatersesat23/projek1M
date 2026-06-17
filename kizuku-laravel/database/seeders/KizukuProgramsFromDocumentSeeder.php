<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class KizukuProgramsFromDocumentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->programs() as $index => $data) {
            $program = Program::withTrashed()->find($data['existing_id'])
                ?? Program::withTrashed()->whereIn('slug', $data['legacy_slugs'])->first();

            if ($program) {
                $program->restore();
            } else {
                $program = new Program();
            }
            $program->slug = $data['slug'];
            $program->status = 'aktif';
            $program->is_featured = true;
            $program->sort_order = $index + 1;

            foreach ([
                'nama_program',
                'deskripsi',
                'target_peserta',
                'durasi',
                'focus',
                'output',
                'materi',
                'benefit',
                'alur_seleksi',
                'biaya',
                'faq',
            ] as $field) {
                $program->setTranslation($field, 'id', $data[$field]);
            }

            $program->save();

            $program->sections()->withTrashed()->forceDelete();

            foreach ($data['sections'] as $sectionIndex => $section) {
                $program->sections()->create([
                    'type' => $section['type'],
                    'title' => ['id' => $section['title']],
                    'description' => empty($section['description'])
                        ? []
                        : ['id' => $section['description']],
                    'items' => ['id' => $section['items']],
                    'settings' => [],
                    'sort_order' => $sectionIndex,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function programs(): array
    {
        $selectionFlow = [
            $this->item('Pendaftaran online'),
            $this->item('Seleksi administrasi dan kelengkapan dokumen'),
            $this->item('Pelatihan dan pembekalan program'),
            $this->item('Interview atau proses seleksi perusahaan Jepang'),
            $this->item('Pengurusan dokumen kerja dan keberangkatan'),
        ];

        return [
            [
                'existing_id' => 6,
                'legacy_slugs' => ['tokutei-ginou-tg'],
                'slug' => 'tokutei-ginou-tg',
                'nama_program' => 'Tokutei Ginou (TG)',
                'deskripsi' => 'Program persiapan kerja ke Jepang melalui jalur Tokutei Ginou atau Specified Skilled Worker (SSW). Peserta dibekali bahasa Jepang, keterampilan bidang kerja, persiapan ujian, interview, serta pendampingan proses dokumen.',
                'target_peserta' => 'Calon pekerja berusia minimal 18 tahun yang ingin bekerja di Jepang melalui jalur Tokutei Ginou dan bersedia mengikuti seleksi serta pelatihan.',
                'durasi' => '5 bulan',
                'focus' => 'Bahasa Jepang, keterampilan kerja, persiapan ujian JFT/JLPT dan skill, serta interview perusahaan Jepang.',
                'output' => 'Peserta siap mengikuti seleksi dan bekerja di Jepang melalui jalur Tokutei Ginou.',
                'materi' => 'Bahasa Jepang, budaya kerja Jepang, keterampilan sesuai bidang kerja, simulasi ujian, dan persiapan interview.',
                'benefit' => "Pelatihan bahasa Jepang dan keterampilan kerja\nPersiapan ujian dan interview\nPendampingan dokumen pendaftaran\nPembekalan budaya kerja Jepang\nPilihan bidang kerja yang beragam",
                'alur_seleksi' => implode("\n", array_column($selectionFlow, 'title')),
                'biaya' => 'Hubungi Admin',
                'faq' => [
                    ['q' => 'Berapa usia minimal untuk mendaftar?', 'a' => 'Pendaftaran terbuka mulai usia 18 tahun dengan mengikuti ketentuan seleksi program.'],
                    ['q' => 'Apakah harus sudah bisa bahasa Jepang?', 'a' => 'Kemampuan bahasa Jepang menjadi nilai tambah. Peserta akan mengikuti pelatihan sesuai kebutuhan program.'],
                    ['q' => 'Bidang kerja apa saja yang tersedia?', 'a' => 'Tersedia 10 bidang utama, termasuk pengolahan makanan, pertanian, kaigo, restoran, perhotelan, konstruksi, manufaktur, otomotif, peternakan, dan pembersihan gedung.'],
                ],
                'sections' => [
                    $this->infoSection(
                        'Calon pekerja minimal usia 18 tahun yang ingin bekerja di Jepang.',
                        'Bahasa Jepang, budaya kerja, keterampilan bidang, ujian, dan interview.',
                        'Bahasa Jepang, skill kerja, persiapan ujian, dan interview.',
                        'Siap mengikuti seleksi kerja Jepang melalui jalur Tokutei Ginou.'
                    ),
                    [
                        'type' => 'cards',
                        'title' => '10 Bidang Pekerjaan Tokutei Ginou',
                        'description' => 'Pilih bidang kerja yang paling sesuai dengan minat, kemampuan, dan pengalaman Anda.',
                        'items' => [
                            $this->item('Pengolahan Makanan', '', 'restaurant'),
                            $this->item('Pertanian', '', 'agriculture'),
                            $this->item('Perawatan Lansia (Kaigo)', '', 'elderly'),
                            $this->item('Restoran / Layanan Makanan', '', 'local_dining'),
                            $this->item('Perhotelan', '', 'bed'),
                            $this->item('Konstruksi', '', 'construction'),
                            $this->item('Manufaktur Mesin & Peralatan', '', 'precision_manufacturing'),
                            $this->item('Otomotif', '', 'directions_car'),
                            $this->item('Peternakan', '', 'pets'),
                            $this->item('Pembersihan Gedung', '', 'cleaning_services'),
                        ],
                    ],
                    $this->checklistSection('Benefit Program', [
                        'Pelatihan bahasa Jepang dan keterampilan kerja',
                        'Persiapan ujian dan interview',
                        'Pendampingan dokumen pendaftaran',
                        'Pembekalan budaya kerja Jepang',
                        'Pilihan bidang kerja yang beragam',
                    ]),
                    $this->timelineSection($selectionFlow),
                    $this->faqSection([
                        ['Berapa usia minimal untuk mendaftar?', 'Pendaftaran terbuka mulai usia 18 tahun dengan mengikuti ketentuan seleksi program.'],
                        ['Apakah harus sudah bisa bahasa Jepang?', 'Kemampuan bahasa Jepang menjadi nilai tambah. Peserta akan mengikuti pelatihan sesuai kebutuhan program.'],
                        ['Bidang kerja apa saja yang tersedia?', 'Tersedia 10 bidang utama yang dapat dipilih sesuai ketentuan program.'],
                    ]),
                ],
            ],
            [
                'existing_id' => 7,
                'legacy_slugs' => ['engineering-gijinkoku', 'engineer-jepang-gijinkoku'],
                'slug' => 'engineer-jepang-gijinkoku',
                'nama_program' => 'Engineering (Gijinkoku)',
                'deskripsi' => 'Program persiapan kerja profesional di Jepang bagi lulusan bidang teknik. Program berfokus pada bahasa Jepang profesional, interview perusahaan, budaya kerja Jepang, dan kesiapan bekerja sebagai tenaga engineering.',
                'target_peserta' => 'Lulusan perguruan tinggi bidang Teknik Sipil, Arsitektur, Mesin, Elektro, Informatika, atau jurusan teknik lainnya.',
                'durasi' => '6-7 bulan',
                'focus' => 'Bahasa Jepang profesional, persiapan interview, budaya kerja Jepang, dan pengenalan dunia kerja engineering.',
                'output' => 'Peserta siap mengikuti seleksi dan interview perusahaan Jepang sebagai tenaga engineering.',
                'materi' => 'Bahasa Jepang profesional, kosakata engineering, etika dan budaya kerja Jepang, simulasi interview, dan persiapan dokumen.',
                'benefit' => "Pelatihan bahasa Jepang profesional\nPersiapan interview perusahaan Jepang\nPembekalan budaya kerja Jepang\nPendampingan dokumen\nPeluang kerja profesional di bidang engineering",
                'alur_seleksi' => implode("\n", array_column($selectionFlow, 'title')),
                'biaya' => 'Hubungi Admin',
                'faq' => [
                    ['q' => 'Siapa yang cocok mengikuti program Engineering?', 'a' => 'Program ini ditujukan untuk lulusan perguruan tinggi dengan latar belakang teknik.'],
                    ['q' => 'Berapa lama durasi pelatihan?', 'a' => 'Durasi pelatihan sekitar 6-7 bulan dan dapat disesuaikan dengan kebutuhan seleksi.'],
                    ['q' => 'Jurusan apa saja yang dapat mendaftar?', 'a' => 'Teknik Sipil, Arsitektur, Mesin, Elektro, Informatika, dan jurusan teknik lainnya.'],
                ],
                'sections' => [
                    $this->infoSection(
                        'Lulusan perguruan tinggi dengan latar belakang teknik.',
                        'Bahasa Jepang profesional, kosakata engineering, budaya kerja, interview, dan dokumen.',
                        'Bahasa Jepang profesional, interview, dan budaya kerja Jepang.',
                        'Siap mengikuti seleksi perusahaan Jepang sebagai tenaga engineering.'
                    ),
                    [
                        'type' => 'cards',
                        'title' => 'Bidang Engineering',
                        'description' => 'Pilihan bidang disesuaikan dengan latar belakang pendidikan dan kebutuhan perusahaan Jepang.',
                        'items' => [
                            $this->item('Teknik Sipil', '', 'foundation'),
                            $this->item('Teknik Arsitektur', '', 'architecture'),
                            $this->item('Teknik Elektro', '', 'electrical_services'),
                            $this->item('Teknik Mesin', '', 'manufacturing'),
                            $this->item('Teknik Informatika', '', 'computer'),
                            $this->item('Jurusan Teknik Lainnya', '', 'engineering'),
                        ],
                    ],
                    $this->checklistSection('Benefit Program', [
                        'Pelatihan bahasa Jepang profesional',
                        'Persiapan interview perusahaan Jepang',
                        'Pembekalan budaya kerja Jepang',
                        'Pendampingan dokumen',
                        'Peluang kerja profesional di bidang engineering',
                    ]),
                    $this->timelineSection($selectionFlow),
                    $this->faqSection([
                        ['Siapa yang cocok mengikuti program Engineering?', 'Program ini ditujukan untuk lulusan perguruan tinggi dengan latar belakang teknik.'],
                        ['Berapa lama durasi pelatihan?', 'Durasi pelatihan sekitar 6-7 bulan.'],
                        ['Jurusan apa saja yang dapat mendaftar?', 'Teknik Sipil, Arsitektur, Mesin, Elektro, Informatika, dan jurusan teknik lainnya.'],
                    ]),
                ],
            ],
            [
                'existing_id' => 8,
                'legacy_slugs' => ['kenshusei-magang-jepang', 'kenshusei-jishussei-magang-jepang'],
                'slug' => 'kenshusei-jishussei-magang-jepang',
                'nama_program' => 'Kenshusei / Jishussei (Magang Jepang)',
                'deskripsi' => 'Program persiapan bagi peserta yang ingin mengikuti magang kerja di Jepang. Peserta dibekali bahasa Jepang dasar, kesiapan fisik dan mental, kedisiplinan, budaya kerja Jepang, serta persiapan seleksi dan keberangkatan.',
                'target_peserta' => 'Peserta yang ingin mengikuti program magang Jepang, bersedia mengikuti pelatihan sebelum berangkat, dan siap ditempatkan di berbagai wilayah Jepang.',
                'durasi' => 'Menyesuaikan kebutuhan program',
                'focus' => 'Bahasa Jepang dasar, kedisiplinan, kesiapan fisik dan mental, budaya kerja Jepang, serta persiapan seleksi.',
                'output' => 'Peserta siap mengikuti proses seleksi, pelatihan, dan keberangkatan program magang Jepang.',
                'materi' => 'Bahasa Jepang dasar, percakapan harian, budaya kerja Jepang, kedisiplinan, persiapan wawancara, dan pembekalan magang.',
                'benefit' => "Persiapan bahasa Jepang\nPembekalan budaya kerja Jepang\nPersiapan seleksi magang\nPendampingan administrasi\nPersiapan keberangkatan",
                'alur_seleksi' => implode("\n", array_column($selectionFlow, 'title')),
                'biaya' => 'Hubungi Admin',
                'faq' => [
                    ['q' => 'Apa itu program Kenshusei / Jishussei?', 'a' => 'Program ini merupakan jalur magang Jepang dengan proses pelatihan dan seleksi sebelum keberangkatan.'],
                    ['q' => 'Apakah ada pelatihan sebelum berangkat?', 'a' => 'Ya, peserta wajib bersedia mengikuti pelatihan sebelum keberangkatan.'],
                    ['q' => 'Apakah harus siap ditempatkan di seluruh Jepang?', 'a' => 'Peserta perlu menyatakan kesediaan ditempatkan sesuai kebutuhan perusahaan di Jepang.'],
                ],
                'sections' => [
                    $this->infoSection(
                        'Peserta program magang Jepang yang siap mengikuti pelatihan dan penempatan.',
                        'Bahasa Jepang dasar, budaya kerja, kedisiplinan, wawancara, dan pembekalan magang.',
                        'Bahasa Jepang, kesiapan fisik dan mental, kedisiplinan, dan seleksi.',
                        'Siap mengikuti seleksi dan keberangkatan program magang Jepang.'
                    ),
                    $this->checklistSection('Benefit Program', [
                        'Persiapan bahasa Jepang',
                        'Pembekalan budaya kerja Jepang',
                        'Persiapan seleksi magang',
                        'Pendampingan administrasi',
                        'Persiapan keberangkatan',
                    ]),
                    $this->timelineSection($selectionFlow),
                    $this->faqSection([
                        ['Apa itu program Kenshusei / Jishussei?', 'Program ini merupakan jalur magang Jepang dengan proses pelatihan dan seleksi.'],
                        ['Apakah ada pelatihan sebelum berangkat?', 'Ya, peserta wajib mengikuti pelatihan sebelum keberangkatan.'],
                        ['Apakah harus siap ditempatkan di seluruh Jepang?', 'Penempatan disesuaikan dengan kebutuhan perusahaan di Jepang.'],
                    ]),
                ],
            ],
            [
                'existing_id' => 9,
                'legacy_slugs' => ['kursus-bahasa-jepang', 'kursus-bahasa-jepang-offline'],
                'slug' => 'kursus-bahasa-jepang',
                'nama_program' => 'Kursus Bahasa Jepang Offline',
                'deskripsi' => 'Program pembelajaran bahasa Jepang bagi peserta yang ingin belajar dari dasar, meningkatkan percakapan, mempersiapkan JLPT/JFT, atau mempersiapkan program kerja ke Jepang.',
                'target_peserta' => 'Peserta umum, calon pekerja ke Jepang, calon peserta Tokutei Ginou, calon peserta Engineering, atau siapa saja yang ingin meningkatkan kemampuan bahasa Jepang.',
                'durasi' => '1 bulan / fleksibel sesuai target belajar',
                'focus' => 'Percakapan, hiragana, katakana, kanji, persiapan JLPT/JFT, Tokutei Ginou, dan Engineering.',
                'output' => 'Peserta memiliki kemampuan bahasa Jepang sesuai target kelas untuk komunikasi, ujian, atau persiapan kerja ke Jepang.',
                'materi' => 'Hiragana, katakana, kanji, kosakata, percakapan, tata bahasa, latihan JLPT/JFT, dan materi persiapan kerja.',
                'benefit' => "Belajar bahasa Jepang dari dasar\nPilihan kelas sesuai kebutuhan\nPersiapan JLPT dan JFT\nPersiapan Tokutei Ginou\nPersiapan Engineering\nKonsultasi target belajar",
                'alur_seleksi' => "Pendaftaran online\nPemilihan kelas\nPengecekan level bahasa\nKonsultasi target belajar\nKonfirmasi administrasi\nMulai mengikuti kelas",
                'biaya' => 'Hubungi Admin',
                'faq' => [
                    ['q' => 'Siapa yang dapat mengikuti kursus?', 'a' => 'Kursus terbuka bagi peserta umum dan calon peserta program kerja ke Jepang.'],
                    ['q' => 'Apa saja pilihan kelasnya?', 'a' => 'Tersedia N5, N4, N3, Kaiwa, Persiapan JLPT, Persiapan TG, dan Persiapan Engineering.'],
                    ['q' => 'Berapa lama durasi kursus?', 'a' => 'Durasi sekitar 1 bulan atau fleksibel sesuai target belajar peserta.'],
                ],
                'sections' => [
                    $this->infoSection(
                        'Peserta umum dan calon peserta program kerja ke Jepang.',
                        'Huruf Jepang, kosakata, percakapan, tata bahasa, JLPT/JFT, dan persiapan kerja.',
                        'Percakapan, hiragana, katakana, kanji, serta persiapan JLPT/JFT.',
                        'Kemampuan bahasa Jepang sesuai target kelas dan kebutuhan peserta.'
                    ),
                    [
                        'type' => 'cards',
                        'title' => 'Pilihan Kelas',
                        'description' => 'Pilih kelas berdasarkan level kemampuan dan target belajar Anda.',
                        'items' => [
                            $this->item('Kelas N5', '', 'school'),
                            $this->item('Kelas N4', '', 'school'),
                            $this->item('Kelas N3', '', 'school'),
                            $this->item('Kaiwa / Percakapan', '', 'record_voice_over'),
                            $this->item('Persiapan JLPT', '', 'assignment'),
                            $this->item('Persiapan Tokutei Ginou', '', 'work'),
                            $this->item('Persiapan Engineering', '', 'engineering'),
                        ],
                    ],
                    $this->checklistSection('Benefit Program', [
                        'Belajar bahasa Jepang dari dasar',
                        'Pilihan kelas sesuai kebutuhan',
                        'Persiapan JLPT dan JFT',
                        'Persiapan Tokutei Ginou',
                        'Persiapan Engineering',
                        'Konsultasi target belajar',
                    ]),
                    $this->timelineSection([
                        $this->item('Pendaftaran online'),
                        $this->item('Pemilihan kelas'),
                        $this->item('Pengecekan level bahasa'),
                        $this->item('Konsultasi target belajar'),
                        $this->item('Konfirmasi administrasi'),
                        $this->item('Mulai mengikuti kelas'),
                    ]),
                    $this->faqSection([
                        ['Siapa yang dapat mengikuti kursus?', 'Kursus terbuka bagi peserta umum dan calon peserta program kerja ke Jepang.'],
                        ['Apa saja pilihan kelasnya?', 'Tersedia N5, N4, N3, Kaiwa, Persiapan JLPT, Persiapan TG, dan Persiapan Engineering.'],
                        ['Berapa lama durasi kursus?', 'Sekitar 1 bulan atau fleksibel sesuai target belajar.'],
                    ]),
                ],
            ],
            [
                'existing_id' => 10,
                'legacy_slugs' => ['engineer-jepang-ex-internship'],
                'slug' => 'engineer-jepang-ex-internship',
                'nama_program' => 'Engineer Jepang (Ex-Internship)',
                'deskripsi' => 'Program persiapan bagi peserta ex-magang atau peserta berpengalaman yang ingin kembali bekerja ke Jepang melalui jalur profesional. Program berfokus pada peningkatan bahasa Jepang, interview, dokumen, dan kesiapan kerja.',
                'target_peserta' => 'Peserta ex-magang Jepang, peserta berpengalaman, atau peserta berlatar belakang teknik yang ingin kembali bekerja ke Jepang.',
                'durasi' => '3 bulan',
                'focus' => 'Upgrade bahasa Jepang, persiapan kerja kembali ke Jepang, interview, dokumen kerja, dan budaya kerja Jepang.',
                'output' => 'Peserta siap mengikuti proses seleksi kerja kembali ke Jepang dengan kemampuan bahasa, dokumen, dan interview yang lebih baik.',
                'materi' => 'Bahasa Jepang lanjutan, percakapan kerja, persiapan interview, budaya kerja Jepang, dan persiapan dokumen kerja.',
                'benefit' => "Upgrade kemampuan bahasa Jepang\nPersiapan kembali bekerja ke Jepang\nPendampingan dokumen\nPersiapan interview\nPembekalan budaya kerja Jepang\nCocok untuk peserta ex-magang atau berpengalaman",
                'alur_seleksi' => implode("\n", array_column($selectionFlow, 'title')),
                'biaya' => 'Hubungi Admin',
                'faq' => [
                    ['q' => 'Siapa yang cocok mengikuti program Ex-Internship?', 'a' => 'Program ini cocok untuk peserta ex-magang Jepang atau peserta berpengalaman yang ingin kembali bekerja ke Jepang.'],
                    ['q' => 'Berapa lama durasi program?', 'a' => 'Durasi program sekitar 3 bulan.'],
                    ['q' => 'Apa fokus utama program?', 'a' => 'Fokusnya adalah peningkatan bahasa Jepang, persiapan interview, dokumen, dan budaya kerja Jepang.'],
                ],
                'sections' => [
                    $this->infoSection(
                        'Peserta ex-magang atau berpengalaman yang ingin kembali bekerja ke Jepang.',
                        'Bahasa Jepang lanjutan, percakapan kerja, interview, budaya kerja, dan dokumen.',
                        'Upgrade bahasa Jepang, persiapan kerja kembali, interview, dan dokumen.',
                        'Siap mengikuti seleksi kerja kembali ke Jepang melalui jalur profesional.'
                    ),
                    [
                        'type' => 'cards',
                        'title' => 'Latar Belakang Keahlian',
                        'description' => 'Bidang pendidikan dan pengalaman akan dicocokkan dengan kebutuhan pekerjaan di Jepang.',
                        'items' => [
                            $this->item('Teknik Mesin', '', 'manufacturing'),
                            $this->item('Teknik Elektro', '', 'electrical_services'),
                            $this->item('Teknik Sipil', '', 'foundation'),
                            $this->item('Bidang Teknik Lainnya', '', 'engineering'),
                        ],
                    ],
                    $this->checklistSection('Benefit Program', [
                        'Upgrade kemampuan bahasa Jepang',
                        'Persiapan kembali bekerja ke Jepang',
                        'Pendampingan dokumen',
                        'Persiapan interview',
                        'Pembekalan budaya kerja Jepang',
                        'Cocok untuk peserta ex-magang atau berpengalaman',
                    ]),
                    $this->timelineSection($selectionFlow),
                    $this->faqSection([
                        ['Siapa yang cocok mengikuti program Ex-Internship?', 'Peserta ex-magang atau peserta berpengalaman yang ingin kembali bekerja ke Jepang.'],
                        ['Berapa lama durasi program?', 'Durasi program sekitar 3 bulan.'],
                        ['Apa fokus utama program?', 'Peningkatan bahasa Jepang, interview, dokumen, dan budaya kerja Jepang.'],
                    ]),
                ],
            ],
        ];
    }

    private function infoSection(string $target, string $material, string $focus, string $output): array
    {
        return [
            'type' => 'info_grid',
            'title' => 'Informasi Program',
            'description' => '',
            'items' => [
                $this->item('Target Peserta', $target, 'groups'),
                $this->item('Materi Utama', $material, 'menu_book'),
                $this->item('Fokus Pelatihan', $focus, 'track_changes'),
                $this->item('Output Program', $output, 'verified'),
            ],
        ];
    }

    private function checklistSection(string $title, array $items): array
    {
        return [
            'type' => 'checklist',
            'title' => $title,
            'description' => '',
            'items' => array_map(fn ($item) => $this->item($item, '', 'check'), $items),
        ];
    }

    private function timelineSection(array $items): array
    {
        return [
            'type' => 'timeline',
            'title' => 'Alur Pendaftaran dan Seleksi',
            'description' => '',
            'items' => $items,
        ];
    }

    private function faqSection(array $items): array
    {
        return [
            'type' => 'faq',
            'title' => 'FAQ',
            'description' => '',
            'items' => array_map(
                fn ($item) => $this->item($item[0], $item[1], 'help'),
                $items
            ),
        ];
    }

    private function item(string $title, string $description = '', string $icon = ''): array
    {
        return compact('title', 'description', 'icon');
    }
}
