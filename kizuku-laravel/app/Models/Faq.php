<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Faq extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = [
        'kategori',
        'question',
        'answer',
        'order',
        'is_active'
    ];

    public $translatable = [
        'kategori',
        'question',
        'answer'
    ];

    protected $casts = [
        'kategori' => 'array',
        'question' => 'array',
        'answer' => 'array',
        'is_active' => 'boolean',
        'kategori' => 'array',
        'question' => 'array',
        'answer' => 'array',
    ];
}
