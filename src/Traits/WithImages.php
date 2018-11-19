<?php

namespace UniSharp\Uploadable\Traits;

use UniSharp\Uploadable\File;
use UniSharp\Uploadable\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait WithImages
{
    use Helpers;

    public static function bootWithImages(): void
    {
        static::deleted(function ($model) {
            $model->images->each(function ($image) {
                $image->delete();
            });
        });
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'uploadable')->whereNull('extra')->latest('id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'uploadable');
    }

    public function bindImage(File $image, $extra = null): self
    {
        return $this->bindUploadable($image, $extra);
    }
}
