<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LaravelCacheDriver::class, function () {
            return new LaravelCacheDriver(cache()->store());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
