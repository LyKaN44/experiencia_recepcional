<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// IMPORTANTE: Asegúrate de que esta línea de abajo esté presente
use Illuminate\Support\Facades\URL;

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
    public function boot(): void {
    if (config('app.env') === 'production') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}