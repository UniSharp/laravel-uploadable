<?php

namespace Unisharp\Uploadable;

use Illuminate\Support\Facades\Config;
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
        $this->mergeConfigFrom(
            __DIR__ . '/config/uploadable.php',
            'uploadable'
        );

        if (Config::get('uploadable.use_default_route')) {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }
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
