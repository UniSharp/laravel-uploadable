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
        'image/gif',
    ];

    public function handle(FilesystemAdapter $storage, $path)
    {
        $image = Image::make($storage->path($path));
        if (in_array($image->mime(), $this->supportedMime)) {
            if (Config::get('uploadable.use_image_orientate')) {
                $image->orientate();
            }

            $image->save();
        }
    }
}
