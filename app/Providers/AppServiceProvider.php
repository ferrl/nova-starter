<?php

namespace App\Providers;

use App\Domain\Support\TypeCaster;
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
        if (! $this->app->environment('testing')) {
            $this->app->singleton('TypeCaster', function () { return new TypeCaster; });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {}
}
