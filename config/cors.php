<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => env('CORS_ALLOWED_ORIGINS')
        ? explode(',', env('CORS_ALLOWED_ORIGINS'))
        : (env('APP_ENV') === 'production'
            ? [
                'https://app.innovaperu.edu.pe',
                'https://innovaperu.edu.pe',
            ]
            : [
                'http://localhost:3000',
                'http://127.0.0.1:3000',
            ]
        ),

    // 'allowed_origins' => [
    //     'https://app.innovaperu.edu.pe',
    //     'https://innovaperu.edu.pe',
    // ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
