<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Testimonial extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = [
        'name',
        'role',
        'content',
        'avatar_path',
        'stars',
        'is_active',
    ];

    public $translatable = ['name', 'role', 'content'];
}
