<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => ':memory:',
]);

$capsule->setEventDispatcher(new Dispatcher(Container::getInstance()));

$capsule->setAsGlobal();

$capsule->bootEloquent();
