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
}
