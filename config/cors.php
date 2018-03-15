<?php

return [
    'allow-credentiails' => false, // set "Access-Control-Allow-Credentials" 👉 string "false" or "true".
    'allow-headers'      => ['*'], // ex: Content-Type, Accept, X-Requested-With
    'expose-headers'     => [],
    'origins'            => ['*'], // ex: http://localhost
    'methods'            => ['*'], // ex: GET, POST, PUT, PATCH, DELETE
    'max-age'            => 0,
    'laravel'            => [
        'allow-route-perfix' => '*', // The perfix is using \Illumante\Http\Request::is method. 👉 
        'route-grouo-mode'   => false,
    ],
];
