<?php

namespace Unisharp\Uploadable;

trait CanUpload
{
    public function files()
    {
        return $this->morphMany(File::class, 'uploadable');
    }

    public function upload($file, $uploader = null)
    {
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
