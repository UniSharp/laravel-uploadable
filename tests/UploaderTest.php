<?php

namespace Tests;

use Mockery as m;
use Illuminate\Http\Testing\File;
use UniSharp\Uploadable\Uploader;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use UniSharp\Uploadable\File as FileModel;
use UniSharp\Uploadable\Image as ImageModel;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class UploaderTest extends TestCase
{
    public function testUpload()
    {
        $uploadedFile = File::image(str_random() . '.pdf');

        Container::getInstance()->bind(FilesystemFactory::class, function () use ($uploadedFile) {
            $mock = m::mock(FilesystemFactory::class);

            $mock->shouldReceive('disk->putFileAs')
                 ->with('files', $uploadedFile, m::type('string'), [])
                 ->once()
                 ->andReturn('path');

            return $mock;
        });

        Config::shouldReceive('get')->with('uploadable.plugins.file', [])->once()->andReturn([]);
        URL::shouldReceive('to')->with('path')->once()->andReturn('url-path');

        $file = Uploader::upload($uploadedFile);

        $this->assertInstanceOf(FileModel::class, $file);
        $this->assertEquals('url-path', $file->path);
        $this->assertEquals($uploadedFile->name, $file->name);
        $this->assertEquals($uploadedFile->getMimeType(), $file->mime);
        $this->assertEquals($uploadedFile->getSize(), $file->size);
    }

    public function testUploadImage()
    {
        $uploadedFile = File::image(str_random() . '.png');

        Container::getInstance()->bind(FilesystemFactory::class, function () use ($uploadedFile) {
            $mock = m::mock(FilesystemFactory::class);

            $mock->shouldReceive('disk->putFileAs')
                 ->with('images', $uploadedFile, m::type('string'), [])
                 ->once()
                 ->andReturn('path');

            return $mock;
        });

        Config::shouldReceive('get')->with('uploadable.plugins.image', [])->once()->andReturn([]);

        $image = Uploader::upload($uploadedFile);

        $this->assertInstanceOf(ImageModel::class, $image);
    }
}
