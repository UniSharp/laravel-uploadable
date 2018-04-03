<?php

use Unisharp\Uploadable\Plugins\ImageHandler;

return [
    'use_default_route' => true,
    'use_image_orientate' => false,
    'plugins' => [
        ImageHandler::class
    ]
];
