<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'type'];

    /**
     * Get a setting value by key with permanent caching.
     * Cache is tagged so it can be invalidated all at once on save.
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Clear all cached settings (call after saving).
     */
    public static function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget('setting_' . $key);
        }
    }

    /**
     * Boot — auto-clear cache on save/delete.
     */
    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            self::clearCache($setting->key);
        });

        static::deleted(function (Setting $setting) {
            self::clearCache($setting->key);
        });
    }
}
