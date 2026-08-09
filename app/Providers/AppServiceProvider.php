<?php

namespace App\Providers;

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
        \App\Models\Paket::observe(\App\Observers\PaketObserver::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\BeritaAcara::class, \App\Policies\BeritaAcaraPolicy::class);
    }
}
