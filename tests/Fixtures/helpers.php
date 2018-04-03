<?php
use Mockery;

function app($class)
{
    return Mockery::mock(ImageHandler::class)->shouldReceive('handle');
}
