# 在 Swoft 中使用

允许在 Swoft Http 中进行 CORS 处理。

## 配置

在配置文件 `config/properties/app.php` 中进行如下配置：

```php
'components' => [
    'custom' => [
        'Medz\\Cors\\Swoft\\',
    ],
],
'cors' => [
    'onlyPreflight' => false, // 是否仅 OPTIONS 预检请求才进行跨域信息附加
    'settings' => [
        /// ... 参考 README 中的 PSR-7
    ],
],
```

## 全局使用

打开 `app/config/beans/base.php` 配置如下：

```php
'serverDispatcher' => [
    'middlewares' => [
        \Medz\Cors\Swoft\CorsMiddleware::class,
    ],
],
```

## 通过注解使用

通过 `@Middleware` 和 `@Middlewares`, 可以很方便的配置中间件到当前的 Controller 和 Action 内。

- 当将此注解应用于 Controller 上，则作用域为整个 Controller
- 将此注解应用于 Action 上，则作用域仅为当前的 Action

```php
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Message\Bean\Annotation\Middleware;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;
use Medz\Cors\Swoft\CorsMiddleware;

/**
 * Setting Controller middleware.
 * 
 * @Controller("middleware")
 * @Middleware(CorsMiddleware::class)
 */
class CorsOneController
{
    //
}

/**
 * Setting Action middleware.
 */
class CorsTwoController
{
    /**
     * @RequestMapping()
     * @Middleware(CorsMiddleware::class)
     */
    public function corsAction(): array
    {
        return [
            'message' => 'The action using CORS.'
        ];
    }
}
```
