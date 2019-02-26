# PSR-15 Support

The `Medz\Cors\PSR\CorsMiddleware` is a PSR-15 server middleware.

## Using

Using this middleware depends on your preference or where you are using PSR-15.

Just like PSR-7 is used in the Slim framework! Based on your specific code pattern for middleware use, you can refer to the use of the Slim framework.

## Example

`Medz\Cors\PSR\CorsMiddleware` :
```php

// Settings.
$settings = [
    'allow-credentials'  => false,
    'allow-headers'      => ['*'],
    'expose-headers'     => [],
    'origins'            => ['*'],
    'methods'            => ['*'],
    'max-age'            => 0,
];

// $cors = new Medz\Cors\Cors($settings); // Create CORS instance.

// Create CORS middleware instance
$middleware = new Medz\Cors\PSR\CorsMiddleware($settings /* $cors */);

// TODO.
```

**See**👉 [Using by Slim](https://github.com/medz/cors/blob/master/docs/using-by-slim.md)
