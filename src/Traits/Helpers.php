<?php

namespace UniSharp\Uploadable\Traits;

use UniSharp\Uploadable\File;

trait Helpers
{
    protected function bindUploadable(File $file, $extra = null): self
    {
        if ($extra) {
            $file->extra = $extra;
        }

        $file->uploadable()->associate($this)->save();

        return $this;
    }
}
