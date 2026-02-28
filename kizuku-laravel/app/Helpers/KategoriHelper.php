<?php

namespace App\Helpers;

class KategoriHelper
{
    public static function label(string $kategori): string
    {
        return match ($kategori) {
            'kat-info'   => 'Info Program',
            'kat-alumni' => 'Alumni',
            'kat-promo'  => 'Promo',
            'kat-tips'   => 'Tips',
            default      => $kategori,
        };
    }
}
