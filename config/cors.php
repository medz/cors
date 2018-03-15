<?php

return [
    'allow-headers'  => ['*'], // ex: Content-Type, Accept, X-Requested-With
    'expose-headers' => [],
    'origins'        => ['*'], // ex: http://localhost
    'methods'        => ['*'], // ex: GET, POST, PUT, PATCH, DELETE
    'max-age'        => 0,
    'laravel'        => [
        'prepend-global-middleware' => true,
        'using-http-message-type'   => 'symfony', // Laravel using `symfong/http-foundation` package, Default using symfony, If you use PSR-7 in Laravel, set `using-http-message-type` to `psr-7`.
        'allow-route-perfix' => '',
    ],
];
