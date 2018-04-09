<?php

declare(strict_types=1);

namespace Medz\Cors\Lumen;

use Medz\Cors\Cors;
use Medz\Cors\CorsInterface;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected $defer = false;

    /**
     * Bootstrap the service provider.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        $this->app->middleware(Middleware\Cors::class);
    }

    /**
     * Run the service provider register handle.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Marge the CORS config.
        $this->mergeConfigFrom(__DIR__.'/../../config/lumen.php', 'cors');

        // Register the CORS server instance alias.
        $this->app->alias(CorsInterface::class, Cors::class);

        // Register CORS servise singleton.
        $this->app->singleton(CorsInterface::class, function ($app) {
            // Get CORS config.
            $config = $app['config']->get('cors');

            return new Cors($config);
        });
    }
}
