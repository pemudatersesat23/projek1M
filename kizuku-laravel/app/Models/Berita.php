<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class Berita extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = [
        'judul', 'kategori', 'image', 'lokasi', 'isi', 'status_publish',
    ];

    public $translatable = ['judul', 'isi'];

    public function scopePublished($query)
    {
        return $query->where('status_publish', 'published');
    }
}
