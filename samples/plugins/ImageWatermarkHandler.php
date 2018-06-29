<?php
namespace App\Plugins;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\FilesystemAdapter;

class ImageWatermarkHandler extends ImageHandler
{
    public function handle(FilesystemAdapter $storage, $path)
    {
        $origin_image = Image::make($storage->path($path));

        $images = $this->getImages($origin_image);

        collect($images)->each(function ($image) use ($storage) {
            if (in_array($image->mime(), $this->supportedMime)) {
                $watermark = $this->getWatermark($image);
                $image->insert($watermark, 'bottom-right');
                $image->save();
            }
        });
    }

    private function getImages($image)
    {
        $images = [];

        $thumbs = array_keys(Config::get('uploadable.thumbs'));

        collect($thumbs)->each(function ($size) use (&$images, $image) {
            $path = $this->getDirectory($image->dirname, $size) . $image->filename . '.' . $image->extension;

            if (file_exists($path)) {
                $images[] = Image::make($path);
            }
        });

        return $images;
    }

    private function getWatermark($image)
    {
        $watermark = Image::make(storage_path('app' . DIRECTORY_SEPARATOR . 'watermark.png'));

        $watermark->resize($image->getWidth() / 2.5, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $watermark;
    }
}
