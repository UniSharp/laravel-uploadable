<?php

namespace UniSharp\Uploadable\Plugins;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

class ImagePlugin
{
    public function handle($path)
    {
        $image = Image::make($path);

        if (Config::get('uploadable.use_image_orientate', false)) {
            $image->orientate();
        }

        $image->save($path, 100);

        $directory = $image->dirname;

        foreach (Config::get('uploadable.thumbs', []) as $name => $size) {
            $image = clone $image;

            [$width, $height] = explode('x', $size);

            $filename = $this->getThumbsDirectory($directory, $name) .
                $image->filename .
                '.' .
                $image->extension;

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($filename, 100);
        }
    }

    public function getThumbsDirectory($baseDir, $name)
    {
        $directory = $baseDir . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;

        if (!File::exists($directory)) {
            File::makeDirectory($directory);
        }

        return $directory;
    }
}
