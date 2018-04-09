# CORS

[![StyleCI](https://styleci.io/repos/125001413/shield?branch=master)](https://styleci.io/repos/125001413)
[![Build Status](https://travis-ci.org/medz/cors.svg?branch=master)](https://travis-ci.org/medz/cors)

PHP CORS (Cross-origin resource sharing) middleware.

## Support

- [x] 🐘[Array, Coding in Native PHP](#array)
- [x] 🔌[Using PSR-7](#prs-7)
- [ ] Symfony Support
- [x] [Laravel Support](https://github.com/medz/cors/blob/master/docs/using-by-laravel.md)
- [x] [Lumen Support](https://github.com/medz/cors/blob/master/docs/using-by-lumen.md)
- [ ] Yii2 Support
- [ ] Slim Framework

## Using

```shell
composer require medz/cors
```

Example:

```php
use Medz\Cors\Cors;

$cors = new Cors($config); // The $config 👉 `config/cors.php` file.
$cors->setRequest($requestType, $request); // The $request is empty array or is `NULL` or $_REQUEST
$cors->setResponse($responseType, $response); // The $response is empty array.
$cors->handle();

$response = $cors->getResponse();
```

Both `$requestType` and `$responseType` are of any type that is individually set to support, but if used in a framework, these two values are usually the same. Because you can set it up individually, you can deliver different values depending on your needs to achieve your goals.

### Configure

The config example:

```php
$config = [
    'allow-credentiails' => false, // set "Access-Control-Allow-Credentials" 👉 string "false" or "true".
    'allow-headers'      => ['*'], // ex: Content-Type, Accept, X-Requested-With
    'expose-headers'     => [],
    'origins'            => ['*'], // ex: http://localhost
    'methods'            => ['*'], // ex: GET, POST, PUT, PATCH, DELETE
    'max-age'            => 0,
];
```

### Array

```php
use Medz\Cors\Cors;

$cors = new Cors($config);
$cors->setRequest('array', $request);
$cors->setResponse('array', $response);
$cors->handle();

$responseHeadersArray = $cors->getResponse();
```

### PSR-7

```php
use Medz\Cors\Cors;

$cors = new Cors($config);
$cors->setRequest('psr-7', $request);
$cors->setResponse('psr-7', $response);

$response = $cors->getResponse();
```

### Other

Because of the interface features provided by this package, you can implement it in a small amount of code in any other framework.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://github.com/medz/cors/blob/master/LICENSE).
