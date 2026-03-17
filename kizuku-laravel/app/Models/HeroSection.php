<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HeroSection extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'btn_primary_text',
        'btn_primary_link',
        'btn_secondary_text',
        'btn_secondary_link',
        'is_active',
    ];

    public $translatable = ['title', 'subtitle', 'btn_primary_text', 'btn_secondary_text'];
}
