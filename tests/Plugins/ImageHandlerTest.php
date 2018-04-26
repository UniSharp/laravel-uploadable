<?php
namespace Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\FilesystemAdapter;
use UniSharp\Uploadable\Plugins\ImageHandler;

class ImageHandlerTest extends TestCase
{
    public function testHandle()
    {
        File::shouldReceive('exists')->andReturn(true);
        Image::shouldReceive('make')
            ->andReturnSelf()
            ->andSet('dirname', 'foo')
            ->andSet('filename', 'bar')
            ->andSet('extension', 'png');
        Image::shouldReceive('mime')->andReturn('image/png');
        Image::shouldReceive('save')->andReturnSelf();
        Image::shouldReceive('save')->with('foo/bar/l')->andReturnSelf();
        Image::shouldReceive('save')->with('foo/bar/m')->andReturnSelf();
        Image::shouldReceive('save')->with('foo/bar/s')->andReturnSelf();
        Image::shouldReceive('orientate')->andReturnSelf();
        Image::shouldReceive('resize')->andReturnSelf();
        Config::shouldReceive('get')->with('uploadable.use_image_orientate', false)->andReturn(true);
        Config::shouldReceive('get')->with('uploadable.thumbs', false)->andReturn([
            's' => '96x96',
            'm' => '256x256',
            'l' => '480x480'
        ]);
        $handler = new ImageHandler();
        $storage = Mockery::mock(FilesystemAdapter::class);
        $storage->shouldReceive('path')->andReturn('foo/bar');
        $handler->handle($storage, 'foo/bar');
        $this->assertTrue(true);
    }
}
