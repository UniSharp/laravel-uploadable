<?php

namespace Unisharp\Uploadable;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Unisharp\Uploadable\File;

class Uploader
{
    private $model;
    public function __construct($model = null)
    {
        $this->model = $model ?: new File;
    }

    public function saveDataWithFile($file, $morph_model = null)
    {
        $file_data = [
            'path' => $file->store('files', 'local'),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
        ];

        $this->orientateImage($file_data);

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

    public function orientateImage($file_data)
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . $file_data['path']);

        if (!exif_imagetype($path)) {
            return;
        }

        Image::make($path)->orientate()->save();
    }
}
