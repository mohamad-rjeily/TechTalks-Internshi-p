<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    // Do NOT use realpath() here; if the directory doesn't exist yet it returns false.
    // Laravel's view compiler expects a string path.
    'compiled' => env('VIEW_COMPILED_PATH') ?: storage_path('framework/views'),
];


