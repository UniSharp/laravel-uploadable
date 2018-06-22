<?php

namespace Tests\Traits;

use Mockery as m;
use Tests\TestCase;
use UniSharp\Uploadable\File;
use Illuminate\Support\Facades\Storage;

class WithFilesTest extends TestCase
{
    public function testBindFile()
    {
        $model = $this->getModel()->BindFile($file = $this->getFile());

        $this->assertTrue($model->file->is($file));
    }

    public function testBindFileWithExtra()
    {
        $model = $this->getModel()->BindFile($file = $this->getFile(), $extra = 'extra');

        $this->assertEquals($model->file->extra, $extra);
    }

    public function testFile()
    {
        $this->getFile()->uploadable()->associate($model = $this->getModel())->save();
        ($file = $this->getFile())->uploadable()->associate($model)->save();

        $this->assertTrue($model->file->is($file));
        $this->assertInstanceOf(File::class, $model->file);
    }

    public function testFiles()
    {
        ($fileA = $this->getFile())->uploadable()->associate($model = $this->getModel())->save();
        ($fileB = $this->getFile())->uploadable()->associate($model)->save();

        $this->assertTrue($model->files[0]->is($fileA));
        $this->assertTrue($model->files[1]->is($fileB));
        $this->assertInstanceOf(File::class, $model->files[0]);
        $this->assertInstanceOf(File::class, $model->files[1]);
    }

    public function testDeleteModel()
    {
        Storage::shouldReceive('delete')
               ->with('path')
               ->twice()
               ->andReturn(true);

        ($fileA = $this->getFile())->uploadable()->associate($model = $this->getModel())->save();
        ($fileB = $this->getFile())->uploadable()->associate($model)->save();

        $model->delete();

        $this->assertNull($fileA->fresh());
        $this->assertNull($fileB->fresh());
    }
}
