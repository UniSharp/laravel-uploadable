<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Unisharp\Uploadable\Uploader;

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

    public function delete($file_id, $uploader = null)
    {
        ($uploader ?: new Uploader)->dropDataWithFile($file_id);

        return ['success' => true];
    }
}
