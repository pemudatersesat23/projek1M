<?php

namespace App\Traits;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

trait AutoTranslate
{
    /**
     * Flag global untuk menonaktifkan auto-translate.
     * Gunakan AutoTranslate::disable() sebelum seeding massal,
     * lalu AutoTranslate::enable() setelahnya.
     */
    protected static bool $autoTranslateEnabled = true;

    /**
     * Nonaktifkan auto-translate secara global (berguna saat seeding massal).
     */
    public static function disableAutoTranslate(): void
    {
        static::$autoTranslateEnabled = false;
    }

    /**
     * Aktifkan kembali auto-translate.
     */
    public static function enableAutoTranslate(): void
    {
        static::$autoTranslateEnabled = true;
    }

    /**
     * Cek apakah auto-translate sedang aktif.
     */
    public static function isAutoTranslateEnabled(): bool
    {
        return static::$autoTranslateEnabled;
    }

    /**
     * Boot the trait and register the saving event listener.
     */
    protected static function bootAutoTranslate(): void
    {
        static::saving(function ($model) {
            // Skip jika: flag dinonaktifkan, atau running di console (seeding)
            // tapi masih bisa dioverride dengan enableAutoTranslate() jika perlu.
            if (! static::$autoTranslateEnabled) {
                return;
            }

            $model->autoTranslateFields();
        });
    }

    /**
     * Automatically translate translatable fields if one of the locales is missing.
     */
    public function autoTranslateFields(): void
    {
        if (! isset($this->translatable)) {
            return;
        }

        foreach ($this->translatable as $field) {
            $translations = $this->getTranslations($field);

            // Jika ada Indonesian tapi tidak ada Japanese
            if (! empty($translations['id']) && empty($translations['jp'])) {
                try {
                    $translated = $this->translateValue($translations['id'], 'ja');
                    $this->setTranslation($field, 'jp', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (id -> jp) for field [{$field}]: " . $e->getMessage());
                }
            }

            // Jika ada Japanese tapi tidak ada Indonesian
            if (! empty($translations['jp']) && empty($translations['id'])) {
                try {
                    $translated = $this->translateValue($translations['jp'], 'id');
                    $this->setTranslation($field, 'id', $translated);
                } catch (\Exception $e) {
                    Log::error("AutoTranslate Error (jp -> id) for field [{$field}]: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Helper: translate value secara rekursif jika berupa array.
     */
    protected function translateValue(mixed $value, string $targetLocale): mixed
    {
        if (is_array($value)) {
            $result = [];
            foreach ($value as $key => $val) {
                $result[$key] = $this->translateValue($val, $targetLocale);
            }
            return $result;
        }

        if (is_string($value) && ! empty($value)) {
            $tr = new GoogleTranslate($targetLocale);
            return $tr->translate($value);
        }

        return $value;
    }
}

