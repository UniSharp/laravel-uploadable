<?php
namespace UniSharp\Uploadable;

use Illuminate\Support\Facades\Route;

class UploaderManager
{
    public static function route($enable = ['store', 'delete'], callable $callback = null): void
    {
        Route::prefix('files')->group(function () use ($enable, $callback) {
            $namespace = '\\UniSharp\\Uploadable\\Http\\Controllers\\Api\\V1\\';

            if (in_array('store', $enable)) {
                Route::post('/', $namespace . 'UploadController@store');
            }

            if (in_array('delete', $enable)) {
                Route::delete('{file}', $namespace . 'UploadController@destroy');
            }

            if ($callback) {
                $callback();
            }
        });
    }
}
