<?php

declare(strict_types=1);

namespace Medz\Cors\Laravel\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Medz\Cors\Cors;
use Medz\Cors\CorsInterface;
use Medz\Cors\Laravel\Middleware\Cors as CorsMiddleware;

class LaravelServiceProvider extends ServiceProvider
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
     * Bootstrap the CORS service provider.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // Publish the cors.php to laravel config dir.
        $this->publishes([__DIR__.'/../../../config/laravel.php' => config_path('cors.php')]);

        $kernel = $this->app->make(Kernel::class);
        // When the Cors middleware is not attached globally.
        if (!$kernel->hasMiddleware(CorsMiddleware::class)) {
            $kernel->prependMiddleware(CorsMiddleware::class);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Marge the CORS config.
        $this->mergeConfigFrom(__DIR__.'/../../../config/laravel.php', 'cors');

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
