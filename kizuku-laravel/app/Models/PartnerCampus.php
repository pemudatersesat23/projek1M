<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\AutoTranslate;

class PartnerCampus extends Model
{
    use HasTranslations, AutoTranslate;

    protected $fillable = ['banner', 'name', 'logo', 'description'];
    
    public $translatable = ['name', 'description'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];


    /**
     * Get the first two sentences of the description.
     */
    public function getShortDescription(string $locale): string
    {
        $desc = $this->getTranslation('description', $locale, false) 
            ?: ($this->getTranslation('description', 'id', false) ?: $this->description);
        $desc = trim(strip_tags($desc ?: ''));

        if ($locale === 'jp' || $locale === 'ja') {
            // Limit by character count for Japanese (around 55 characters)
            if (mb_strlen($desc, 'UTF-8') <= 55) {
                return $desc;
            }
            return mb_substr($desc, 0, 55, 'UTF-8') . '...';
        } else {
            // Limit by word count for Indonesian/English (around 18 words)
            return \Illuminate\Support\Str::words($desc, 18, '...');
        }
    }
}

