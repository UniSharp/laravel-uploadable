<?php

namespace Tests;

use Mockery as m;
use Illuminate\Support\Facades\URL;

class ImageTest extends TestCase
{
    public function testThumb()
    {
        URL::shouldReceive('to')->with('path')->once()->andReturn('s-path');
        URL::shouldReceive('to')->with('path')->once()->andReturn('m-path');
        URL::shouldReceive('to')->with('path')->once()->andReturn('l-path');

        $this->assertEquals([
            's' => 's-path',
            'm' => 'm-path',
            'l' => 'l-path',
        ], $this->getImage()->thumb);
    }
}
