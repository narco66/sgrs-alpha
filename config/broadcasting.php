
<?php

return [
    'pusher' => [
        'driver' => 'pusher',
        'key'    => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster'   => env('PUSHER_APP_CLUSTER'),
            'useTLS'    => true,
            'host'      => env('PUSHER_HOST', 'api-pusher.com'),
            'port'      => env('PUSHER_PORT', 443),
            'scheme'    => env('PUSHER_SCHEME', 'https'),
        ],
    ],
];
