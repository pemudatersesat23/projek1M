<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class AlurPendaftaran extends Model
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
        'title' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];
}
