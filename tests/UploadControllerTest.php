<?php
namespace Tests;

use Illuminate\Http\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Unisharp\Uploadable\File;
use Unisharp\Uploadable\UploadController;
use Unisharp\Uploadable\Uploader;

class UploadControllerTest extends TestCase
{
    public function testStore()
    {
        $request = m::mock(Request::class);
        $request->shouldReceive('file')->andReturn(['foo'])->once();

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
