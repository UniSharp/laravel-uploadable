<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $file = (new File)->upload($request, 'file');

        if (is_null($file)) {
            return ['success' => false];
        }

        return $file;
    }

    public function delete(File $file)
    {
        (new File)->removeFiles([$file->id]);

        return ['success' => true];
    }
}
