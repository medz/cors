<?php

namespace Medz\Cors\Swoft;

use Medz\Cors\Cors;
use Medz\Cors\CorsInterface;
use Swoft\App;

/**
 * @\Swoft\Bean\Annotation\Bean('cors.main')
 */
class CorsBean
{
    /**
     * The cors instance.
     */
    protected static $cors;

    /**
     * Set a cors instance.
     *
     * @param \Medz\Cors\CorsInterface $cors
     */
    public function setCors(CorsInterface $cors)
    {
        static::$cors = $cors;

        return $this;
    }

    /**
     * Get the cors instance.
     *
     * @return \Medz\Cors\CorsInterface
     */
    public function getCors(): CorsInterface
    {
        if (static::$cors instanceof CorsInterface) {
            return static::$cors;
        }

        return $this->setCors($this->createCors())->getCors();
    }

    /**
     * Has the http request is only preflight.
     *
     * @return bool
     */
    public function onlyPreflight(): bool
    {
        return boolval(
            $this->getConfigure('onlyPreflight', false)
        );
    }

    /**
     * Get the cors configures.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function getConfigure(string $key = '', $value = null)
    {
        return App::getAppProperties()->get($key ? sprintf('cors.%s', $key) : 'cors', $value);
    }

    /**
     * Resolve the cors.
     *
     * @return \Medz\Cors\CorsInterface
     */
    protected function createCors(): CorsInterface
    {
        return new Cors($this->parseSettings(
            $this->getConfigure('settings', [])
        ));
    }

    /**
     * Parse settings.
     *
     * @param array $settings
     *
     * @return array
     */
    protected function parseSettings(array $settings): array
    {
        $defaultSettings = [
            'allow-credentials'  => false,
            'allow-headers'      => ['*'],
            'expose-headers'     => [],
            'origins'            => ['*'],
            'methods'            => ['*'],
            'max-age'            => 0,
        ];

        return array_merge($defaultSettings, $settings);
    }

    /**
     * Call "Medz\Cors\CorsInterface" methods.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return static::getCors()->{$method}(...$parameters);
    }
}
