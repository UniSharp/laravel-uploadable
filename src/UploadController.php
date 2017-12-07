<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Unisharp\Uploadable\Uploader;

class UploadController extends Controller
{
    public function store(Request $request, $uploader = null)
    {
        $file = ($uploader ?: new Uploader)->upload($request);

        if (is_null($file)) {
            return ['success' => false];
        }

        return $file;
    }

    public function delete(File $file, $uploader = null)
    {
        ($uploader ?: new Uploader)->removeFiles([$file->id]);

        return ['success' => true];
    }
}
