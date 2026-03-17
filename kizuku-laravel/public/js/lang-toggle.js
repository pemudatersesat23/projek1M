/* ═══ Language Toggle — Full Website Translation ═══ */
document.addEventListener('DOMContentLoaded', function () {

    /* ──────────────────────────────────────────
     *  TRANSLATION DICTIONARY
     * ────────────────────────────────────────── */
    var T = {
        id: {
            /* ── Nav ── */
            nav: ['Beranda', 'Program', 'Keunggulan', 'Testimoni', 'Kontak'],
            cta_konsultasi: 'Konsultasi',
            cta_daftar: 'Daftar Sekarang',

            /* ── Hero ── */
            hero_pill: 'Terpercaya sejak 2014 \u00a0·\u00a0 🇯🇵 Jepang',
            hero_h1: 'Wujudkan Karier<br><span class="line-accent">Impian di Jepang</span><br>Bersama Kami',
            hero_sub: 'LPK Kizuku International Academy hadir untuk mempersiapkan kamu dengan pelatihan bahasa, budaya, dan skill kerja terbaik menuju Jepang.',
            hero_btn1: 'Lihat Program',
            hero_btn2: 'Konsultasi Gratis',
            hero_trust_strong: '1000+ alumni ditempatkan',
            hero_trust_text: 'di berbagai perusahaan Jepang',

            /* ── Hero card ── */
            mc_title: 'Kizuku International Academy',
            mc_sub: 'Program tersedia',
            mc_progs: ['Tokutei Ginou (TG)', 'Engineering', 'Kelas Bahasa Jepang', 'Returnee / Ex Jepang'],
            mc_open: 'Open',
            badge1_num: '98%',
            badge1_label: 'Tingkat Penempatan',
            badge2_num: '10+',
            badge2_label: 'Tahun Pengalaman',
            jp_flag_title: 'Berangkat ke Jepang',
            jp_flag_sub: 'Program resmi SSW/TG',

            /* ── Stats ── */
            stats: [
                { num: '1000<span>+</span>', label: 'Alumni Ditempatkan' },
                { num: '98<span>%</span>', label: 'Tingkat Keberhasilan' },
                { num: '10<span>+</span>', label: 'Tahun Pengalaman' },
                { num: '4', label: 'Program Tersedia' }
            ],

            /* ── Program Section ── */
            prog_tag: '✦ Program Kami',
            prog_h2: 'Pilih Jalur yang Tepat<br>Untuk Kariermu',
            prog_p: 'Dari pemula hingga profesional, kami siapkan jalur pelatihan terstruktur yang mengantarkan kamu ke Jepang.',
            prog_cards: [
                {
                    badge: 'TG / SSW',
                    h3: 'Program Tokutei Ginou (TG)',
                    p: 'Jalur resmi kerja Jepang dengan sistem pelatihan terarah: bahasa, disiplin, budaya kerja, sampai simulasi interview.',
                    feats: ['Bahasa Jepang intensif (N5–N4)', 'Budaya & disiplin kerja Jepang', 'Simulasi interview user Jepang', 'Pendampingan dokumen lengkap'],
                    btn1: 'Daftar TG Batch', btn2: 'Lihat Detail', note: '⚡ Kuota terbatas'
                },
                {
                    badge: 'Engineering',
                    h3: 'Program Engineering',
                    p: 'Program profesional untuk bidang teknik & konstruksi. Fokus pada kesiapan kerja, bahasa teknis, dan etika kerja Jepang.',
                    feats: ['Bahasa teknis & terminologi lapangan', 'Disiplin kerja & mindset profesional', 'Interview preparation', 'Pendampingan penempatan'],
                    btn1: 'Cek Persyaratan', btn2: 'Lihat Detail', note: '✦ Jalur profesional'
                },
                {
                    badge: 'Japanese Class',
                    h3: 'Kelas Bahasa Jepang (N5–N3)',
                    p: 'Kelas reguler dari pemula sampai menengah: grammar terstruktur, kaiwa aktif, dan latihan soal JLPT.',
                    feats: ['Level: N5, N4, N3', 'Grammar sistematis + drilling', 'Kaiwa & listening practice', 'Latihan JLPT style'],
                    btn1: 'Join Kelas', btn2: 'Lihat Jadwal', note: '★ Tersedia kelas pemula'
                },
                {
                    badge: 'Returnee / Ex Japan',
                    h3: 'Program Returnee (Ex Jepang)',
                    p: 'Untuk alumni magang/eks Jepang yang ingin upgrade jalur karier: peningkatan bahasa, re-matching, dan jalur lanjutan.',
                    feats: ['Upgrade level bahasa & kaiwa', 'Persiapan dokumen lanjutan', 'Re-matching user', 'Konsultasi jalur karier'],
                    btn1: 'Konsultasi Program', btn2: 'Lihat Detail', note: 'Untuk yang sudah pernah ke Jepang'
                }
            ],

            /* ── Keunggulan ── */
            keung_tag: '✦ Mengapa Kizuku',
            keung_h2: 'Keunggulan yang Membuat<br>Kami Berbeda',
            keung_p: 'Kami tidak sekadar mengajar bahasa — kami mempersiapkan kamu seutuhnya untuk sukses bekerja di Jepang.',
            keung_cards: [
                { h4: 'Pengajar Berpengalaman', p: 'Tenaga pengajar kami adalah praktisi yang pernah bekerja atau belajar langsung di Jepang, memberikan insight nyata dunia kerja Jepang.' },
                { h4: 'Kurikulum Berbasis Realita', p: 'Materi disesuaikan dengan kebutuhan aktual pasar kerja Jepang — bukan sekadar teori, tapi langsung aplikatif di lapangan.' },
                { h4: 'Jaringan Perusahaan Luas', p: 'Kami telah membangun relasi dengan ratusan perusahaan Jepang yang aktif mencari tenaga kerja Indonesia berkualitas.' },
                { h4: 'Pendampingan Penuh', p: 'Dari pertama daftar hingga kamu sudah bekerja di Jepang, tim kami mendampingi setiap langkah prosesmu tanpa biaya tersembunyi.' },
                { h4: 'Proses Cepat & Transparan', p: 'Proses seleksi, pelatihan, hingga pemberangkatan dilakukan secara transparan dengan timeline yang jelas dan dapat dipantau.' },
                { h4: 'Rekam Jejak Terbukti', p: 'Lebih dari 1000 alumni telah berhasil kami tempatkan di Jepang dengan tingkat keberhasilan mencapai 98%. Hasil nyata, bukan janji.' }
            ],

            /* ── Testimoni ── */
            testi_tag: '✦ Testimoni',
            testi_h2: 'Cerita Sukses<br>Alumni Kizuku',
            testi_p: 'Bukan kami yang bilang — dengarlah langsung dari mereka yang sudah membuktikannya.',
            testi_cards: [
                { text: '"Dulu saya nol bahasa Jepang. Setelah 6 bulan di Kizuku, saya lulus N4 dan langsung diterima di perusahaan manufaktur di Aichi."', role: 'Alumni TG · Bekerja di Aichi, Jepang' },
                { text: '"Program Engineering Kizuku sangat fokus dan relevan. Saya dapat kerja di bidang konstruksi di Osaka hanya 3 bulan setelah pelatihan."', role: 'Alumni Engineering · Osaka, Jepang' },
                { text: '"Saya returnee yang sempat bingung mau lanjut kemana. Kizuku bantu saya upgrade bahasa dari N4 ke N3 dan matching dengan perusahaan baru yang lebih baik."', role: 'Alumni Returnee · Tokyo, Jepang' }
            ],

            /* ── Berita ── */
            berita_tag: '📰 Berita Terkini',
            berita_h2: 'Update &amp; Informasi<br>Terbaru Kizuku',
            berita_p: 'Tetap update dengan berita, info program, dan kisah sukses alumni kami.',
            berita_empty: 'Belum ada berita.',
            berita_kelola: '+ Kelola Berita',

            /* ── Kontak ── */
            kontak_tag: '✦ Hubungi Kami',
            kontak_h2: 'Siap Mulai<br>Perjalananmu?',
            kontak_p: 'Tim kami siap membantu kamu memilih program terbaik, menjawab pertanyaan, dan memandu proses pendaftaran dari awal hingga akhir.',
            kontak_labels: ['Alamat', 'WhatsApp', 'Email', 'Jam Operasional'],
            kontak_wa_btn: '💬 Chat WhatsApp',
            kontak_email_btn: '✉️ Kirim Email',
            form_title: 'Form Pendaftaran',
            form_sub: 'Isi form di bawah, kami akan menghubungi kamu dalam 1×24 jam.',
            form_nama: 'Nama Lengkap',
            form_wa: 'No. WhatsApp',
            form_email: 'Email',
            form_program: 'Program yang Diminati',
            form_select: '-- Pilih Program --',
            form_pesan: 'Pesan / Pertanyaan',
            form_submit: 'Kirim Pendaftaran',
            form_nama_ph: 'Nama kamu',
            form_wa_ph: '08xx-xxxx-xxxx',
            form_email_ph: 'nama@email.com',
            form_pesan_ph: 'Tuliskan pertanyaan atau informasi tambahan kamu di sini...',

            /* ── Footer ── */
            ft_desc: 'Lembaga pelatihan kerja terpercaya yang mempersiapkan generasi Indonesia untuk bersaing dan berkarier di Jepang.',
            ft_col1_title: 'Program',
            ft_col1_items: ['Tokutei Ginou (TG)', 'Engineering', 'Kelas Bahasa Jepang', 'Returnee / Ex Jepang'],
            ft_col2_title: 'Navigasi',
            ft_col2_items: ['Beranda', 'Keunggulan', 'Testimoni', 'Daftar Sekarang'],
            ft_copy: 'All rights reserved.',
            ft_made: 'Dibuat dengan ❤️ untuk masa depan Indonesia di Jepang'
        },

        jp: {
            /* ── Nav ── */
            nav: ['ホーム', 'プログラム', '強み', '体験談', 'お問い合わせ'],
            cta_konsultasi: '相談する',
            cta_daftar: '今すぐ登録',

            /* ── Hero ── */
            hero_pill: '2014年から信頼 \u00a0·\u00a0 🇯🇵 日本',
            hero_h1: 'キャリアを<br><span class="line-accent">日本で実現しよう</span><br>私たちと共に',
            hero_sub: 'LPK Kizuku International Academyは、日本での就職に向けた語学・文化・実務スキルの最高のトレーニングを提供します。',
            hero_btn1: 'プログラムを見る',
            hero_btn2: '無料相談',
            hero_trust_strong: '1000人以上の卒業生を配置',
            hero_trust_text: '日本の様々な企業で',

            /* ── Hero card ── */
            mc_title: 'Kizuku International Academy',
            mc_sub: '利用可能なプログラム',
            mc_progs: ['特定技能 (TG)', 'エンジニアリング', '日本語クラス', 'リターニー / 元日本'],
            mc_open: '募集中',
            badge1_num: '98%',
            badge1_label: '配置率',
            badge2_num: '10+',
            badge2_label: '年の経験',
            jp_flag_title: '日本へ出発',
            jp_flag_sub: '公式SSW/TGプログラム',

            /* ── Stats ── */
            stats: [
                { num: '1000<span>+</span>', label: '卒業生配置済み' },
                { num: '98<span>%</span>', label: '成功率' },
                { num: '10<span>+</span>', label: '年の経験' },
                { num: '4', label: 'プログラム' }
            ],

            /* ── Program Section ── */
            prog_tag: '✦ プログラム',
            prog_h2: 'キャリアに最適な<br>道を選ぼう',
            prog_p: '初心者からプロフェッショナルまで、日本への道を切り開く体系的なトレーニングプログラムを用意しています。',
            prog_cards: [
                {
                    badge: 'TG / SSW',
                    h3: '特定技能プログラム (TG)',
                    p: '日本での正規就労ルート：語学、規律、職場文化、面接シミュレーションまでの体系的なトレーニング。',
                    feats: ['日本語集中講座 (N5–N4)', '日本の文化と職場規律', 'ユーザー面接シミュレーション', '書類サポート完備'],
                    btn1: 'TGバッチに登録', btn2: '詳細を見る', note: '⚡ 定員限定'
                },
                {
                    badge: 'Engineering',
                    h3: 'エンジニアリングプログラム',
                    p: '技術・建設分野のプロフェッショナルプログラム。就労準備、専門用語、日本の労働倫理に焦点。',
                    feats: ['専門用語と現場用語', '職場規律とプロ意識', '面接準備', '配置サポート'],
                    btn1: '要件を確認', btn2: '詳細を見る', note: '✦ プロフェッショナルルート'
                },
                {
                    badge: 'Japanese Class',
                    h3: '日本語クラス (N5–N3)',
                    p: '初級から中級までの通常クラス：体系的な文法、アクティブな会話、JLPTの練習問題。',
                    feats: ['レベル: N5, N4, N3', '体系的な文法 + ドリル', '会話 & リスニング練習', 'JLPTスタイルの演習'],
                    btn1: 'クラスに参加', btn2: 'スケジュールを見る', note: '★ 初心者クラスあり'
                },
                {
                    badge: 'Returnee / Ex Japan',
                    h3: 'リターニープログラム（元日本）',
                    p: '元実習生・元日本在住者向け：語学力向上、再マッチング、キャリアアップルートを提供。',
                    feats: ['語学レベルと会話のアップグレード', '継続書類の準備', 'ユーザー再マッチング', 'キャリア相談'],
                    btn1: 'プログラム相談', btn2: '詳細を見る', note: '日本経験者向け'
                }
            ],

            /* ── Keunggulan ── */
            keung_tag: '✦ なぜKizukuか',
            keung_h2: '私たちが<br>選ばれる理由',
            keung_p: '私たちは単に言語を教えるだけではありません。日本での成功に向けて、あなたを総合的に準備します。',
            keung_cards: [
                { h4: '経験豊富な講師陣', p: '講師陣は日本で直接働いたり学んだりした実務家であり、日本の職場のリアルな知見を提供します。' },
                { h4: '実践的なカリキュラム', p: '日本の労働市場の実際のニーズに合わせた教材 — 理論だけでなく、現場ですぐに活用できます。' },
                { h4: '幅広い企業ネットワーク', p: '質の高いインドネシア人労働者を積極的に求める数百の日本企業と関係を構築しています。' },
                { h4: '完全サポート', p: '登録から日本での就労まで、隠れた費用なく、プロセスの各ステップをチームがサポートします。' },
                { h4: '迅速で透明なプロセス', p: '選考、研修、出発まで、明確なタイムラインで透明に実施され、進捗を確認できます。' },
                { h4: '実績ある成果', p: '1000人以上の卒業生を日本に配置し、成功率は98%。約束ではなく、実際の成果です。' }
            ],

            /* ── Testimoni ── */
            testi_tag: '✦ 体験談',
            testi_h2: 'Kizuku卒業生の<br>成功ストーリー',
            testi_p: '私たちの言葉ではなく、実際に成功した方々の声をお聞きください。',
            testi_cards: [
                { text: '「日本語ゼロからスタート。Kizukuで6ヶ月後、N4に合格し、愛知の製造会社にすぐに採用されました。」', role: 'TG卒業生 · 愛知、日本勤務' },
                { text: '「KizukuのEngineeringプログラムは非常に集中的で実用的。研修後わずか3ヶ月で大阪の建設業界に就職できました。」', role: 'Engineering卒業生 · 大阪、日本' },
                { text: '「帰国組として次にどうすればいいか悩んでいました。KizukuがN4からN3への語学力アップと、より良い新しい企業とのマッチングを助けてくれました。」', role: 'リターニー卒業生 · 東京、日本' }
            ],

            /* ── Berita ── */
            berita_tag: '📰 最新ニュース',
            berita_h2: 'Kizukuの最新<br>アップデート情報',
            berita_p: 'ニュース、プログラム情報、卒業生の成功事例をチェックしてください。',
            berita_empty: 'ニュースはまだありません。',
            berita_kelola: '+ ニュース管理',

            /* ── Kontak ── */
            kontak_tag: '✦ お問い合わせ',
            kontak_h2: '旅を始める<br>準備はできましたか？',
            kontak_p: '最適なプログラムの選択、質問への回答、最初から最後までの登録プロセスのガイドをお手伝いします。',
            kontak_labels: ['住所', 'WhatsApp', 'メール', '営業時間'],
            kontak_wa_btn: '💬 WhatsAppチャット',
            kontak_email_btn: '✉️ メールを送る',
            form_title: '登録フォーム',
            form_sub: '以下のフォームにご記入ください。24時間以内にご連絡いたします。',
            form_nama: '氏名',
            form_wa: 'WhatsApp番号',
            form_email: 'メール',
            form_program: '希望プログラム',
            form_select: '-- プログラムを選択 --',
            form_pesan: 'メッセージ / ご質問',
            form_submit: '登録を送信',
            form_nama_ph: 'お名前',
            form_wa_ph: '08xx-xxxx-xxxx',
            form_email_ph: 'name@email.com',
            form_pesan_ph: 'ご質問や追加情報をこちらにお書きください...',

            /* ── Footer ── */
            ft_desc: 'インドネシアの次世代が日本で競争し、キャリアを築くための信頼できる職業訓練機関です。',
            ft_col1_title: 'プログラム',
            ft_col1_items: ['特定技能 (TG)', 'エンジニアリング', '日本語クラス', 'リターニー / 元日本'],
            ft_col2_title: 'ナビゲーション',
            ft_col2_items: ['ホーム', '強み', '体験談', '今すぐ登録'],
            ft_copy: 'All rights reserved.',
            ft_made: '❤️ を込めて — インドネシアの日本での未来のために'
        }
    };

    var currentLang = 'id';

    /* ──────────────────────────────────────────
     *  TOGGLE BUTTON
     * ────────────────────────────────────────── */
    document.querySelectorAll('.lang-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            // Ripple
            var ripple = document.createElement('span');
            ripple.classList.add('ink-ripple');
            var rect = btn.getBoundingClientRect();
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            btn.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 600);

            currentLang = currentLang === 'id' ? 'jp' : 'id';
            applyLanguage(currentLang);
            updateActiveLabel(currentLang);
        });
    });

    function updateActiveLabel(lang) {
        document.querySelectorAll('.lang-toggle').forEach(function (btn) {
            var jp = btn.querySelector('.lang-label-jp');
            var id = btn.querySelector('.lang-label-id');
            if (lang === 'jp') { jp.classList.add('active'); id.classList.remove('active'); }
            else { id.classList.add('active'); jp.classList.remove('active'); }
        });
    }

    /* ──────────────────────────────────────────
     *  HELPERS
     * ────────────────────────────────────────── */
    function setText(sel, val) {
        var el = document.querySelector(sel);
        if (el) el.textContent = val;
    }
    function setHTML(sel, val) {
        var el = document.querySelector(sel);
        if (el) el.innerHTML = val;
    }
    function setAll(sel, arr) {
        document.querySelectorAll(sel).forEach(function (el, i) {
            if (arr[i] !== undefined) el.textContent = arr[i];
        });
    }

    /* ──────────────────────────────────────────
     *  APPLY LANGUAGE
     * ────────────────────────────────────────── */
    function applyLanguage(lang) {
        var t = T[lang];

        /* ── Nav links (desktop + mobile) ── */
        var navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(function (a, i) { if (t.nav[i]) a.textContent = t.nav[i]; });
        var mobLinks = document.querySelectorAll('.mobile-menu > a');
        mobLinks.forEach(function (a, i) { if (t.nav[i]) a.textContent = t.nav[i]; });

        /* ── CTA buttons ── */
        document.querySelectorAll('.nav-cta .btn-outline, .mob-cta .btn-outline').forEach(function (b) {
            if (b.getAttribute('href') && b.getAttribute('href').indexOf('#kontak') > -1) b.textContent = t.cta_konsultasi;
        });
        document.querySelectorAll('.nav-cta .btn-primary, .mob-cta .btn-primary').forEach(function (b) {
            b.textContent = t.cta_daftar;
        });

        /* ── Hero ── */
        var pill = document.querySelector('.hero-pill');
        if (pill) {
            var dot = pill.querySelector('.hero-pill-dot');
            pill.innerHTML = '';
            if (dot) pill.appendChild(dot);
            pill.appendChild(document.createTextNode(t.hero_pill));
        }
        setHTML('.hero-h1', t.hero_h1);
        setText('.hero-sub', t.hero_sub);

        var heroBtn1 = document.querySelector('.hero-btns .btn-primary');
        if (heroBtn1) {
            var svg = heroBtn1.querySelector('svg');
            heroBtn1.innerHTML = '';
            if (svg) heroBtn1.appendChild(svg);
            heroBtn1.appendChild(document.createTextNode(' ' + t.hero_btn1));
        }
        var heroBtn2 = document.querySelector('.hero-btns .btn-outline');
        if (heroBtn2) heroBtn2.textContent = t.hero_btn2;

        var trustStrong = document.querySelector('.trust-text strong');
        if (trustStrong) trustStrong.textContent = t.hero_trust_strong;
        var trustText = document.querySelector('.trust-text');
        if (trustText && trustStrong) {
            trustText.innerHTML = '';
            var s = document.createElement('strong');
            s.textContent = t.hero_trust_strong;
            trustText.appendChild(s);
            trustText.appendChild(document.createTextNode(' ' + t.hero_trust_text));
        }

        /* ── Hero cards ── */
        setText('.mc-title', t.mc_title);
        setText('.mc-sub', t.mc_sub);
        var progs = document.querySelectorAll('.mc-prog');
        progs.forEach(function (p, i) {
            var dot = p.querySelector('.mc-dot');
            var countEl = p.querySelector('.count');
            if (t.mc_progs[i]) {
                p.innerHTML = '';
                if (dot) p.appendChild(dot);
                p.appendChild(document.createTextNode(t.mc_progs[i]));
                var sp = document.createElement('span');
                sp.className = 'count';
                sp.textContent = t.mc_open;
                p.appendChild(sp);
            }
        });

        /* ── Hero badges ── */
        var b1 = document.querySelector('.hero-badge-1');
        if (b1) {
            var b1num = b1.querySelector('.hb-num');
            var b1lbl = b1.querySelector('.hb-label');
            if (b1lbl) b1lbl.textContent = t.badge1_label;
        }
        var b2 = document.querySelector('.hero-badge-2');
        if (b2) {
            var b2lbl = b2.querySelector('.hb-label');
            if (b2lbl) b2lbl.textContent = t.badge2_label;
        }

        /* ── JP Flag ── */
        var jpFlag = document.querySelector('.jp-flag');
        if (jpFlag) {
            var flagDiv = jpFlag.querySelector('div');
            if (flagDiv) {
                var titleDiv = flagDiv.children[0];
                var subDiv = flagDiv.children[1];
                if (titleDiv) titleDiv.textContent = t.jp_flag_title;
                if (subDiv) subDiv.textContent = t.jp_flag_sub;
            }
        }

        /* ── Stats strip ── */
        var statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(function (item, i) {
            if (t.stats[i]) {
                var numEl = item.querySelector('.stat-num');
                var lblEl = item.querySelector('.stat-lbl');
                if (numEl) numEl.innerHTML = t.stats[i].num;
                if (lblEl) lblEl.textContent = t.stats[i].label;
            }
        });

        /* ── Program section ── */
        var progSection = document.querySelector('#program');
        if (progSection) {
            var tag = progSection.querySelector('.sec-tag');
            var h2 = progSection.querySelector('.sec-h2');
            var p = progSection.querySelector('.sec-p');
            if (tag) tag.textContent = t.prog_tag;
            if (h2) h2.innerHTML = t.prog_h2;
            if (p) p.textContent = t.prog_p;

            var cards = progSection.querySelectorAll('.prog-card');
            cards.forEach(function (card, i) {
                if (!t.prog_cards[i]) return;
                var c = t.prog_cards[i];
                var badge = card.querySelector('.prog-badge');
                if (badge) {
                    var bdot = badge.querySelector('.bdot');
                    badge.innerHTML = '';
                    if (bdot) badge.appendChild(bdot);
                    badge.appendChild(document.createTextNode(c.badge));
                }
                var h3 = card.querySelector('h3');
                if (h3) h3.textContent = c.h3;
                var cp = card.querySelector('p');
                if (cp) cp.textContent = c.p;
                var feats = card.querySelectorAll('.feat-list li');
                feats.forEach(function (li, fi) {
                    if (c.feats[fi]) li.textContent = c.feats[fi];
                });
                var btns = card.querySelectorAll('.prog-footer .btn');
                if (btns[0]) btns[0].textContent = c.btn1;
                if (btns[1]) btns[1].textContent = c.btn2;
                var note = card.querySelector('.prog-note');
                if (note) note.textContent = c.note;
            });
        }

        /* ── Keunggulan ── */
        var keungSection = document.querySelector('#keunggulan');
        if (keungSection) {
            var tag = keungSection.querySelector('.sec-tag');
            var h2 = keungSection.querySelector('.sec-h2');
            var p = keungSection.querySelector('.sec-p');
            if (tag) tag.textContent = t.keung_tag;
            if (h2) h2.innerHTML = t.keung_h2;
            if (p) p.textContent = t.keung_p;

            var cards = keungSection.querySelectorAll('.keung-card');
            cards.forEach(function (card, i) {
                if (!t.keung_cards[i]) return;
                var h4 = card.querySelector('h4');
                var cp = card.querySelector('p');
                if (h4) h4.textContent = t.keung_cards[i].h4;
                if (cp) cp.textContent = t.keung_cards[i].p;
            });
        }

        /* ── Testimoni ── */
        var testiSection = document.querySelector('#testimoni');
        if (testiSection) {
            var tag = testiSection.querySelector('.sec-tag');
            var h2 = testiSection.querySelector('.sec-h2');
            var p = testiSection.querySelector('.sec-p');
            if (tag) tag.textContent = t.testi_tag;
            if (h2) h2.innerHTML = t.testi_h2;
            if (p) p.textContent = t.testi_p;

            var cards = testiSection.querySelectorAll('.testi-card');
            cards.forEach(function (card, i) {
                if (!t.testi_cards[i]) return;
                var text = card.querySelector('.testi-text');
                var role = card.querySelector('.testi-role');
                if (text) text.textContent = t.testi_cards[i].text;
                if (role) role.textContent = t.testi_cards[i].role;
            });
        }

        /* ── Berita section header ── */
        var beritaSection = document.querySelector('#berita');
        if (beritaSection) {
            var tag = beritaSection.querySelector('.sec-tag');
            var h2 = beritaSection.querySelector('.sec-h2');
            var p = beritaSection.querySelector('.sec-p');
            if (tag) tag.textContent = t.berita_tag;
            if (h2) h2.innerHTML = t.berita_h2;
            if (p) p.textContent = t.berita_p;
        }

        /* ── Kontak ── */
        var kontakSection = document.querySelector('#kontak');
        if (kontakSection) {
            var tag = kontakSection.querySelector('.sec-tag');
            var h2 = kontakSection.querySelector('.sec-h2');
            var kp = kontakSection.querySelector('.kontak-left > p');
            if (tag) tag.textContent = t.kontak_tag;
            if (h2) h2.innerHTML = t.kontak_h2;
            if (kp) kp.textContent = t.kontak_p;

            var labels = kontakSection.querySelectorAll('.k-label');
            labels.forEach(function (lbl, i) {
                if (t.kontak_labels[i]) lbl.textContent = t.kontak_labels[i];
            });

            var waBtn = kontakSection.querySelector('.kontak-left .btn-primary');
            var emBtn = kontakSection.querySelector('.kontak-left .btn-outline');
            if (waBtn) waBtn.textContent = t.kontak_wa_btn;
            if (emBtn) emBtn.textContent = t.kontak_email_btn;

            /* form */
            var formCard = kontakSection.querySelector('.form-card');
            if (formCard) {
                var fh3 = formCard.querySelector('h3');
                var fsub = formCard.querySelector('.fc-sub');
                if (fh3) fh3.textContent = t.form_title;
                if (fsub) fsub.textContent = t.form_sub;

                var formLabels = formCard.querySelectorAll('label');
                var labelTexts = [t.form_nama, t.form_wa, t.form_email, t.form_program, t.form_pesan];
                formLabels.forEach(function (lbl, i) {
                    if (labelTexts[i]) lbl.textContent = labelTexts[i];
                });

                var inputs = formCard.querySelectorAll('input');
                var placeholders = [t.form_nama_ph, t.form_wa_ph, t.form_email_ph];
                inputs.forEach(function (inp, i) {
                    if (placeholders[i]) inp.placeholder = placeholders[i];
                });

                var sel = formCard.querySelector('select option:first-child');
                if (sel) sel.textContent = t.form_select;

                var ta = formCard.querySelector('textarea');
                if (ta) ta.placeholder = t.form_pesan_ph;

                var submitBtn = formCard.querySelector('.form-submit');
                if (submitBtn) {
                    var svg = submitBtn.querySelector('svg');
                    submitBtn.innerHTML = '';
                    if (svg) submitBtn.appendChild(svg);
                    submitBtn.appendChild(document.createTextNode(' ' + t.form_submit));
                }
            }
        }

        /* ── Footer ── */
        setText('.ft-desc', t.ft_desc);
        var ftCols = document.querySelectorAll('.ft-col');
        if (ftCols[0]) {
            var h5 = ftCols[0].querySelector('h5');
            if (h5) h5.textContent = t.ft_col1_title;
            var links = ftCols[0].querySelectorAll('a');
            links.forEach(function (a, i) { if (t.ft_col1_items[i]) a.textContent = t.ft_col1_items[i]; });
        }
        if (ftCols[1]) {
            var h5 = ftCols[1].querySelector('h5');
            if (h5) h5.textContent = t.ft_col2_title;
            var links = ftCols[1].querySelectorAll('a');
            links.forEach(function (a, i) { if (t.ft_col2_items[i]) a.textContent = t.ft_col2_items[i]; });
        }

        var fbPs = document.querySelectorAll('.footer-bottom p');
        if (fbPs[0]) fbPs[0].innerHTML = '&copy; ' + new Date().getFullYear() + ' LPK Kizuku International Academy. ' + t.ft_copy;
        if (fbPs[1]) fbPs[1].textContent = t.ft_made;

        /* ── Dynamic Lang (Database Content) ── */
        document.querySelectorAll('.dynamic-lang').forEach(function (el) {
            var text = el.getAttribute('data-' + lang);
            if (text) {
                if (el.tagName.toLowerCase() === 'input' || el.tagName.toLowerCase() === 'textarea') {
                    el.placeholder = text;
                } else {
                    el.innerHTML = text;
                }
            }
        });
    }
});
