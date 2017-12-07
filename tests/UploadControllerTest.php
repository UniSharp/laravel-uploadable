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
        $file_data = [
            'path' => 'foo/bar',
            'name' => 'bar',
            'type' => 'baz'
        ];

        $request = m::mock(Request::class);

        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('upload')->andReturn(null)->once();
        $uploader->shouldReceive('upload')->with($request)->andReturn($file_data)->once();
        $response1 = (new UploadController)->store($request, $uploader);
        $response2 = (new UploadController)->store($request, $uploader);

        $this->assertFalse($response1['success']);
        $this->assertEquals($file_data, $response2);
    }

    public function testDelete()
    {
        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('removeFiles')->andReturn(null);
        $response = (new UploadController)->delete(new File, $uploader);

        $this->assertTrue($response['success']);
    }
}
