<?php

namespace UniSharp\Uploadable\Plugins;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class WatermarkPlugin
{
    public function handle($path)
    {
        $image = Image::make($path);
        $image->insert($this->getWatermark($image), 'bottom-right');
        $image->save();
    }

    protected function getWatermark($image)
    {
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $watermarkPath = $storagePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'watermark.png';
        $watermark = Image::make($watermarkPath);

        $watermark->resize($image->getWidth() / 2.5, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $watermark;
    }
}
