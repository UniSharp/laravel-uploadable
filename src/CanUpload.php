<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait CanUpload
{
    public function files()
    {
        return $this->morphMany(File::class, 'uploadable');
    }

    public function upload(Request $request, $file_key)
    {
        $file = $request->file($file_key);

        if (is_null($file)) {
            return null;
        }

        return (new File)->saveFileData($file, $this);
    }

    public function removeFiles($arr_files_id = [])
    {
        $files_query = $this->files();
        if (count($arr_files_id) > 0) {
            $files_query = $files_query->whereIn('id', $arr_files_id);
        }

        Storage::delete($files_query->get()->pluck('name')->toArray());
        $files_query->delete();
    }
}
