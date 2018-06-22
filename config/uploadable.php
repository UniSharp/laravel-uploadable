<?php

use UniSharp\Uploadable\Plugins\ImagePlugin;

return [
    'use_image_orientate' => false,
    'thumbs' => [
        's' => '96x96',
        'm' => '256x256',
        'l' => '480x480'
    ],
    'plugins' => [
        'image' => [
            ImagePlugin::class
        ]
    ]
];
