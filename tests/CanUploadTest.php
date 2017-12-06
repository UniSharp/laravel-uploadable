<?php
namespace Tests;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Unisharp\Uploadable\CanUpload;
use Unisharp\Uploadable\File;

class CanUploadTest extends TestCase
{
    public function testUpload()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz'
        ];

        $mock = $this->getMockForTrait(CanUpload::class, [], '', true, true, true, ['saveToDb']);
        $mock->expects($this->any())
            ->method('saveToDb')
            ->will($this->returnValue($file_data));

        $file = m::mock(File::class);

        $request = m::mock(Request::class);
        $request->shouldReceive('file')->with('file')->once()->andReturn($file);
        $request->shouldReceive('file')->with('file')->once()->andReturn(null);

        $this->assertEquals($file_data, $mock->upload($request, 'file'));
        $this->assertEquals(null, $mock->upload($request, 'file'));
    }

    public function testSaveToDb()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz'
        ];

        $file = m::mock(File::class);
        $file->shouldReceive('store')->andReturn($file_data['path']);
        $file->shouldReceive('getClientOriginalName')->andReturn($file_data['path']);
        $file->shouldReceive('getMimeType')->andReturn($file_data['path']);

        $fake_model = m::mock(stdClass::class);
        $fake_model->shouldReceive('create')->andReturn($file_data);

        $mock = $this->getMockForTrait(CanUpload::class, [], '', true, true, true, ['files']);
        $mock->expects($this->any())
            ->method('files')
            ->will($this->returnValue($fake_model));

        $this->assertEquals($file_data, $mock->saveToDb($file));
    }
}
