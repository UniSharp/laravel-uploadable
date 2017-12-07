<?php

namespace Unisharp\Uploadable;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    public function uploadable()
    {
        return $this->morphTo();
    }
}
