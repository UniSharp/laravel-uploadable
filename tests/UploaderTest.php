<?php
namespace Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use UniSharp\Uploadable\Uploader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class FakeModel
{
    public function create($input)
    {
        return $input;
    }
}

class UploaderTest extends TestCase
{
    public function testSaveDataWithFile()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz',
            'size' => 0
        ];

        $file = m::mock(UploadedFile::class);
        $file->shouldReceive('store')->andReturn($file_data['path']);
        $file->shouldReceive('getClientOriginalName')->andReturn($file_data['name']);
        $file->shouldReceive('getMimeType')->andReturn($file_data['type']);
        $file->shouldReceive('getSize')->andReturn($file_data['size']);
        Config::shouldReceive('get')->with('uploadable.plugins', [])->andReturn([]);

        $fake_model = new FakeModel;

        $uploader = new Uploader($fake_model);

        $this->assertEquals($file_data, $uploader->saveDataWithFile($file, null));

    }

    public function testSaveDataWithFileWithModel()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz',
            'size' => 0
        ];

        $file = m::mock(UploadedFile::class);
        $file->shouldReceive('store')->andReturn($file_data['path']);
        $file->shouldReceive('getClientOriginalName')->andReturn($file_data['name']);
        $file->shouldReceive('getMimeType')->andReturn($file_data['type']);
        $file->shouldReceive('getSize')->andReturn($file_data['size']);
        Config::shouldReceive('get')->with('uploadable.plugins', [])->andReturn([]);

        $fake_model = new FakeModel;

        $uploader = new Uploader($fake_model);
        $model = new \stdClass;
        $model->id = 1;

        $file_data['uploadable_id'] = 1;
        $file_data['uploadable_type'] = 'stdClass';

        $uploader = new Uploader($fake_model);

        $this->assertArrayHasKey('uploadable_type', $uploader->saveDataWithFile($file, $model));
    }

    public function testUploadWithPlugin()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz',
            'size' => 0
        ];

        $file = m::mock(UploadedFile::class);
        $file->shouldReceive('store')->andReturn($file_data['path']);
        $file->shouldReceive('getClientOriginalName')->andReturn($file_data['name']);
        $file->shouldReceive('getMimeType')->andReturn($file_data['type']);
        $file->shouldReceive('getSize')->andReturn($file_data['size']);
        $handler = m::mock(ImageHandler::class);
        $handler->shouldReceive('handle')->with(m::any(), m::type('string'));
        App::shouldReceive('make')->andReturn($handler);
        Storage::shouldReceive('disk')->andReturn(m::mock(FilesystemAdapter::class));

        Config::shouldReceive('get')->with('uploadable.plugins', [])->andReturn([
            ImageHandler::class
        ]);

        $fake_model = new FakeModel;

        $uploader = new Uploader($fake_model);

        $this->assertEquals($file_data, $uploader->saveDataWithFile($file, null));
    }

    public function testDropDataWithFile()
    {
        $fake_model = m::mock(FakeModel::class);
        $fake_model->shouldReceive('find')->with(1)->andReturn((object)['path' => 'foo'])->once();
        $fake_model->shouldReceive('destroy')->with(1)->andReturn(null)->once();

        Storage::shouldReceive('delete')->with('foo')->andReturn(null)->once();

        $uploader = new Uploader($fake_model);

        $this->assertNull($uploader->dropDataWithFile(1));
    }
}
