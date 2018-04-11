<?php

declare(strict_types=1);

namespace Medz\Cors\ThinkPHP;

use think\Container;
use think\Hook as ThinkHookManager;

class Injecter
{
    protected $hook;

    public function __construct(ThinkHookManager $hook)
    {
        $this->hook = $hook;
    }

    public function register()
    {
        $this->hook->add('app_init', $this->buildHookCallable(Hook\AppInit::class));
        $this->hook->add('response_send', $this->buildHookCallable(Hook\ResponseSend::class));
    }

    protected function buildHookCallable(string $classname): callable
    {
        return function (...$params) use ($classname) {
            $hook = Container::getInstance()->make($classname);
            $hook->handle(...$params);
        };
    }
}
