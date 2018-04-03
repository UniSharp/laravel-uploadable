<?php

namespace Unisharp\Uploadable;

use Unisharp\Uploadable\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    private $model;
    public function __construct($model = null)
    {
        $this->model = $model ?: new File;
    }

    public function saveDataWithFile(?UploadedFile $file = null, $morph_model = null)
    {
        $file_data = [
            'path' => $file->store('files', 'local'),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
        ];

        $classes = Config::get('uploadable.plugins', []);

        foreach ($classes as $class) {
            App::make($class)->handle(Storage::disk('local'), $file_data['path']);
        }

        if (!is_null($morph_model)) {
            $file_data['uploadable_id'] = $morph_model->id;
            $file_data['uploadable_type'] = get_class($morph_model);
        }

        return $this->model->create($file_data);
    }

    public function dropDataWithFile($file_id)
    {
        $file_model = $this->model->find($file_id);

        Storage::delete($file_model->path);

        $this->model->destroy($file_id);
    }
}
