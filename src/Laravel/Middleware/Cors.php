<?php

declare(strict_types=1);

namespace Medz\Cors\Laravel\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Medz\Cors\CorsInterface;
use Route;

class Cors
{
    /**
     * CORS servie.
     *
     * @var \Medz\Cors\CorsInterface
     */
    protected $cors;

    /**
     * Laravel Event Dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create the handle.
     *
     * @param \Medz\Cors\CorsInterface                $cors
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(CorsInterface $cors, Dispatcher $events)
    {
        $this->cors = $cors;
        $this->events = $events;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        $type = 'laravel';

        // The request not is CORS request,
        // break the handle.
        if (!$this->hasShouldRouteGroup($request) || !$this->hasAllowRoutePerfix($request) || !$this->cors->isCorsRequest($type, $request)) {
            return $next($request);

        // Check the request is option,
        // is "option" request return CORS headers response.
        } elseif ($this->cors->isPreflightRequest($type, $request)) {
            $this->corsHandle($request, $response = new Response());

            // Set Preflight response status code.
            $response->setStatusCode(204);

            return $response;

        // Add the headers on the Request Handled event as fallback in case of exceptions
        } elseif (class_exists(RequestHandled::class)) {
            $this->events->listen(RequestHandled::class, function (RequestHandled $event) {
                $event->response = $this->corsHandle($event->request, $event->response);
            });
        }

        return $this->corsHandle($request, $next($request));
    }

    /**
     * Has should route group.
     *
     * @param mixed $request
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasShouldRouteGroup($request): bool
    {
        if (!config('cors.laravel.route-group-mode')) {
            return true;
        }

        $shouldClsssName = ShouldGroup::class;
        $shouldAlias = collect(Route::getMiddleware())->flip()->get($shouldClsssName, $shouldClsssName);
        $route = collect(Route::getRoutes()->get())->first(function ($route) use ($request) {
            return $route->matches($request, false);
        });
        if (!$route) {
            return false;
        }
        $gatherMiddleware = $route->gatherMiddleware();

        if (in_array($shouldClsssName, $gatherMiddleware) || in_array($shouldAlias, $gatherMiddleware)) {
            return true;
        }

        $middlewareGroups = collect(Route::getMiddlewareGroups())->filter(function ($group) use ($shouldClsssName, $shouldAlias) {
            return in_array($shouldAlias, $group) || in_array($shouldClsssName, $group);
        })->keys();

        return $middlewareGroups->filter(function ($value) use ($gatherMiddleware) {
            return in_array($value, $gatherMiddleware);
        })->isNotEmpty();
    }

    /**
     * Has the request allow route perfix.
     *
     * @param \Illumante\Http\Request $request
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function hasAllowRoutePerfix($request): bool
    {
        return $request->is(config('cors.laravel.allow-route-perfix'));
    }

    /**
     * CORS serve singleton handle.
     *
     * @param any $request
     * @param any $response
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function corsHandle($request, $response)
    {
        if (!$this->cors->hasAdded()) {
            $this->cors->setRequest('laravel', $request);
            $this->cors->setResponse('laravel', $response);
            $this->cors->handle();
        }

        return $this->cors->getResponse();
    }
}
