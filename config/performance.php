<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Optimization Settings
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'settings' => env('CACHE_SETTINGS', true),
        'categories' => env('CACHE_CATEGORIES', true),
        'products' => env('CACHE_PRODUCTS', true),
        'ttl' => env('CACHE_TTL', 3600), // 1 hour in seconds
    ],

    'images' => [
        'lazy_loading' => env('LAZY_LOADING', true),
        'webp_support' => env('WEBP_SUPPORT', true),
        'compression_quality' => env('IMAGE_COMPRESSION_QUALITY', 85),
    ],

    'minification' => [
        'css' => env('MINIFY_CSS', true),
        'js' => env('MINIFY_JS', true),
        'html' => env('MINIFY_HTML', false),
    ],

    'gzip' => [
        'enabled' => env('GZIP_ENABLED', true),
        'level' => env('GZIP_LEVEL', 6),
    ],
];