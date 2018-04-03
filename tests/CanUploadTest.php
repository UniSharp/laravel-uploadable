<?php
namespace Tests;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use Unisharp\Uploadable\Uploader;
use Unisharp\Uploadable\CanUpload;

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

        $this->assertNull($mock->upload(null, $uploader));
        $this->assertEquals($file_data, $mock->upload(m::mock(UploadedFile::class), $uploader));
    }

    public function testRemoveFiles()
    {
        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('dropDataWithFile')->andReturn(null)->once();

        $file_model = new \stdClass;
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
