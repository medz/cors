# CORS

[![StyleCI](https://styleci.io/repos/125001413/shield?branch=master)](https://styleci.io/repos/125001413)
[![Build Status](https://travis-ci.org/medz/cors.svg?branch=master)](https://travis-ci.org/medz/cors)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fmedz%2Fcors.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fmedz%2Fcors?ref=badge_shield)

PHP CORS (Cross-origin resource sharing) middleware.

## Support

- [x] [Array, Coding in Native PHP](#array)
- [x] [Using PSR-7](#psr-7)
- [x] [PSR-15 Support](https://github.com/medz/cors/blob/master/docs/psr-15-support.md)
- [ ] Symfony Support
- [x] [Laravel Support](https://github.com/medz/cors/blob/master/docs/using-by-laravel.md)
- [x] [Lumen Support](https://github.com/medz/cors/blob/master/docs/using-by-lumen.md)
- [ ] Yii2 Support
- [x] [Slim Framework](https://github.com/medz/cors/blob/master/docs/using-by-slim.md)
- [x] [ThinkPHP Support](https://github.com/medz/cors/blob/master/docs/using-by-thinkphp.md)

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
    'allow-credentials' => false, // set "Access-Control-Allow-Credentials" 👉 string "false" or "true".
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


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fmedz%2Fcors.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fmedz%2Fcors?ref=badge_large)