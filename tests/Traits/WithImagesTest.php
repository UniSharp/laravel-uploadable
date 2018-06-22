<?php

namespace Tests\Traits;

use Mockery as m;
use Tests\TestCase;
use UniSharp\Uploadable\Image;
use Illuminate\Support\Facades\Storage;

class WithImagesTest extends TestCase
{
    public function testBindImage()
    {
        $model = $this->getModel()->BindImage($image = $this->getImage());

        $this->assertTrue($model->image->is($image));
    }

    public function testBindImageWithExtra()
    {
        $model = $this->getModel()->BindFile($image = $this->getImage(), $extra = 'extra');

        $this->assertEquals($model->image->extra, $extra);
    }

    public function testimage()
    {
        $this->getImage()->uploadable()->associate($model = $this->getModel())->save();
        ($image = $this->getImage())->uploadable()->associate($model)->save();

        $this->assertTrue($model->image->is($image));
        $this->assertInstanceOf(Image::class, $model->image);
    }

    public function testimages()
    {
        ($imageA = $this->getImage())->uploadable()->associate($model = $this->getModel())->save();
        ($imageB = $this->getImage())->uploadable()->associate($model)->save();

        $this->assertTrue($model->images[0]->is($imageA));
        $this->assertTrue($model->images[1]->is($imageB));
        $this->assertInstanceOf(Image::class, $model->images[0]);
        $this->assertInstanceOf(Image::class, $model->images[1]);
    }

    public function testDeleteModel()
    {
        Storage::shouldReceive('delete')
               ->with('path')
               ->twice()
               ->andReturn(true);

        ($imageA = $this->getImage())->uploadable()->associate($model = $this->getModel())->save();
        ($imageB = $this->getImage())->uploadable()->associate($model)->save();

        $model->delete();

        $this->assertNull($imageA->fresh());
        $this->assertNull($imageB->fresh());
    }
}
