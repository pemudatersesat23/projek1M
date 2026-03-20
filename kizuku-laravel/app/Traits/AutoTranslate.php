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

        $locales = ['id', 'jp'];

        foreach ($this->translatable as $field) {
            $translations = $this->getTranslations($field);
            
            // If we have Indonesian but no Japanese
            if (!empty($translations['id']) && empty($translations['jp'])) {
                try {
                    $translated = $this->translateValue($translations['id'], 'ja');
                    $this->setTranslation($field, 'jp', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (id -> jp) for field {$field}: " . $e->getMessage());
                }
            }
            
            // If we have Japanese but no Indonesian
            if (!empty($translations['jp']) && empty($translations['id'])) {
                try {
                    $translated = $this->translateValue($translations['jp'], 'id');
                    $this->setTranslation($field, 'id', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (jp -> id) for field {$field}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Helper to translate a value recursively if it's an array.
     */
    protected function translateValue($value, $targetLocale)
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $val) {
                $result[$key] = $this->translateValue($val, $targetLocale);
            }
            return $result;
        }

        if (is_string($value) && !empty($value)) {
            $tr = new GoogleTranslate($targetLocale);
            return $tr->translate($value);
        }

        return $value;
    }
}
