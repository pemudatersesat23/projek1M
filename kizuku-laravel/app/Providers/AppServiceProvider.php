<?php

namespace App\Providers;

use App\Models\Keunggulan;
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
            'whatsapp_number' => '6281217549529',
            'admin_email' => 'info@kizuku-academy.com',
            'office_address' => 'Jl. Bontotangnga, Paccinongang, Kec. Somba Opu, Kabupaten Gowa, Sulawesi Selatan 90233',
            'office_hours' => 'Senin - Sabtu, 08.00 - 17.00 WIB',
        ];

        View::share('appSettings', Schema::hasTable('settings') ? [
            'whatsapp_number' => Setting::get('whatsapp_number', $defaultSettings['whatsapp_number']),
            'admin_email' => Setting::get('admin_email', $defaultSettings['admin_email']),
            'office_address' => Setting::get('office_address', $defaultSettings['office_address']),
            'office_hours' => Setting::get('office_hours', $defaultSettings['office_hours']),
        ] : $defaultSettings);

        View::composer('sections.keunggulan', function ($view) {
            if (!isset($view->getData()['keunggulans'])) {
                $view->with('keunggulans', Schema::hasTable('keunggulans')
                    ? Keunggulan::where('is_active', true)->orderBy('order', 'asc')->get()
                    : collect());
            }
        });
    }
}
