<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Program Slug → Japanese Name Mapping
    |--------------------------------------------------------------------------
    | Digunakan untuk menampilkan nama program dalam bahasa Jepang
    | berdasarkan slug, tanpa hardcode di dalam blade.
    */
    'slug_jp_names' => [
        'tokutei-ginou-tg'                    => '特定技能 (TG)',
        'kenshusei-jishussei-magang-jepang'   => '技能実習生 (研修生)',
        'engineer-jepang-gijinkoku'           => 'エンジニア',
        'engineer-jepang-ex-internship'       => 'エンジニア (経験者)',
        'kursus-bahasa-jepang-offline'        => '日本語クラス (オフライン)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tokutei Ginou — 10 Bidang Pekerjaan (SSW Fields)
    |--------------------------------------------------------------------------
    | Digunakan di program-detail hero display DAN form checkbox.
    | key  => icon Material Symbol
    */
    'tg_fields' => [
        'makanan'    => ['icon' => 'restaurant',             'msg' => 'messages.form.ssw_fields.makanan',    'display' => 'Pengolahan Makanan'],
        'pertanian'  => ['icon' => 'agriculture',            'msg' => 'messages.form.ssw_fields.pertanian',  'display' => 'Pertanian'],
        'kaigo'      => ['icon' => 'elderly',                'msg' => 'messages.form.ssw_fields.kaigo',      'display' => 'Perawatan Lansia (Kaigo)'],
        'restoran'   => ['icon' => 'local_dining',           'msg' => 'messages.form.ssw_fields.restoran',   'display' => 'Restoran / Layanan Makanan'],
        'hotel'      => ['icon' => 'bed',                    'msg' => 'messages.form.ssw_fields.hotel',      'display' => 'Perhotelan'],
        'konstruksi' => ['icon' => 'construction',           'msg' => 'messages.form.ssw_fields.konstruksi', 'display' => 'Konstruksi'],
        'manufaktur' => ['icon' => 'precision_manufacturing','msg' => 'messages.form.ssw_fields.manufaktur', 'display' => 'Manufaktur Mesin & Peralatan'],
        'otomotif'   => ['icon' => 'directions_car',         'msg' => 'messages.form.ssw_fields.otomotif',   'display' => 'Otomotif'],
        'peternakan' => ['icon' => 'pets',                   'msg' => 'messages.form.ssw_fields.peternakan', 'display' => 'Peternakan'],
        'cleaning'   => ['icon' => 'cleaning_services',      'msg' => 'messages.form.ssw_fields.gedung',     'display' => 'Pembersihan Gedung'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dokumen yang diperlukan per slug program
    |--------------------------------------------------------------------------
    | value = translation key untuk label dokumen
    */
    'docs_per_slug' => [
        'tokutei-ginou-tg' => [
            'ktp'          => 'messages.form.docs.ktp',
            'kk'           => 'messages.form.docs.kk',
            'foto'         => 'messages.form.docs.foto',
            'ijazah'       => 'messages.form.docs.ijazah',
            'sertifikat'   => 'messages.form.docs.sertifikat',
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
        'engineer-jepang-gijinkoku' => [
            'cv'           => 'messages.form.docs.cv',
            'ktp'          => 'messages.form.docs.ktp',
            'kk'           => 'messages.form.docs.kk',
            'foto'         => 'messages.form.docs.foto',
            'ijazah'       => 'messages.form.docs.ijazah',
            'transkrip'    => 'messages.form.docs.transkrip',
            'sertifikat'   => 'messages.form.docs.sertifikat',
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
        'kenshusei-jishussei-magang-jepang' => [
            'ktp'          => 'messages.form.docs.ktp',
            'kk'           => 'messages.form.docs.kk',
            'ijazah'       => 'messages.form.docs.ijazah',
            'foto'         => 'messages.form.docs.foto',
            'sertifikat'   => 'messages.form.docs.sertifikat_bahasa',
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
        'kursus-bahasa-jepang-offline' => [
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
        'engineer-jepang-ex-internship' => [
            'cv'           => 'messages.form.docs.cv',
            'ktp'          => 'messages.form.docs.ktp',
            'ijazah'       => 'messages.form.docs.ijazah',
            'transkrip'    => 'messages.form.docs.transkrip',
            'sertifikat'   => 'messages.form.docs.sertifikat',
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
        // default (fallback)
        '_default' => [
            'cv'           => 'messages.form.docs.cv',
            'ijazah'       => 'messages.form.docs.ijazah',
            'transkrip'    => 'messages.form.docs.transkrip',
            'sertifikat'   => 'messages.form.docs.sertifikat',
            'bukti_sosmed' => 'messages.form.docs.bukti_follow',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch Status → Warna & Label
    |--------------------------------------------------------------------------
    */
    'batch_status_colors' => [
        'dibuka'      => ['bg' => '#ecfdf5', 'text' => '#059669'],
        'akan_dibuka' => ['bg' => '#fef3c7', 'text' => '#d97706'],
        'selesai'     => ['bg' => '#f1f5f9', 'text' => '#64748b'],
        'ditutup'     => ['bg' => '#f1f5f9', 'text' => '#64748b'],
        'berjalan'    => ['bg' => '#eff6ff', 'text' => '#3b82f6'],
        '_default'    => ['bg' => '#f1f5f9', 'text' => '#64748b'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slug → TG Fields Section Metadata
    |--------------------------------------------------------------------------
    | Menentukan program mana yang menampilkan section "Bidang Pekerjaan TG"
    | dan teks deskripsinya. Key = program slug.
    */
    'tg_fields_by_slug' => [
        'tokutei-ginou-tg' => [
            'section_title' => '10 Bidang Pekerjaan Tokutei Ginou',
            'section_desc'  => 'Program Tokutei Ginou (SSW) membuka peluang kerja yang terjamin dan transparan di berbagai sektor industri esensial di Jepang. Berikut adalah 10 bidang keahlian utama yang dapat Anda pilih:',
        ],
    ],

];
