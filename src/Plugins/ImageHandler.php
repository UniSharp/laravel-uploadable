<?php
namespace Unisharp\Uploadable\Plugins;

use Illuminate\Filesystem\FilesystemAdapter;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

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
        $image = Image::make($storage->path($path));
        if (in_array($image->mime(), $this->supportedMime)) {
            if (Config::get('uploadable.use_image_orientate', false)) {
                $image->orientate();
            }

            $image->save();

            $directory = $image->dirname;
            foreach (Config::get('uploadable.thumbs', []) as $name => $size) {
                [$width, $height] = explode('x', $size);
                $filename = $this->getThumbsDirectory($directory, $name) .
                    $image->filename .
                    '.' .
                    $image->extension;
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($filename);
            }
        }
    }

    public function getThumbsDirectory($baseDir, $name)
    {
        $directory = $baseDir . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        return $directory;
    }
}
