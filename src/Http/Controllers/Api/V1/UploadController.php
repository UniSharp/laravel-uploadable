<?php

namespace UniSharp\Uploadable\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use UniSharp\Uploadable\Contracts\FileContract;
use UniSharp\Uploadable\Uploader;

class UploadController extends Controller
{
    public function store(Request $request, Uploader $uploader)
    {
        $file = $uploader->saveDataWithFile(array_first($request->file()));

        if (is_null($file)) {
            return ['success' => false];
        }

        return $file;
    }

    public function delete(FileContract $file, Uploader $uploader)
    {
        $uploader->dropDataWithFile($file->id);

        return ['success' => true];
    }
}
