<?php
namespace Tests;

use Illuminate\Support\Facades\Storage;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Unisharp\Uploadable\Uploader;

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
            'type' => 'baz'
        ];

        $file = m::mock(\stdClass::class);
        $file->shouldReceive('store')->andReturn($file_data['path']);
        $file->shouldReceive('getClientOriginalName')->andReturn($file_data['name']);
        $file->shouldReceive('getMimeType')->andReturn($file_data['type']);

        $fake_model = new FakeModel;

        $uploader = new Uploader($fake_model);

        $this->assertEquals($file_data, $uploader->saveDataWithFile($file, null));

        $model = new \stdClass;
        $model->id = 1;

        $file_data['uploadable_id'] = 1;
        $file_data['uploadable_type'] = 'stdClass';

        $uploader = new Uploader($fake_model);

        $this->assertArrayHasKey('uploadable_type', $uploader->saveDataWithFile($file, $model));
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
