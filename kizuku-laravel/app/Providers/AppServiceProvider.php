<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Keunggulan;

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
        // Share global app settings to ALL views (layouts, sections, components).
        // Uses Setting::get() which is now cached — no repeated DB queries.
        View::share('appSettings', [
            'whatsapp_number' => Setting::get('whatsapp_number', '6281217549529'),
            'admin_email'     => Setting::get('admin_email', 'info@kizuku-academy.com'),
            'office_address'  => Setting::get('office_address', 'Jl. Contoh No. 123, Kota Anda, Indonesia'),
            'office_hours'    => Setting::get('office_hours', 'Senin – Sabtu, 08.00 – 17.00 WIB'),
        ]);

        // Share $keunggulans to all views so sections/keunggulan.blade.php
        // does NOT need to query the database itself.
        View::composer('sections.keunggulan', function ($view) {
            if (!isset($view->getData()['keunggulans'])) {
                $view->with('keunggulans', Keunggulan::where('is_active', true)->orderBy('order', 'asc')->get());
            }
        });
    }
}
