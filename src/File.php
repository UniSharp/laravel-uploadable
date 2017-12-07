<?php

namespace Unisharp\Uploadable;

use Illuminate\Database\Eloquent\Model;
use Unisharp\Uploadable\CanUpload;

class File extends Model
{
    use CanUpload;
    protected $guarded = [];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function saveFileData($file, $model = null)
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

        return $this->create($file_data);
    }
}
