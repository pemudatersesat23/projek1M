<?php

/**
 * Program Alias Route Mapping
 *
 * Mapping antara URL alias pendek dan slug program di database.
 * Jika slug program berubah di database, update nilai di sini agar route alias tidak 404.
 *
 * Format:
 *   'nama_route' => [
 *       'url'  => '/url-alias-pendek',
 *       'slug' => 'slug-program-di-database',
 *       'name' => 'pages.nama_route',
 *   ],
 */

return [

    'tokutei' => [
        'url'  => '/tokutei-ginou',
        'slug' => 'tokutei-ginou-tg',
        'name' => 'pages.tokutei',
    ],

    'engineer' => [
        'url'  => '/engineer-jepang',
        'slug' => 'engineer-jepang-gijinkoku',
        'name' => 'pages.engineer',
    ],

    'magang' => [
        'url'  => '/ex-internship',
        'slug' => 'engineer-jepang-ex-internship',
        'name' => 'pages.magang',
    ],

    'kursus' => [
        'url'  => '/kursus-bahasa-jepang',
        'slug' => 'kursus-bahasa-jepang',
        'name' => 'pages.kursus',
    ],

];
