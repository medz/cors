# Using by Slim framework

`medz/cors` package supported [Slim framework 3](https://github.com/slimphp/Slim).

## Configure

See 👉[Configure example](https://github.com/medz/cors#configure).

## Using

Slim frameowk supported class name is `Medz\Cors\Slim\Cors`, Simple use:

```php
$app = new Slim\App();
$app->add(new Medz\Cors\Slim\Cors);
```

> 💡Slim framework middleware using see https://www.slimframework.com/docs/v3/concepts/middleware.html

### Using Settings

```php
$settings = [
    // The configure see https://github.com/medz/cors#configure
];
$app = new Slim\App();
$app->add(new Medz\Cors\Slim\Cors($settings));
```

### Using custom CORS instance

```php
$cors = // Todo, The instance is \Medz\Cors\CorsInterface::class
$app = new Slim\App();
$app->add(new Medz\Cors\Slim\Cors($cors));
```

## CORS middleware `__construct`

- payload: This is Medz\Cors\CorsInterface or array or null
- append: The type is boolean, If using `true`, All request set CORS to response hraders.
