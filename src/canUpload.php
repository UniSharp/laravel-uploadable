<?php

namespace Unisharp\Uploadable;

use Illuminate\Http\Request;

trait canUpload
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

        return $this->saveToDb($file);
    }

    public function saveToDb($file)
    {
        return $this->files()->create([
            'path' => $file->store('files', 'local'),
            'name' => $file->getClientOriginalName(),
            'type' => $file->getMimeType(),
        ]);
    }
}
