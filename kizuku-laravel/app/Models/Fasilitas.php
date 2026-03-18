<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $fillable = ['nama', 'image', 'urutan'];

    protected $table = 'fasilitas';

    protected $casts = ['urutan' => 'integer'];
}
