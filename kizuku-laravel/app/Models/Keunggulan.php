<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Keunggulan extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = [
        'icon',
        'title',
        'description',
        'order',
        'is_active'
    ];

    public $translatable = [
        'title',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'title' => 'array',
        'description' => 'array',
        'title' => 'array',
        'description' => 'array',
    ];
}
