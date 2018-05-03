<?php
namespace Tests;

use Mockery as m;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use UniSharp\Uploadable\Uploader;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use UniSharp\Uploadable\Http\Controllers\Api\V1\UploadController;

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
        $app = new Container();

        $app->bind(Uploader::class, function () {
            $uploader = m::mock(Uploader::class);
            $uploader->shouldReceive('dropDataWithFile')->andReturn(null);
            return $uploader;
        });
        $class = UploadController::class;
        $response = $app->call("{$class}@delete", ['file_id' => 1]);
        $this->assertTrue($response['success']);
    }
}
