# PSR-15 Support

The `Medz\Cors\PSR\CorsMiddleware` is a PSR-15 server middleware.

## Using

Using this middleware depends on your preference or where you are using PSR-15.

Just like PSR-7 is used in the Slim framework! Based on your specific code pattern for middleware use, you can refer to the use of the Slim framework.

**See**👉 [Using by Slim](https://github.com/medz/cors/blob/master/docs/using-by-slim.md)

## Example

`Medz\Cors\PSR\CorsMiddleware` :
```php
$settings = []; // The config.
$cors = new Medz\Cors\Cors($settings); // Create CORS instance.
$middleware = new Medz\Cors\PSR\CorsMiddleware($cors); // Create CORS middleware instance

// TODO.
```
