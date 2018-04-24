<?php

use Unisharp\Uploadable\Plugins\ImageHandler;

return [
    'use_default_route' => true,
    'use_image_orientate' => false,
    'thumbs' => [
        's' => '96x96',
        'm' => '256x256',
        'l' => '480x480'
    ],
    'plugins' => [
        ImageHandler::class
    ]
];
