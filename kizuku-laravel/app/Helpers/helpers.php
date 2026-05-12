<?php

/**
 * app/Helpers/helpers.php
 * ─────────────────────────────────────────────────────────────────────────
 * Global helper functions untuk seluruh aplikasi Kizuku.
 * Autoloaded via composer.json → autoload.files
 * ─────────────────────────────────────────────────────────────────────────
 */

if (! function_exists('splitStat')) {
    /**
     * Memisahkan nilai statistik menjadi angka dan suffix untuk tampilan.
     * Contoh: "1000+" → '<div class="stat-num">1000<span>+</span></div>'
     *         "98%"   → '<div class="stat-num">98<span>%</span></div>'
     *
     * @param  string $val  Nilai mentah dari setting (contoh: "1000+", "98%")
     * @return string       HTML siap render (gunakan dengan {!! !!})
     */
    function splitStat(string $val): string
    {
        preg_match('/^(\d+)(.*)$/', $val, $matches);

        if (count($matches) === 3) {
            return '<div class="stat-num">' . $matches[1] . '<span>' . $matches[2] . '</span></div>';
        }

        return '<div class="stat-num">' . e($val) . '</div>';
    }
}
