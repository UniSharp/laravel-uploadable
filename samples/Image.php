<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['name', 'size', 'path', 'type'];

    protected $appends = ['status', 'thumb'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getPathAttribute($path)
    {
        return url($path);
    }

    public function getThumbAttribute()
    {
        return collect(['s', 'm', 'l'])->flip()->map(function ($_, $size) {
            return url(preg_replace('/^(images\/)/', "\$1{$size}/", $this->attributes['path']));
        })->toArray();
    }

    public function getStatusAttribute($path)
    {
        return 'success';
    }
}
