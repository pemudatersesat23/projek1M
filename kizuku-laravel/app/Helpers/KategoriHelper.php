<?php

namespace App\Helpers;

class KategoriHelper
{
    public static function label(string $kategori): string
    {
        return __("messages.home.categories.{$kategori}") ?: $kategori;
    }
}
