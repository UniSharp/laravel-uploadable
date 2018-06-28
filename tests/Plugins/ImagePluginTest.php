<?php

namespace Tests;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use UniSharp\Uploadable\Uploader;
use Illuminate\Container\Container;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use UniSharp\Uploadable\Plugins\ImagePlugin;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class ImagePluginTest extends TestCase
{
    public function testHandle()
    {
        $uploadedFile = File::image(str_random() . '.png');

        Container::getInstance()->bind(FilesystemFactory::class, function () use ($uploadedFile) {
            $mock = m::mock(FilesystemFactory::class);

            $mock->shouldReceive('disk->putFileAs')->andReturn('path');

            return $mock;
        });

        Storage::shouldReceive('disk->getDriver->getAdapter->getPathPrefix')
            ->andReturn('/app');

        Image::shouldReceive('make')
            ->once()
            ->andSet('dirname', 'foo')
            ->andSet('filename', 'bar')
            ->andSet('extension', 'png')
            ->andReturnSelf();

        Image::shouldReceive('orientate')->once();
        Image::shouldReceive('save')->with('/app/path', 100)->once();
        Image::shouldReceive('resize')->with(96, 96, m::type('closure'))->once()->andReturnSelf();
        Image::shouldReceive('save')->with('foo/s/bar.png', 100)->once();
        Image::shouldReceive('resize')->with(256, 256, m::type('closure'))->once()->andReturnSelf();
        Image::shouldReceive('save')->with('foo/m/bar.png', 100)->once();
        Image::shouldReceive('resize')->with(480, 480, m::type('closure'))->once()->andReturnSelf();
        Image::shouldReceive('save')->with('foo/l/bar.png', 100)->once();

        Config::shouldReceive('get')->with('uploadable.plugins.image', [])->once()->andReturn([ImagePlugin::class]);
        Config::shouldReceive('get')->with('uploadable.use_image_orientate', false)->andReturn(true);
        Config::shouldReceive('get')->with('uploadable.thumbs', [])->andReturn([
            's' => '96x96',
            'm' => '256x256',
            'l' => '480x480'
        ]);

        FileFacade::shouldReceive('exists')->with('foo/s/')->once()->andReturn(false);
        FileFacade::shouldReceive('exists')->with('foo/m/')->once()->andReturn(false);
        FileFacade::shouldReceive('exists')->with('foo/l/')->once()->andReturn(false);
        FileFacade::shouldReceive('makeDirectory')->with('foo/s/')->once();
        FileFacade::shouldReceive('makeDirectory')->with('foo/m/')->once();
        FileFacade::shouldReceive('makeDirectory')->with('foo/l/')->once();

        Uploader::upload($uploadedFile);

        $this->assertTrue(true);
    }
}
