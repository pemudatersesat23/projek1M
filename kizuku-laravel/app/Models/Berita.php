<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Berita extends Model
{
    use HasTranslations;

    protected $fillable = [
        'judul', 'kategori', 'emoji', 'isi', 'status_publish',
    ];

    public $translatable = ['judul', 'isi'];

    public function scopePublished($query)
    {
        return $query->where('status_publish', 'published');
    }
}
