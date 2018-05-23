<?php
namespace Tests;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use Mockery as m;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use UniSharp\Uploadable\Contracts\FileContract;
use UniSharp\Uploadable\File;
use UniSharp\Uploadable\Http\Controllers\Api\V1\UploadController;
use UniSharp\Uploadable\Uploader;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\App;
use UniSharp\Uploadable\Http\Controllers\Api\V1\UploadController;

class UploadControllerTest extends TestCase
{
    public function testStore()
    {
        $app = new Container();

        $request = m::mock(Request::class);
        $request->shouldReceive('file')->andReturn(null);

        $uploader = m::mock(Uploader::class);
        $uploader->shouldReceive('saveDataWithFile')->andReturn(null)->once();
        $uploader->shouldReceive('saveDataWithFile')->andReturn('bar')->once();

        $app->bind(Request::class, function () use ($request) {
            return $request;
        });

        $app->bind(Uploader::class, function () use ($uploader) {
            return $uploader;
        });

        $response1 = $app->call(UploadController::class . '@store', [$request]);
        $response2 = $app->call(UploadController::class . '@store', [$request]);

        $this->assertEquals(['success' => false], $response1);
        $this->assertEquals('bar', $response2);
    }

    public function testDelete()
    {
        $app = new Container();
        $file = new File(['id' => 1]);

        $app->bind(Uploader::class, function () {
            $uploader = m::mock(Uploader::class);
            $uploader->shouldReceive('dropDataWithFile')->andReturn(null);
            return $uploader;
        });

        $app->bind(FileContract::class, function () use ($file) {
            return $file;
        });

        $response = $app->call(UploadController::class . '@delete', [$file->id]);

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
