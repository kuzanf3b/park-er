<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('components.pagination');

        $appLogoPath = file_exists(public_path('images/logo.png'))
            ? asset('images/logo.png')
            : asset('favicon.ico');

        View::share('appLogo', $appLogoPath);
    }
}
