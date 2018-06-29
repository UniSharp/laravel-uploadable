<?php

namespace Tests\Plugins;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use UniSharp\Uploadable\Uploader;
use Illuminate\Container\Container;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use UniSharp\Uploadable\Plugins\WatermarkPlugin;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class WatermarkPluginTest extends TestCase
{
    public function testHandle()
    {
        $uploadedFile = File::image(str_random() . '.png');

        Container::getInstance()->bind(FilesystemFactory::class, function () use ($uploadedFile) {
            $mock = m::mock(FilesystemFactory::class);

            $mock->shouldReceive('disk->putFileAs')->andReturn('path');

            return $mock;
        });

        Storage::shouldReceive('disk->getDriver->getAdapter->getPathPrefix')->andReturn('storage');

        Image::shouldReceive('make')
            ->once()
            ->andSet('dirname', 'foo')
            ->andSet('filename', 'bar')
            ->andSet('extension', 'png')
            ->andReturnSelf();

        Image::shouldReceive('make')->once()->andReturnSelf();
        Image::shouldReceive('getWidth')->once()->andReturn(100);
        Image::shouldReceive('resize')->with(40, null, m::type('closure'))->once()->andReturnSelf();
        Image::shouldReceive('insert')->with(Image::getFacadeRoot(), 'bottom-right')->once()->andReturnSelf();
        Image::shouldReceive('save')->once();

        Config::shouldReceive('get')->with('uploadable.plugins.image', [])->once()->andReturn([WatermarkPlugin::class]);

        Uploader::upload($uploadedFile);

        $this->assertTrue(true);
    }
}
