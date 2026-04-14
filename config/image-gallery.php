<?php

// config for Alsaloul/ImageGallery

return [
    /*
    |--------------------------------------------------------------------------
    | Viewer.js Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how Viewer.js is loaded. By default, it's loaded from CDN.
    | Set 'cdn' to false if you want to bundle it locally.
    |
    */
    'viewer_js' => [
        'cdn' => true,
        'version' => '1.11.6',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These are the default values used by the image gallery components.
    | You can override these when using the components.
    |
    */
    'defaults' => [
        'thumb_width' => 128,
        'thumb_height' => 128,
        'rounded' => 'rounded-lg',
        'gap' => 'gap-4',
        'stacked' => true,
        'limit' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Viewer Options
    |--------------------------------------------------------------------------
    |
    | You can change the global default options for Viewer.js
    | @see https://github.com/fengyuanchen/viewerjs/blob/main/README.md#options
    |
    */
    'viewer_options' => [
        'toolbar' => [
            'zoomIn' => 1,
            'zoomOut' => 1,
            'oneToOne' => 1,
            'reset' => 1,
            'prev' => 1,
            'play' => 0,
            'next' => 1,
            'rotateLeft' => 1,
            'rotateRight' => 1,
            'flipHorizontal' => 1,
            'flipVertical' => 1,
        ],
        'navbar' => false,
        'inline' => false,
        'movable' => true,
        'rotatable' => true,
        'scalable' => true,
        'fullscreen' => true,
        'transition' => true,
        'title' => false,
    ],
];
