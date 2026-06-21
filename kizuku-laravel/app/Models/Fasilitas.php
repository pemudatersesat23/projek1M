<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Fasilitas extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = ['nama', 'image', 'urutan'];

    public $translatable = ['nama'];

    protected $casts = [
        'nama' => 'array',
        'urutan' => 'integer',
    ];

    protected $table = 'fasilitas';
}
