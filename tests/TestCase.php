<?php

namespace Tests;

use Mockery as m;
use CreateFilesTable;
use UniSharp\Uploadable\File;
use UniSharp\Uploadable\Image;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use UniSharp\Uploadable\Traits\WithFiles;
use UniSharp\Uploadable\Traits\WithImages;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $app = m::mock(Container::class);
        $app->shouldReceive('instance');
        $app->shouldReceive('offsetGet')->with('db')->andReturn(
            m::mock('db')->shouldReceive('connection')->andReturn(
                m::mock('connection')->shouldReceive('getSchemaBuilder')->andReturn('schema')->getMock()
            )->getMock()
        );
        $app->shouldReceive('offsetGet');

        Schema::setFacadeApplication($app);
        Schema::swap(Manager::schema());

        (new CreateFilesTable)->up();
    }

    public function tearDown()
    {
        (new CreateFilesTable)->down();

        m::close();

        Facade::clearResolvedInstances();

        Container::getInstance()->flush();

        parent::tearDown();
    }

    protected function getModel()
    {
        Schema::create($table = str_random(), function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $model = new class extends Model {
            use WithFiles, WithImages {
                WithImages::bindUploadable insteadof WithFiles;
            }
        };

        $model->setTable($table)->save();

        return $model;
    }

    protected function getFile(): File
    {
        return File::create([
            'name' => 'name',
            'mime' => 'mime',
            'size' => 100000,
            'path' => 'path',
        ]);
    }

    protected function getImage(): Image
    {
        return Image::create([
            'name' => 'name',
            'mime' => 'mime',
            'size' => 100000,
            'path' => 'path',
        ]);
    }
}
