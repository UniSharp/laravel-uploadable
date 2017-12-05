<?php

namespace Unisharp\Uploadable;

use Illuminate\Support\ServiceProvider;

class UploadableServiceProvider extends ServiceProvider
{
    /**
     * Boot the services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
