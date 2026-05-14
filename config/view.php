<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Configure the location where your compiled Blade templates will be
    | stored. This should typically be within the storage directory.
    |
    */

    'compiled' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),
    'compiled_path' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),

    /*
    |--------------------------------------------------------------------------
    | View Cache
    |--------------------------------------------------------------------------
    |
    | When enabled, the compiled views are cached. This should be enabled
    | in production to boost performance.
    |
    */

    'cache' => env('VIEW_CACHE', true),

    /*
    |--------------------------------------------------------------------------
    | Compiled View Extension
    |--------------------------------------------------------------------------
    |
    | This is the file extension used for compiled views.
    |
    */

    'compiled_extension' => env('VIEW_COMPILED_EXTENSION', 'php'),

    /*
    |--------------------------------------------------------------------------
    | Determine if View Cache Should Respect Timestamps
    |--------------------------------------------------------------------------
    |
    | When enabled, Blade will check timestamps to determine whether cached
    | compiled views are outdated.
    |
    */

    'check_cache_timestamps' => env('VIEW_CHECK_CACHE_TIMESTAMPS', true),

    /*
    |--------------------------------------------------------------------------
    | View Relative Hash
    |--------------------------------------------------------------------------
    |
    | When enabled, the view compiled path will include a relative hash.
    |
    */

    'relative_hash' => env('VIEW_RELATIVE_HASH', false),

    /*
    |--------------------------------------------------------------------------
    | Blade Component Class Namespace
    |--------------------------------------------------------------------------
    |
    | This is used by Blade components to resolve class based components.
    |
    */

    'component_namespace' => env('VIEW_COMPONENT_NAMESPACE', null),

    /*
    |--------------------------------------------------------------------------
    | Blade Components View Directory
    |--------------------------------------------------------------------------
    |
    | This is the directory used for class-based components.
    |
    */

    'components' => [

        'path' => resource_path('views/components'),

        'extensions' => ['blade.php'],

        'namespace' => 'App\\View\\Components',

    ],

];
