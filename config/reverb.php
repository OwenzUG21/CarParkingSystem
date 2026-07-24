<?php

return [
    'apps' => [
        [
            'app_id' => env('REVERB_APP_ID'),
            'app_key' => env('REVERB_APP_KEY'),
            'app_secret' => env('REVERB_APP_SECRET'),
            'allowed_origins' => ['*'],
        ],
    ],
    'server' => [
        'host' => env('REVERB_HOST', '0.0.0.0'),
        'port' => env('REVERB_PORT', 8080),
        'hostname' => env('REVERB_HOSTNAME', 'localhost'),
        'max_request_size' => 10_000,
    ],
];
