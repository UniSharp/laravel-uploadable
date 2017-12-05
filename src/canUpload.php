<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;

trait canUpload
{
    public function files()
    {
        return $this->morphMany(File::class, 'uploadable');
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        if (is_null($file)) {
            return null;
        }

        return $this->files()->create([
            'path' => $file->store('files', 'local'),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
        ]);
    }
}
