<?php

namespace UniSharp\Uploadable;

use Illuminate\Database\Eloquent\Model;
use UniSharp\Uploadable\Contracts\FileContract;

class File extends Model implements FileContract
{
    protected $guarded = [];

    protected $appends = ['url_path'];

    public function uploadable()
    {
        return $this->morphTo();
    }

    public function getUrlPathAttribute()
    {
        return url($this->path);
    }
}
