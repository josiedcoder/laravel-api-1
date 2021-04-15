<?php

namespace App\Providers;

use App\Models\ExchangeRate;
use App\Observers\ExchangeRateObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('VoyagerGuard', function () {
            return 'admin';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        ExchangeRate::observe(ExchangeRateObserver::class);
    }
}
