<?php

namespace Unisharp\Uploadable;

use Illuminate\Support\Facades\Storage;
use Unisharp\Uploadable\File;

class Uploader
{
    public function saveDataWithFile($file, $model = null)
    {
        $file_data = [
            'path' => $file->store('files', 'local'),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
        ];

        if (!is_null($model)) {
            $file_data['uploadable_id'] = $model->id;
            $file_data['uploadable_type'] = get_class($model);
        }

        return File::create($file_data);
    }

    public function dropDataWithFile($file_id)
    {
        $file_model = File::find($file_id);

        Storage::delete($file_model->path);

        $file_model->delete();
    }
}
