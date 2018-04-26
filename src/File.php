<?php

namespace UniSharp\Uploadable;

use Illuminate\Database\Eloquent\Model;

class File extends Model
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
