<?php

namespace UniSharp\Uploadable\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use UniSharp\Uploadable\Uploader;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        return Uploader::upload(array_first($request->file()));
    }

    public function destroy(File $file)
    {
        $file->remove();

        return ['success' => true];
    }
}
