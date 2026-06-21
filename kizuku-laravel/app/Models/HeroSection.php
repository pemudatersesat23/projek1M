<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class HeroSection extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'btn_primary_text',
        'btn_primary_link',
        'btn_secondary_text',
        'btn_secondary_link',
        'badge_text',
        'is_active',
        'sort_order',
    ];

    public $translatable = ['title', 'subtitle', 'btn_primary_text', 'btn_secondary_text', 'badge_text'];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'btn_primary_text' => 'array',
        'btn_secondary_text' => 'array',
        'badge_text' => 'array',
    ];

}
