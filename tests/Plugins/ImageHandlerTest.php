<?php
namespace Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\FilesystemAdapter;
use Unisharp\Uploadable\Plugins\ImageHandler;

class ImageHandlerTest extends TestCase
{
    public function testHandle()
    {
        Image::shouldReceive('make')->andReturnSelf();
        Image::shouldReceive('mime')->andReturn('image/png');
        Image::shouldReceive('save')->andReturnSelf();
        Image::shouldReceive('orientate')->andReturnSelf();
        Config::shouldReceive('get')->with('uploadable.use_image_orientate', false)->andReturn(true);
        $handler = new ImageHandler();
        $storage = Mockery::mock(FilesystemAdapter::class);
        $storage->shouldReceive('path')->andReturn('foo/bar');
        $handler->handle($storage, 'foo/bar');
        $this->assertTrue(true);
    }
}
