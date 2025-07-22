<?php

return [

    'default' => env('BROADCAST_DRIVER', 'reverb'),

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],

       'reverb' => [
        'driver' => 'reverb',
        'host' => env('REVERB_HOST', '127.0.0.1'),
        'port' => env('REVERB_PORT', 6001),
        'scheme' => env('REVERB_SCHEME', 'http'),
    ],

        'log' => [
            'driver' => 'log',
        ],
    ],
];
