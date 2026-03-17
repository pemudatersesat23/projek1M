<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $fillable = [
        'judul', 'kategori', 'emoji', 'isi', 'status_publish',
    ];

    public function scopePublished($query)
    {
        return $query->where('status_publish', 'published');
    }
}
