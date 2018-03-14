# CORS

PHP CORS (Cross-origin resource sharing) middleware.

## Support

- Array, Native PHP coding.
- Using PSR-7
- Laravel
- Symfony

## Using

```shell
composer require medz/cors
```

Example:

```php
use Medz\Cors\Cors;

$cors = new Cors($config); // The $config 👉 `config/cors.php` file.
$cors->setRequest($requestType, $request); // The $request is empty array or is `NULL` or $_REQUEST
$cors->setResponse($responseType, $response) // The $response is empty array.
$cors->handle();

$response = $cors->getResponse();
```

Both `$requestType` and `$responseType` are of any type that is individually set to support, but if used in a framework, these two values are usually the same. Because you can set it up individually, you can deliver different values depending on your needs to achieve your goals.

### Array

```php
use Medz\Cors\Cors;

$cors = new Cors($config); // The $config 👉 `config/cors.php` file.
$cors->setRequest('array', $request); // The $request is empty array or is `NULL` or $_REQUEST
$cors->setResponse('array', $response) // The $response is empty array.
$cors->handle();

$response = $cors->getResponse();
```

### PSR-7

```php
use Medz\Cors\Cors;

$cors = new Cors($config);
$cors->setRequest('psr-7', $request);
$cors->setResponse('psr-7', $response);

$response = $cors->getResponse();
```

### Laravel

Add `Medz\Cors\Laravel\Middleware\Cors::class` to you app file `app/Http/Kernel.php`, Here is an example of an API middleware group:

```php
protected $middlewareGroups => [
    'api' => [
        Medz\Cors\Laravel\Middleware\Cors::class,
    ]
];
```

### Symfony

> ⚠️The framework has not provided a method yet. Please wait!

### Other

Because of the interface features provided by this package, you can implement it in a small amount of code in any other framework.
