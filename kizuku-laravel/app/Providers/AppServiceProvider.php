<?php

namespace App\Providers;

use App\Models\Keunggulan;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $defaultSettings = [
            'whatsapp_number' => '6282261888851',
            'facebook_url'    => 'https://www.facebook.com/share/1BHHcLbuLP/?mibextid=wwXIfr',
            'office_address'  => 'Jl. Bontotangnga No.47, Paccinongang, Kec. Somba Opu, Kabupaten Gowa, Sulawesi Selatan 90233',
            'office_hours'    => 'Senin – Sabtu, 08.00 – 17.00 WIB',
            'instagram_link'  => 'https://instagram.com/kizuku_academy',
            'tiktok_link'     => 'https://tiktok.com/@kizuku_academy',
            'youtube_link'    => 'https://youtube.com/@kizuku_academy',
        ];

        View::share('appSettings', Schema::hasTable('settings') ? [
            'whatsapp_number' => Setting::get('whatsapp_number', $defaultSettings['whatsapp_number']),
            'facebook_url'    => Setting::get('facebook_url', $defaultSettings['facebook_url']),
            'office_address'  => Setting::get('office_address', $defaultSettings['office_address']),
            'office_hours'    => Setting::get('office_hours', $defaultSettings['office_hours']),
            'instagram_link'  => Setting::get('instagram_link', $defaultSettings['instagram_link']),
            'tiktok_link'     => Setting::get('tiktok_link', $defaultSettings['tiktok_link']),
            'youtube_link'    => Setting::get('youtube_link', $defaultSettings['youtube_link']),
        ] : $defaultSettings);

        // View Composer: inject $keunggulans ke section keunggulan
        View::composer('sections.keunggulan', function ($view) {
            if (!isset($view->getData()['keunggulans'])) {
                $view->with('keunggulans', Schema::hasTable('keunggulans')
                    ? Keunggulan::where('is_active', true)->orderBy('order', 'asc')->get()
                    : collect());
            }
        });

        // View Composer: inject $publicPrograms ke layout utama
        // Digunakan oleh WA Modal agar daftar program tidak hardcoded.
        View::composer('layouts.app', function ($view) {
            if (!isset($view->getData()['publicPrograms'])) {
                $view->with('publicPrograms', Schema::hasTable('programs')
                    ? Program::active()->ordered()->get(['id', 'nama_program', 'slug'])
                    : collect());
            }
        });
    }
}

