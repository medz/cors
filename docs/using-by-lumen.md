# Using by Lummen

> 暂无具体的 Lummen 版本要求测试，开发于 5.6 环境。

## Configure

你应该在你的 Lumen 项目目录建立一个 `config` 目录，并按照👉[参考配置](https://github.com/medz/cors#configure)创建一个名为 `cors.php` 文件。

## Using

On Laravel Lumen, register the `ServiceProvider` in `bootstrap/app.php`:

```php
$app->configure('cors'); // 如果想 `config/cors.php` 的配置生效，请务必添加这行代码。
$app->register(Medz\Cors\Lumen\ServiceProvider::class);
```

> Lumen 的定义为轻量级 API 框架，所以，摒弃了 Laravel 支持中的「组模式」和「匹配模式」，转而正常请求不会赋予 CORS 信息，只有 OPTIONS 预检请求才会设置 CORS 信息。 
