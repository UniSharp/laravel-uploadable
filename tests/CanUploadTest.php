<?php
namespace Tests;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Unisharp\Uploadable\CanUpload;
use Unisharp\Uploadable\File;
use Unisharp\Uploadable\Uploader;

class CanUploadTest extends TestCase
{
    public function testUpload()
    {
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz'
        ];

        $mock = $this->getMockForTrait(CanUpload::class);

        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('saveDataWithFile')->once()->andReturn($file_data);
        $uploader->shouldReceive('saveDataWithFile')->once()->andReturn($file_data);

        $request = m::mock(Request::class);
        $request->shouldReceive('file')->once()->andReturn([]);
        $request->shouldReceive('file')->once()->with('file_key')->andReturn(null);
        $request->shouldReceive('file')->once()->andReturn(['foo']);
        $request->shouldReceive('file')->once()->with('file_key')->andReturn('foo');

        $this->assertNull($mock->upload($request, null));
        $this->assertNull($mock->upload($request, 'file_key'));
        $this->assertEquals($file_data, $mock->upload($request, null, $uploader));
        $this->assertEquals($file_data, $mock->upload($request, 'file_key', $uploader));
    }

    public function testRemoveFiles()
    {
        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('dropDataWithFile')->andReturn(null)->once();

        $file_model = $this->getMockBuilder(File::class)
             ->disableOriginalConstructor()
             ->getMock();
        $file_model->id = 1;

        $mock = $this->getMockForTrait(CanUpload::class, [], '', true, true, true, ['files', 'whereIn', 'get']);
        $mock->expects($this->any())->method('files')
            ->will($this->returnValue($mock));
        $mock->expects($this->any())->method('whereIn')
            ->will($this->returnValue($mock));
        $mock->expects($this->any())->method('get')
            ->will($this->returnValue([$file_model]));

        $this->assertNull($mock->removeFiles([], $uploader));
    }
}
