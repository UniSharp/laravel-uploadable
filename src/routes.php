<?php

Route::group(['prefix' => 'api', 'namespace' => 'Unisharp\Uploadable'], function () {
    Route::post('files', 'UploadController@store');
    Route::delete('files/{file}', 'UploadController@delete');
});
