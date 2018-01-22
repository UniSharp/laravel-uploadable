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
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->app->bind(Uploader::class, function () {
            return new Uploader(new File);
        });
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
