<?php
namespace UniSharp\Uploadable\Plugins;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\FilesystemAdapter;

class ImageHandler
{
    protected $supportedMime = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    public function handle(FilesystemAdapter $storage, $path)
    {
        $mimeType = File::mimeType($path = $storage->path($path));

        if (in_array($mimeType, $this->supportedMime)) {
            $image = Image::make($path);
            if (Config::get('uploadable.use_image_orientate', false)) {
                $image->orientate();
            }

            $image->save($path, 100);

            $directory = $image->dirname;
            foreach (Config::get('uploadable.thumbs', []) as $name => $size) {
                $img = clone $image;
                [$width, $height] = explode('x', $size);
                $filename = $this->getThumbsDirectory($directory, $name) .
                    $img->filename .
                    '.' .
                    $img->extension;
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($filename, 100);
            }
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
