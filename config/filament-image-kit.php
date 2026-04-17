<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Thumbnail Settings
    |--------------------------------------------------------------------------
    */
    'thumbnail' => [
        'enabled' => false,  // Off by default, user enables when needed
        'width' => env('IMAGE_KIT_THUMBNAIL_WIDTH', 150),
        'height' => env('IMAGE_KIT_THUMBNAIL_HEIGHT', 150),
        'quality' => env('IMAGE_KIT_THUMBNAIL_QUALITY', 80),
        'suffix' => env('IMAGE_KIT_THUMBNAIL_SUFFIX', '_thumb'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Blur Settings
    |--------------------------------------------------------------------------
    */
    'blur' => [
        'enabled' => false,  // Off by default
        'intensity' => env('IMAGE_KIT_BLUR_INTENSITY', 15),  // Higher = more blur
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    */
    'driver' => env('IMAGE_KIT_DRIVER', 'gd'),
];