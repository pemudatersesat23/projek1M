<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        Berita::query()->delete();

        $beritas = [
            [
                'judul' => [
                    'id' => 'Kisah Sukses Alumni LPK Kizuku Merintis Karier Tokutei Ginou di Sektor Restoran Osaka',
                    'jp' => '大阪の飲食部門で特定技能キャリアをスタートさせたLPK Kizuku卒業生の成功ストーリー'
                ],
                'kategori' => 'kat-alumni',
                'lokasi' => 'Osaka, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Perjalanan karier di luar negeri bukanlah hal yang mudah, namun dengan tekad yang kuat dan persiapan yang matang, impian tersebut bisa terwujud. Inilah yang dibuktikan oleh Eko Santoso, salah satu alumni LPK Kizuku International Academy yang kini telah sukses bekerja di Osaka, Jepang, melalui jalur Tokutei Ginou (Specified Skilled Worker) di sektor pelayanan restoran. Eko membagikan kisahnya mengenai bagaimana pelatihan intensif di LPK Kizuku membantunya beradaptasi dengan cepat di lingkungan kerja barunya.

Sebelum berangkat ke Jepang, Eko mengikuti pelatihan bahasa dan budaya Jepang selama kurang lebih 5 bulan di LPK Kizuku. Pelatihan ini tidak hanya fokus pada tata bahasa untuk kelulusan ujian JFT-Basic dan Skill Assessment Test, tetapi juga menekankan pada kemampuan percakapan sehari-hari serta etika kerja di Jepang (Kaiwa dan Business Manners). Hal ini terbukti sangat krusial saat Eko mulai bekerja di restoran terkemuka di Osaka, di mana ia dituntut berinteraksi langsung dengan pelanggan lokal Jepang setiap hari.

Eko menuturkan bahwa pada minggu-minggu pertama bekerja, ia sempat merasa gugup karena kecepatan bicara orang Osaka yang khas dengan dialek Kansai-ben. Namun, berkat pondasi bahasa dan simulasi wawancara serta komunikasi yang sering dipraktikkan di kelas Kizuku, ia mampu mengatasi tantangan tersebut dalam waktu singkat. Rekan kerja dan atasannya di Jepang sangat mengapresiasi kesopanan dan kesiapan kerja Eko, yang menurut mereka jauh lebih matang dibanding pekerja asing dari jalur lain.

Melalui keberhasilan ini, LPK Kizuku membuktikan komitmennya untuk terus melahirkan tenaga kerja profesional yang siap bersaing secara global. Bagi rekan-rekan yang ingin mengikuti jejak Eko, kuncinya adalah disiplin dalam belajar dan memanfaatkan setiap sesi konsultasi serta latihan yang disediakan oleh instruktur di LPK Kizuku. Peluang kerja di Jepang kini terbuka sangat lebar, dan persiapan yang tepat bersama lembaga yang terpercaya adalah langkah pertama menuju kesuksesan karier internasional Anda.',
                    'jp' => '海外でのキャリアは簡単ではありませんが、強い決意と十分な準備があれば、その夢は実現できます。これは、LPK Kizuku International Academyの卒業生であり、特定技能制度を通じて大阪の飲食サービス部門で働いているEko Santosoさんによって証明されました。

日本へ出発する前、EkoさんはLPK Kizukuで約5ヶ月間、日本語と日本文化の研修を受けました。この研修は、JFT-Basicや技能測定試験に合格するための文法だけでなく、日常会話や日本での労働倫理（ビジネスマナー）にも重点を置いていました。

Ekoさんは、働き始めて最初の数週間は、関西弁独特のスピードに緊張したと語っています。しかし、Kizukuの授業で培った語学の基礎とシミュレーションのおかげで、短期間でその課題を克服することができました。

この成功を通じて、LPK Kizukuはグローバルに活躍できるプロフェッショナルな人材を育成し続けるというコミットメントを証明しました。日本での就職機会は現在非常に広く開かれており、信頼できる機関での適切な準備が国際的なキャリア構築への第一歩となります。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Panduan Lengkap Mempersiapkan Ujian JFT-Basic dan Ujian Keterampilan Tokutei Ginou',
                    'jp' => 'JFT-Basic試験および特定技能評価試験の完全準備ガイド'
                ],
                'kategori' => 'kat-tips',
                'lokasi' => 'Gowa, Sulawesi Selatan',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Bagi Anda yang berencana untuk bekerja di Jepang melalui jalur Tokutei Ginou (Specified Skilled Worker), kelulusan dalam dua ujian utama adalah syarat mutlak yang harus dipenuhi. Kedua ujian tersebut adalah JFT-Basic (Japan Foundation Test for Basic Japanese) atau alternatifnya JLPT N4, serta Ujian Evaluasi Keterampilan (Skill Assessment Test) sesuai bidang pekerjaan yang Anda pilih. Mempersiapkan kedua ujian ini memerlukan strategi belajar yang sistematis dan fokus tinggi agar bisa lulus dalam sekali percobaan.

Pertama, mari kita bahas persiapan untuk JFT-Basic. Ujian ini mengukur kemampuan komunikasi bahasa Jepang yang diperlukan dalam kehidupan sehari-hari. Format ujian ini berbasis komputer (CBT/Computer Based Testing) dengan fokus pada empat area: Huruf dan Kosakata, Percakapan dan Ungkapan, Mendengar, serta Membaca. LPK Kizuku menyarankan Anda untuk membiasakan diri menggunakan buku teks "Irodori: Seikatsu no Nihongo" yang disediakan gratis oleh Japan Foundation. Buku ini sangat relevan karena situasi yang ditampilkan dalam buku tersebut mencerminkan kehidupan nyata di Jepang. Latihlah pendengaran Anda secara konsisten setiap hari dengan mendengarkan audio latihan agar terbiasa dengan intonasi penutur asli.

Kedua, Ujian Keterampilan sesuai bidang pilihan Anda (seperti Pengolahan Makanan, Kaigo/Caregiver, atau Pertanian). Ujian ini biasanya tersedia dalam pilihan bahasa Indonesia di beberapa lokasi ujian, namun memiliki istilah-istilah teknis khusus industri yang tetap menggunakan bahasa Jepang dalam format Katakana. Kunci utama untuk lulus ujian keterampilan ini adalah mempelajari modul resmi (textbook) bidang terkait yang diterbitkan oleh asosiasi penyelenggara ujian di Jepang. Di LPK Kizuku, para instruktur berpengalaman membimbing Anda membahas soal-soal latihan tahun sebelumnya serta memberikan simulasi praktis agar Anda tidak gugup saat hari ujian tiba.

Terakhir, manajemen waktu dan mental saat menghadapi tes berbasis komputer sangat menentukan. Banyak peserta gagal bukan karena tidak menguasai materi, melainkan karena panik kehabisan waktu atau kesulitan mengoperasikan sistem CBT. Latihan simulasi ujian (Try Out) secara berkala di LPK Kizuku dirancang khusus menyerupai kondisi ujian asli agar Anda memiliki kepercayaan diri penuh. Mulailah persiapan Anda dari sekarang, buatlah jadwal belajar yang disiplin, dan pastikan Anda mendapatkan bimbingan dari mentor yang berpengalaman.',
                    'jp' => '特定技能の制度を通じて日本で働くことを計画している方にとって、JFT-Basic試験と技能測定試験の合格は必須条件です。これらの試験対策には、計画的かつ集中した学習戦略が必要です。

まず、JFT-Basic対策について説明します。この試験は、日常生活に必要な日本語コミュニケーション能力を測定します。コンピューターを使用したテスト形式（CBT）で行われます。「いろどり：生活の日本語」のテキストを使用することをお勧めします。

次に、分野別の技能測定試験についてです。日本語の専門用語やカタカナ表記を理解することが合格の鍵となります。LPK Kizukuでは、過去の模擬問題を活用した対策を行っています。

最後に、時間管理とメンタル面の準備が重要です。定期的な模擬試験を通じて自信をつけ、本番に臨みましょう。適切なガイダンスを受けることが合格への近道です。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Peluang Kerja Sektor Pengolahan Makanan di Jepang Semakin Terbuka Lebar untuk Pemuda Indonesia',
                    'jp' => '日本の食品加工部門での雇用機会がインドネシアの若者に向けて拡大中'
                ],
                'kategori' => 'kat-info',
                'lokasi' => 'Tokyo, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Sektor industri makanan dan minuman di Jepang saat ini sedang menghadapi tantangan kekurangan tenaga kerja yang cukup serius akibat fenomena penuaan populasi (aging population). Untuk mengatasi masalah ini, Pemerintah Jepang secara aktif membuka keran penerimaan pekerja asing terampil melalui program Tokutei Ginou, khususnya di bidang pengolahan makanan dan minuman (Food and Beverage Manufacture). Bidang ini menjadi salah satu primadona dan sangat diminati oleh para pencari kerja dari Indonesia karena persyaratannya yang relatif ramah bagi pemula.

Bekerja di pabrik pengolahan makanan di Jepang menawarkan banyak keuntungan, mulai dari standar keselamatan kerja yang sangat tinggi, lingkungan kerja yang higienis dan modern, hingga kompensasi gaji yang menarik. Tugas umum pekerja di sektor ini meliputi persiapan bahan baku, pengolahan, pengemasan produk, hingga kontrol kualitas produk makanan sebelum didistribusikan. Semua proses dijalankan dengan standar sanitasi ketat yang dikenal sebagai HACCP (Hazard Analysis Critical Control Point), yang juga menjadi materi wajib dalam ujian keterampilan industri tersebut.

Bagi pemuda Indonesia, sektor ini menawarkan stabilitas karier yang baik dengan kontrak kerja hingga 5 tahun. Selain itu, keterampilan manajerial dan teknologi pengolahan pangan yang dipelajari selama bekerja di Jepang akan menjadi modal berharga bagi mereka sekembalinya ke tanah air untuk membuka usaha kuliner mandiri. LPK Kizuku International Academy memfasilitasi kelas persiapan khusus untuk program pengolahan makanan ini, mulai dari bimbingan bahasa, pendaftaran ujian, hingga pencocokan (matching) kerja dengan perusahaan-perusahaan terkemuka di Jepang.

Peluang emas ini sebaiknya tidak dilewatkan oleh generasi muda yang ingin mengubah masa depan mereka. Persiapan mental, fisik, dan penguasaan bahasa Jepang dasar adalah kunci utama yang harus dipersiapkan sejak dini. Dengan bimbingan yang tepat dari lembaga yang profesional seperti LPK Kizuku, langkah Anda untuk meniti karier cemerlang di industri makanan Jepang akan terasa lebih mudah dan terarah.',
                    'jp' => '日本の食品製造業は、少子高齢化による深刻な労働力不足に直面しています。この課題を解決するため、特定技能制度を通じた外国人材の受け入れが積極的に行われています。

食品加工部門での仕事は、高い安全基準、クリーンな環境、そして魅力的な待遇が特徴です。主な仕事内容は、原材料の準備、加工、包装、品質管理などです。

インドネシアの若者にとって、このセクターは最長5年間の安定したキャリアを提供します。日本で学ぶ先進技術や衛生管理は、帰国後のキャリアにも大きな財産となります。

LPK Kizukuでは、言語学習から面接対策、求人マッチングまで一貫したサポートを提供しています。適切なガイダンスのもとで、日本でのキャリアをスタートさせましょう。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Pentingnya Memahami Budaya Kerja Jepang (Business Manners) Sebelum Mulai Bekerja',
                    'jp' => '就業前に理解しておくべき日本のビジネスマナーの重要性'
                ],
                'kategori' => 'kat-tips',
                'lokasi' => 'Kyoto, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Banyak pekerja asing di Jepang yang mengalami kesulitan di tempat kerja baru mereka bukan karena kurangnya keterampilan teknis, melainkan karena kesalahpahaman budaya kerja atau etika bisnis setempat. Masyarakat Jepang sangat menjunjung tinggi keharmonisan kelompok, kedisiplinan waktu, dan rasa hormat yang mendalam kepada rekan kerja serta pelanggan. Oleh karena itu, mempelajari etika bisnis Jepang (Business Manners/ビジネスマナー) sebelum Anda menginjakkan kaki di Jepang adalah langkah persiapan yang sangat penting.

Salah satu pilar utama dalam etika bisnis Jepang adalah konsep "Hou-Ren-So" yang merupakan singkatan dari Houkoku (Melaporkan),連絡 Renraku (Menghubungi), dan Soudan (Berkonsultasi). Dalam operasional kerja sehari-hari, Anda diharapkan selalu melaporkan perkembangan tugas Anda secara berkala kepada atasan, segera menghubungi pihak terkait jika terjadi kendala, serta berkonsultasi sebelum mengambil keputusan penting. Konsep ini menjamin transparansi kerja dan meminimalkan kesalahan fatal yang bisa merugikan tim.

Selain "Hou-Ren-So", ketepatan waktu (Punctuality) adalah hal sakral di Jepang. Prinsip "5 menit sebelum waktu yang ditentukan" adalah standar minimum yang harus ditaati. Jika jam kerja dimulai pukul 08.00, maka Anda harus sudah siap di meja kerja atau posisi kerja Anda pada pukul 07.55. Terlambat tanpa alasan yang jelas atau tanpa memberikan kabar terlebih dahulu dianggap sebagai tindakan tidak bertanggung jawab yang dapat merusak kepercayaan perusahaan terhadap Anda secara instan.

Di LPK Kizuku International Academy, pengenalan etika kerja ini merupakan kurikulum wajib yang diintegrasikan ke dalam kelas bahasa harian. Siswa dilatih bagaimana cara membungkuk (Ojigi) yang benar sesuai situasinya, cara menerima kartu nama, serta menggunakan bahasa Jepang formal (Keigo) saat berbicara dengan atasan. Dengan memahami nilai-nilai budaya kerja ini, alumni Kizuku terbukti mampu beradaptasi lebih cepat dan memiliki hubungan kerja yang harmonis dengan rekan kerja lokal di Jepang.',
                    'jp' => '日本の職場では、技術的なスキルだけでなく、ビジネスマナーや企業文化の理解が非常に重視されます。グループの調和、時間の遵守、そして他者への敬意が基本となります。

ビジネスコミュニケーションの基本となるのが「報・連・相（ほうれんそう）」です。報告、連絡、相談を徹底することで、業務の効率化とトラブルの未予防が可能になります。

また、日本の職場において時間は極めて厳格に管理されます。「5分前行動」は基本のルールです。遅刻は信頼関係を大きく損なう要因となります。

LPK Kizukuでは、お辞儀の仕方や敬語の使い方など、日本の就労現場で即戦力となるマナー研修を提供しています。これらを事前に学ぶことで、スムーズな職場適応が可能になります。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'LPK Kizuku Buka Kelas Persiapan Bahasa Jepang Batch 2026 Semester Pertama',
                    'jp' => 'LPK Kizukuが2026年度第1期日本語準備クラスの受講生募集を開始'
                ],
                'kategori' => 'kat-promo',
                'lokasi' => 'Gowa, Sulawesi Selatan',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'LPK Kizuku International Academy kembali membuka pendaftaran untuk kelas persiapan bahasa Jepang intensif program kerja Jepang periode Semester Pertama Tahun 2026. Kelas ini dirancang khusus bagi lulusan SMA/SMK sederajat serta alumni Universitas yang berminat untuk membangun masa depan cerah dengan bekerja di Jepang melalui jalur Tokutei Ginou maupun Program Magang (Internship). Program pelatihan ini akan berlangsung secara tatap muka dengan kurikulum terstruktur yang disesuaikan dengan kebutuhan industri modern di Jepang.

Pelatihan akan dilaksanakan selama 5 bulan penuh, mencakup pembelajaran bahasa Jepang dasar (tingkat N5 hingga N4), latihan percakapan intensif (Kaiwa), serta pembekalan budaya dan etika kerja Jepang. Siswa juga akan dipandu secara bertahap dalam melakukan pendaftaran ujian bahasa JFT-Basic dan ujian keterampilan sesuai bidang minat masing-masing. Fasilitas pendukung di asrama Kizuku yang nyaman dan representatif menjamin para peserta didik dapat fokus belajar dengan maksimal tanpa hambatan jarak.

Keunggulan utama mengikuti program pelatihan di LPK Kizuku adalah adanya jaminan penyaluran kerja (job matching) yang andal dengan mitra perusahaan penerima (Sending Organization & Supervising Organization) resmi di Jepang. Tim manajemen Kizuku akan mendampingi proses pencocokan kerja, bimbingan wawancara langsung dengan user Jepang, hingga pengurusan berkas administrasi Visa Tokutei Ginou sampai keberangkatan selesai. Hal ini meminimalkan kecemasan siswa dan memastikan proses transisi berjalan lancar.

Pendaftaran untuk Semester Pertama 2026 ini dibuka terbatas untuk menjaga kualitas pembelajaran yang kondusif di kelas. Bagi Anda yang memiliki komitmen kuat untuk sukses bekerja di Jepang, segera hubungi admin pendaftaran LPK Kizuku atau kunjungi kantor pusat kami untuk mengikuti seleksi masuk. Jangan lewatkan kesempatan emas ini untuk meraih karier internasional yang mapan bersama lembaga bimbingan terbaik.',
                    'jp' => 'LPK Kizuku International Academyは、2026年度第1期の日本語準備クラスの募集を開始しました。このクラスは、特定技能や技能実習での日本就職を目指す高校・大学卒業生を対象としています。

5ヶ月間の研修期間中、日本語の基礎から会話、ビジネスマナーまで体系的に学びます。試験の出願サポートや面接対策も充実しています。

当アカデミーの強みは、日本の提携企業や監理団体との直接のマッチングと、ビザ申請から出国までの手厚いサポート体制です。

高品質な学習環境を維持するため、定員に達し次第募集を締め切ります。日本でのキャリアを真剣に考える方は、今すぐお問い合わせください。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Mengenal Program Kaigo (Caregiver) Tokutei Ginou: Pekerjaan Mulia dengan Gaji Menjanjikan',
                    'jp' => '特定技能「介護」について知る：やりがいと安定した待遇を備えた仕事'
                ],
                'kategori' => 'kat-info',
                'lokasi' => 'Tokyo, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Program perawatan lansia atau Kaigo (Caregiver) merupakan salah satu sektor pekerjaan paling prioritas dan memiliki kuota penerimaan terbesar dalam skema program Tokutei Ginou di Jepang. Hal ini disebabkan oleh meningkatnya populasi lansia di Jepang yang tidak sebanding dengan ketersediaan tenaga perawat lokal. Bagi pemuda-pemudi Indonesia, tantangan sosial di Jepang ini menjadi peluang emas untuk mendapatkan pekerjaan profesional yang mulia sekaligus menawarkan paket remunerasi yang sangat kompetitif.

Tugas seorang Kaigo di panti jompo Jepang mencakup membantu aktivitas harian para lansia, seperti makan, mandi, mobilitas, serta mendampingi rekreasi sosial mereka di dalam panti. Pekerjaan ini menuntut empati yang tinggi, kesabaran, dan kemampuan komunikasi bahasa Jepang yang baik agar dapat berinteraksi dengan ramah kepada para lansia. Pemerintah Jepang juga menyediakan berbagai tunjangan tambahan bagi perawat asing yang rajin belajar untuk meningkatkan kualifikasi sertifikasi profesi mereka.

Salah satu keunggulan terbesar dari sektor Kaigo adalah tidak adanya persyaratan latar belakang pendidikan keperawatan formal dari tanah air untuk mendaftar jalur Tokutei Ginou. Lulusan bidang apa pun dapat mendaftar asalkan lulus ujian bahasa Jepang dan Ujian Evaluasi Keterampilan Caregiver (Caregiver Skill Evaluation Test). LPK Kizuku International Academy menyediakan modul ajar khusus bidang Kaigo, lengkap dengan istilah-istilah medis dasar Jepang dan latihan simulasi perawatan praktis di laboratorium pelatihan.

Karier sebagai perawat lansia di Jepang tidak hanya menjanjikan dari segi materi, tetapi juga memberikan kepuasan batin karena membantu sesama. Keterampilan profesional yang Anda peroleh selama bekerja di fasilitas kesehatan standar Jepang akan sangat dihargai dan menjadi bekal berharga di industri pelayanan kesehatan global. Jika Anda tertarik, mulailah langkah awal Anda bersama kelas intensif Kaigo di LPK Kizuku.',
                    'jp' => '介護セクターは、日本の高齢化に伴い、特定技能制度において最も需要の高い分野の一つです。インドネシアの若者にとって、これは国際的なキャリアを築く絶好のチャンスです。

主な業務内容は、日常生活の支援、入浴や食事の介助、コミュニケーションなどです。この仕事には、思いやりと高いコミュニケーション能力が求められます。

看護専門の学歴がなくても、必要な試験（日本語試験および介護技能評価試験）に合格すれば就業が可能です。LPK Kizukuでは専門的な対策を提供しています。

日本での介護実務経験は、将来的なグローバルヘルスケア分野での活躍に向けた大きな強みとなります。Kizukuで第一歩を踏み出しましょう。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Tips Sukses Lolos Wawancara (Mensetsu) Kerja dengan User Perusahaan Jepang',
                    'jp' => '日本企業との面接（面接）を突破するための成功の秘訣'
                ],
                'kategori' => 'kat-tips',
                'lokasi' => 'Fukuoka, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Setelah Anda menyelesaikan pelatihan bahasa Jepang dan lulus ujian keahlian, tahap akhir yang paling menentukan sebelum keberangkatan adalah proses wawancara kerja (Mensetsu/面接) langsung dengan perwakilan dari perusahaan Jepang. Wawancara dengan orang Jepang memiliki keunikan dan standar penilaian tersendiri yang sangat berbeda dengan wawancara kerja di Indonesia. Persiapan etika, mental, dan cara berbicara yang tepat adalah kunci utama agar Anda dapat memikat perhatian user Jepang sejak menit pertama.

Pertama, perhatikan etika penampilan dan postur tubuh Anda (Attitude). Perusahaan Jepang sangat menilai kerapian dan kesopanan. Pastikan Anda mengenakan pakaian formal yang rapi (setelan jas gelap dan kemeja putih), rambut tertata rapi, dan wajah tampak segar. Saat memasuki ruang wawancara online maupun langsung, bungkukkan badan (Ojigi) dengan sopan dan sapalah pewawancara dengan suara yang tegas dan jelas. Duduklah dengan posisi tegak, letakkan tangan di atas paha, dan pertahankan kontak mata yang bersahabat selama wawancara berlangsung.

Kedua, persiapkan jawaban untuk pertanyaan-pertanyaan dasar yang sering diajukan. Pertanyaan seperti perkenalan diri (Jiko Shoukai), alasan ingin bekerja di Jepang (Shibou Douki), serta kelebihan dan kekurangan diri adalah hal wajib yang harus Anda kuasai pelafalannya dalam bahasa Jepang yang lancar. Hindari menghafal jawaban secara kaku; sebaliknya, tunjukkan antusiasme dan ketulusan Anda untuk bekerja keras serta belajar budaya baru di perusahaan mereka. Gunakan kalimat penutup yang menunjukkan kesiapan kerja Anda dengan optimis.

LPK Kizuku International Academy memfasilitasi sesi simulasi wawancara tatap muka (Mock Interview) secara berkala bagi setiap siswa yang akan menghadapi jadwal interview asli. Instruktur kami melatih kepercayaan diri siswa, membetulkan intonasi suara, hingga memberikan koreksi tata bahasa langsung agar presentasi diri siswa optimal. Melalui latihan simulasi yang konsisten di Kizuku, peluang Anda untuk lolos dalam wawancara kerja pertama akan meningkat secara signifikan.',
                    'jp' => '特定技能の就職プロセスにおいて、日本企業との面接は最終にして最も重要な関門です。日本のビジネススタイルに合わせた事前のマナー対策が合否を左右します。

まず、第一印象（身だしなみと姿勢）が極めて重視されます。清潔感のある服装とお辞儀（おじぎ）、大きな声での挨拶を心がけましょう。

次に、自己紹介や志望動機など、よく聞かれる質問に対する準備が必要です。熱意と誠実さを伝えることが最も大切です。

LPK Kizukuでは、面接シミュレーション（模擬面接）を通じて、学生の自信向上と適切な表現方法の指導を行っています。繰り返しの練習が合格率を高めます。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Perbedaan Utama Jalur Mandiri Tokutei Ginou dan Program Magang Jepang Tradisional',
                    'jp' => '特定技能と技能実習の違い：自分に適したルートの選択'
                ],
                'kategori' => 'kat-info',
                'lokasi' => 'Makassar, Sulawesi Selatan',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Bagi calon pekerja migran Indonesia yang ingin mengadu nasib ke Jepang, sering kali merasa bingung dalam memilih jalur keberangkatan yang tepat antara program Tokutei Ginou (Specified Skilled Worker) dan program Magang Tradisional (Jisshusei/Technical Intern Training). Kedua jalur ini diatur oleh regulasi pemerintah Jepang yang berbeda dan memiliki karakteristik hak serta kewajiban yang sangat bertolak belakang. Memahami perbedaan utama ini penting agar pilihan Anda sesuai dengan rencana karier masa depan Anda.

Perbedaan mendasar pertama terletak pada status kepegawaian dan besaran remunerasi (gaji). Pada program Magang (Jisshusei), fokus utamanya adalah transfer teknologi dan pelatihan, sehingga peserta berstatus sebagai pemagang dengan standar uang saku yang sering kali berada di batas minimum. Sementara itu, program Tokutei Ginou menganggap Anda sebagai pekerja terampil kontrak penuh dengan hak gaji dan tunjangan yang setara dengan pekerja lokal Jepang di posisi yang sama. Hal ini membuat kompensasi finansial jalur Tokutei Ginou jauh lebih menjanjikan.

Perbedaan kedua adalah kebebasan untuk pindah tempat kerja dan durasi kontrak. Pemagang (Jisshusei) tidak diperkenankan untuk pindah perusahaan selama masa kontrak 3 tahun terlepas dari kondisi kerja, kecuali dalam keadaan luar biasa seperti kebangkrutan perusahaan. Sebaliknya, pekerja Tokutei Ginou memiliki kebebasan untuk mengajukan pindah kerja ke perusahaan lain di sektor industri yang sama jika mengalami ketidakcocokan. Durasi kerja Tokutei Ginou juga lebih panjang, yaitu hingga 5 tahun penuh dan berpeluang diperpanjang ke tingkat lanjut (Tokutei Ginou No. 2).

LPK Kizuku International Academy memandu Anda secara transparan untuk menentukan jalur terbaik yang sesuai dengan profil dan kemampuan Anda. Baik program Magang maupun Tokutei Ginou memiliki kelebihan tersendiri tergantung kesiapan bahasa dan keterampilan awal yang Anda miliki. Konsultasikan masa depan Anda bersama tim mentor Kizuku agar langkah keberangkatan Anda terencana dengan aman dan legal.',
                    'jp' => '日本での就労を目指すインドネシアの人々にとって、特定技能と技能実習の違いを理解することは重要です。それぞれ異なる目的と待遇があります。

技能実習（実習生）は技術移転を目的としており、手当などの待遇に一定の制限があります。一方、特定技能は即戦力としての就労ビザであり、日本国民と同等以上の給与水準が保証されます。

また、技能実習生は原則として転籍（転職）ができませんが、特定技能労働者は同分野内での転職が認められています。就労可能期間も特定技能の方が長くなります。

LPK Kizukuでは、それぞれの希望やスキルに応じた最適な就労プランを提示し、確実な手続きをサポートしています。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Kunci Disiplin Belajar Bahasa Jepang Mandiri bagi Pemula di Asrama LPK Kizuku',
                    'jp' => 'LPK Kizuku寄宿舎における初心者のための日本語自習の秘訣'
                ],
                'kategori' => 'kat-tips',
                'lokasi' => 'Gowa, Sulawesi Selatan',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Belajar bahasa Jepang dari nol hingga mencapai tingkat kemahiran komunikatif sehari-hari seringkali dianggap sebagai tantangan yang berat bagi sebagian besar pemula. Selain karena perbedaan struktur kalimat yang drastis dibanding bahasa Indonesia, keberadaan sistem huruf Jepang (Hiragana, Katakana, dan Kanji) membutuhkan daya ingat dan ketekunan ekstra. Di lingkungan asrama LPK Kizuku International Academy, kedisiplinan belajar mandiri secara terstruktur adalah rahasia utama mengapa para siswa kami mampu menguasai materi dengan cepat.

Metode belajar mandiri yang efektif dimulai dengan pembagian target harian yang realistis. Instruktur LPK Kizuku menyarankan siswa untuk menghafal minimal 15 kosakata baru dan 3 huruf Kanji setiap pagi sebelum kelas dimulai. Pembiasaan kecil ini jika dilakukan secara konsisten selama 5 bulan akan membentuk perbendaharaan kata yang sangat kaya. Manfaatkan kartu flash (flashcards) fisik atau aplikasi digital seperti Anki untuk memudahkan proses pengulangan (spaced repetition) kosakata yang sulit dihafal saat waktu luang Anda di asrama.

Selain hafalan tulisan, melatih kemampuan pendengaran (Choukai) adalah aspek penting yang tidak boleh diabaikan. Lingkungan asrama Kizuku dirancang untuk mendorong atmosfer berbahasa Jepang dengan menyediakan area khusus bercakap-cakap (English/Japanese Zone). Cobalah untuk mempraktikkan ungkapan yang baru Anda pelajari di kelas dengan rekan sekamar Anda, atau tontonlah tayangan video situasi kehidupan di Jepang tanpa terjemahan bahasa Indonesia untuk melatih kepekaan telinga Anda menangkap intonasi asli.

Terakhir, evaluasi diri secara berkala sangat penting untuk mengukur perkembangan belajar Anda. Jangan takut melakukan kesalahan tata bahasa saat mencoba berbicara, karena dari kesalahan itulah proses belajar yang sesungguhnya terjadi. Para instruktur di LPK Kizuku selalu terbuka untuk memberikan bimbingan tambahan dan konsultasi belajar bagi siswa yang merasa tertinggal. Dengan kedisiplinan yang konsisten dan bimbingan terarah, penguasaan bahasa Jepang bukan lagi hal yang mustahil untuk diraih.',
                    'jp' => '日本語の学習、特に漢字や独自の文法構造のマスターには、一貫した自習の習慣が不可欠です。Kizukuの寄宿舎環境は、この学習効率を最大化する設計となっています。

効果的な学習方法として、毎日15の語彙と3つの漢字を覚える習慣づけを推奨しています。復習用アプリの活用なども有効です。

会話力の向上には、学んだ表現を宿舎内の仲間と実際に使ってみることが効果的です。間違えることを恐れずに積極的に使いましょう。

LPK Kizukuでは、学生の理解度に応じたサポート体制を整えており、個別相談や補習も実施しています。'
                ]
            ],
            [
                'judul' => [
                    'id' => 'Pendaftaran Program Kerja Pertanian Jepang Bidang Hortikultura Resmi Dibuka Kembali',
                    'jp' => '日本の農業（施設園芸）分野特定技能生の公式募集が再開'
                ],
                'kategori' => 'kat-promo',
                'lokasi' => 'Osaka, Jepang',
                'status_publish' => 'published',
                'isi' => [
                    'id' => 'Kabar gembira bagi Anda yang memiliki minat tinggi bekerja di sektor luar ruangan dan pertanian modern. LPK Kizuku International Academy secara resmi mengumumkan pembukaan pendaftaran untuk program kerja pertanian Jepang (Horticulture & Crop Farming) jalur Tokutei Ginou periode keberangkatan tahun ini. Sektor pertanian di Jepang saat ini menerapkan teknologi pertanian modern tingkat tinggi, sehingga sangat nyaman, teratur, dan efisien bagi pekerja usia muda.

Kontrak kerja untuk program pertanian ini berkisar antara 3 hingga 5 tahun penuh dengan fasilitas akomodasi panti yang disubsidi oleh perusahaan penerima di Jepang. Tugas umum pekerja mencakup penanaman bibit di dalam rumah kaca (greenhouse), pemeliharaan tanaman hortikultura menggunakan sistem otomatis, hingga proses pemanenan dan pengepakan buah atau sayuran segar standar ekspor. Sektor ini juga memiliki salah satu tingkat kelulusan wawancara tertinggi bagi kandidat pemula asal Indonesia.

Persyaratan mendaftar program ini cukup mudah, yaitu usia minimal 18 tahun, memiliki kondisi fisik yang prima untuk aktivitas outdoor, serta lulus sertifikat ujian bahasa JFT-Basic dan Ujian Keterampilan Pertanian Jepang (ASAT/Agriculture Skill Assessment Test). LPK Kizuku menyelenggarakan pelatihan bahasa intensif di asrama serta menyediakan materi latihan khusus soal-soal ASAT edisi terbaru untuk mempermudah persiapan kelulusan ujian para siswa bimbingan kami.

Kesempatan untuk bekerja di tengah lingkungan alam Jepang yang asri dengan teknologi pertanian termaju di dunia adalah kesempatan langka yang sangat berharga. Selain penghasilan yang mapan, ilmu pertanian modern yang Anda peroleh dapat menjadi modal sukses untuk mendirikan agribisnis mandiri di Indonesia kelak. Segera lakukan konsultasi gratis dan amankan kuota pendaftaran Anda bersama LPK Kizuku International Academy sekarang juga.',
                    'jp' => '日本の最先端農業セクターでの就労を希望するインドネシアの人材に向けて、特定技能「農業」の募集を開始しました。現代の日本農業はハイテク化が進んでおり、若者にとっても魅力的な環境です。

最長5年の雇用契約のもと、温室ハウスでの栽培管理、収穫、製品の出荷梱包作業などに従事します。生活環境（寮）もサポートされます。

応募には、満18歳以上で身体が健康であること、日本語試験および農業技能測定試験（ASAT）の合格が必要です。Kizukuでは専門の講座を開設して合格を支援します。

世界水準の農業技術を学びながら、安定した収入を得られるこのチャンスに、ぜひLPK Kizukuを通じて挑戦してください。'
                ]
            ]
        ];

        foreach ($beritas as $data) {
            Berita::create($data);
        }
    }
}
