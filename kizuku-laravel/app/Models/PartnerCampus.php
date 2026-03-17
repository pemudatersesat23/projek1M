<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;

class PartnerCampus extends Model
{
    // use HasTranslations;
    
    protected $fillable = ['name', 'logo'];
    
    // public $translatable = ['name'];
}
