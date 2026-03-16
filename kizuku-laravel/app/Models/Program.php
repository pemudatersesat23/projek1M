<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'name', 'slug', 'explanation', 'target_participants',
        'duration', 'benefits', 'selection_flow', 'cost',
        'faq', 'materi', 'brosur_path', 'thumbnail_path', 'status'
    ];

    protected $casts = [
        'faq' => 'array',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
