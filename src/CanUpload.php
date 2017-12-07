<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;

trait CanUpload
{
    public function files()
    {
        return $this->morphMany(File::class, 'uploadable');
    }

    public function upload(Request $request, $file_key = null, $uploader = null)
    {
        $file = is_null($file_key) ? array_first($request->file()) : $request->file($file_key);

        if (is_null($file)) {
            return null;
        }

        return ($uploader ?: new Uploader)->saveDataWithFile($file, $this);
    }

    public function removeFiles($arr_files_id = [], $uploader = null)
    {
        $files_query = $this->files();
        if (count($arr_files_id) > 0) {
            $files_query = $files_query->whereIn('id', $arr_files_id);
        }

        foreach ($files_query->get() as $file_model) {
            ($uploader ?: new Uploader)->dropDataWithFile($file_model->id);
        }
    }
}
