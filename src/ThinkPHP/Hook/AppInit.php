<?php

declare(strict_types=1);

namespace Medz\Cors\ThinkPHP\Hook;

use Medz\Cors\Cors;
use Medz\Cors\CorsInterface;
use think\Config;
use think\Container;
use think\Hook as ThinkHookManager;

class AppInit
{
    protected $hook;
    protected $config;

    public function __construct(Config $config, ThinkHookManager $hook)
    {
        $this->config = $config;
    }

    public function handle()
    {
        $this->resolveSettings();
        $cors = new Cors($this->config->pull('cors'));
        Container::getInstance()->bind(CorsInterface::class, Cors::class);
        Container::getInstance()->instance(CorsInterface::class, $cors);
    }

    /**
     * Parse settings.
     *
     * @param array $settings
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function resolveSettings(array $settings = [])
    {
        $defaultSettings = [
            'allow-credentiails' => false,
            'allow-headers'      => ['*'],
            'expose-headers'     => [],
            'origins'            => ['*'],
            'methods'            => ['*'],
            'max-age'            => 0,
        ];

        $this->config->set([
            'cors' => array_merge($defaultSettings, $this->config->pull('cors')),
        ]);
    }
}
