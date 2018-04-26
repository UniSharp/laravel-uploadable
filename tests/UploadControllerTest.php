<?php
namespace Tests;

use Mockery as m;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use UniSharp\Uploadable\Http\Controllers\Api\V1\UploadController;
use UniSharp\Uploadable\Uploader;

class UploadControllerTest extends TestCase
{
    public function testStore()
    {
        $request = m::mock(Request::class);
        $request->shouldReceive('file')->andReturn(m::mock(UploadedFile::class))->once();

        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('saveDataWithFile')->andReturn(null)->once();
        $uploader->shouldReceive('saveDataWithFile')->andReturn('bar')->once();

        $response1 = (new UploadController)->store($request, $uploader);
        $response2 = (new UploadController)->store($request, $uploader);

        $this->assertFalse($response1['success']);
        $this->assertEquals('bar', $response2);
    }

    public function testDelete()
    {
        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('dropDataWithFile')->andReturn(null);
        $response = (new UploadController)->delete(1, $uploader);

        $this->assertTrue($response['success']);
    }
}
