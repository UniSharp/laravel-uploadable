<?php

namespace UniSharp\Uploadable\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use UniSharp\Uploadable\Contracts\FileContract;
use UniSharp\Uploadable\File;

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
            __DIR__ . '/../config/uploadable.php',
            'uploadable'
        );

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->app->bind(Uploader::class, function () {
            return new Uploader(new File);
        });

        $this->app->bind(FileContract::class, function () {
            return new File();
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
