<?php

namespace UniSharp\Uploadable;

use Illuminate\Support\Facades\URL;

class Image extends File
{
    protected $table = 'files';

    protected $appends = ['thumb'];

    public function getThumbAttribute(): array
    {
        return collect(['s', 'm', 'l'])->flip()->map(function ($_, $size) {
            return URL::to(preg_replace('/^(images\/)/', "\$1{$size}/", $this->attributes['path']));
        })->toArray();
    }
}
