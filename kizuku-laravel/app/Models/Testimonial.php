<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'role',
        'content',
        'avatar_path',
        'stars',
        'is_active',
    ];

    public $translatable = ['role', 'content'];
}
