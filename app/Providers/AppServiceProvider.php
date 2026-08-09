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
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\BeritaAcara::class, \App\Policies\BeritaAcaraPolicy::class);

        // Record LOGIN events to activity log
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function (\Illuminate\Auth\Events\Login $event) {
                activity()
                    ->causedBy($event->user)
                    ->log('LOGIN');
            }
        );
    }
}
