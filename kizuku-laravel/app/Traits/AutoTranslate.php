<?php

namespace App\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

trait AutoTranslate
{
    /**
     * Boot the trait and register the saving event listener.
     */
    protected static function bootAutoTranslate()
    {
        static::saving(function ($model) {
            $model->autoTranslateFields();
        });
    }

    /**
     * Automatically translate translatable fields if one of the locales is missing.
     */
    public function autoTranslateFields()
    {
        if (!isset($this->translatable)) return;

        $locales = ['id', 'jp']; // We use 'id' for Indonesian and 'jp' for Japanese as per lang directory

        foreach ($this->translatable as $field) {
            $translations = $this->getTranslations($field);
            
            // If we have Indonesian but no Japanese
            if (!empty($translations['id']) && empty($translations['jp'])) {
                try {
                    $tr = new GoogleTranslate('ja'); // 'ja' is the language code for Japanese in Google Translate
                    $translated = $tr->translate($translations['id']);
                    $this->setTranslation($field, 'jp', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (id -> jp) for field {$field}: " . $e->getMessage());
                }
            }
            
            // If we have Japanese but no Indonesian
            if (!empty($translations['jp']) && empty($translations['id'])) {
                try {
                    $tr = new GoogleTranslate('id');
                    $translated = $tr->translate($translations['jp']);
                    $this->setTranslation($field, 'id', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (jp -> id) for field {$field}: " . $e->getMessage());
                }
            }
        }
    }
}
