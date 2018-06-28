<?php

namespace UniSharp\Uploadable;

use UniSharp\Uploadable\File;
use UniSharp\Uploadable\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    const IMAGE_MIME_TYPES = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    public static function upload($file): File
    {
        $name = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $size = $file->getSize();

        switch (true) {
            case in_array($mime, static::IMAGE_MIME_TYPES):
                $type = 'image';
                $class = Image::class;
                break;

            default:
                $type = 'file';
                $class = File::class;
                break;
        }

        $path = $file->store(str_plural($type));

        foreach (Config::get("uploadable.plugins.{$type}", []) as $plugin) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $fullPath = "{$storagePath}/{$path}";
            (new $plugin)->handle($fullPath);
        }

        return $class::create(compact('name', 'mime', 'size', 'path'));
    }
}
