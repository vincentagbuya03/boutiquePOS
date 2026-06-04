<?php

namespace App\Providers;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\Facades\URL;
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
        // When deployed behind a reverse proxy (e.g. Render), Laravel must trust
        // forwarded headers to correctly detect HTTPS and generate secure URLs.
        if (app()->environment('production')) {
            TrustProxies::at(env('TRUSTED_PROXIES', '*'));
            URL::forceScheme('https');
        }
    }
}
