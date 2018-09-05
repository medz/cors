# 在 ThinkPHP 中使用

> 之所以本文档为中文，是因为 ThinkPHP 框架定位问题，满足中国大陆用户。

一个比 ThinkPHP 5 官方实现**更好**的跨域中间件！！！

## 支持

因为 `medz/cors` 组件限制了 PHP 版本，所以你只能在 ThinkPHP 5 中使用。

## 配置

在 ThinkPHP 项目的 `config` 目录下新建一个 `cors.php` 文件，其中配置内容请参考👉https://github.com/medz/cors#configure 
```php
<?php

return [
    'allow-credentials' => false, // set "Access-Control-Allow-Credentials" 👉 string "false" or "true".
    'allow-headers'      => ['*'], // ex: Content-Type, Accept, X-Requested-With
    'expose-headers'     => [],
    'origins'            => ['*'], // ex: http://localhost
    'methods'            => ['*'], // ex: GET, POST, PUT, PATCH, DELETE
    'max-age'            => 0,
];

```

然后按照说明配置成你需要的配置即可！

## 使用

使用 Composer 安装后，你已经不需要进行代码设置了，你唯一要做的就是按照「配置」进行配置使用即可！！！

## 组件是如何支持 ThinkPHP 的

注入代码参考自 ThinkPHP 官方的拓展包，首先我们建立 `src/ThinkPHP/injection.php` 文件，然后在 `composer.json` 的 `autoload` 中使用 `files` 方式对该文件进行加载，代码如下：

```php
<?php

declare(strict_types=1);

namespace Medz\Cors\ThinkPHP;

if (!class_exists(\think\Container::class)) {
    return;
}

call_user_func(function (\think\Container $container) {
    $injecter = $container->make(Injecter::class);
    $injecter->register();
}, \think\Container::getInstance());
```

为了不影响其他框架的使用，优先判断 ThinkPHP 5 的容器类是否存在，如果存在，执行 `call_user_func`，使用闭包进行包装，然后使用容器蛋里进行 `Injector` 实例。

### 使用的 ThinkPHP 5 钩子

在 ThinkPHP 5 中使用 `app_init` 钩子，作用是为了在框架读取全局配置后进行 `CORS` 服务的单例处理注入到容器中。

然后使用 `response_send` 在 Response 发送前进行拦截，并对其 CORS 信息的处理！

### ThinkPHP 5 的坑

因为框架独占了 OPTIONS 请求的绑定以及跨域信息的判断处理，目前只能通过 `response_send` 钩子拦截 Response 实现 CORS 处理！

> ThinkPHP 5.1 的中间件运行机制有问题，且无全局中间件机制。所以无法通过中间件进行预处理！！！

## Example

1. 创建 ThinkPHP 框架项目
    ```bash
        composer create-project topthink/think tp5
        cd tp5
    ```
2. 安装 CORS 插件
    ```bash
        composer require medz/cors
    ```
3. 编写配置文件
    ```
        echo "<?php \
        return [\
            'allow-credentials'  => false, \
            'allow-headers'      => ['*'], \
            'expose-headers'     => [], \
            'origins'            => ['*'], \
            'methods'            => ['*'], \
            'max-age'            => 0, \
        ];\
        " > ./config/cors.php
    ```
4. 现在，按照 https://github.com/medz/cors#configure 的配置说明去配置 `config/cors.php` 配置即可！
