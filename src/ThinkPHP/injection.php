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
