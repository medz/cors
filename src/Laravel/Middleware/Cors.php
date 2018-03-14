<?php

declare(strict_types=1);

namespace Medz\Cors\Laravel\Middleware;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Medz\Cors\CorsInterface;

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
        $type = config('cors.laravel.using-http-message-type', 'laravel');

        // Add the headers on the Request Handled event as fallback in case of exceptions
        if (class_exists(RequestHandled::class)) {
            $this->events->listen(RequestHandled::class, function (RequestHandled $event) use ($type) {
                $this->cors->setRequest($type, $event->request);
                $this->cors->setResponse($type, $event->response);
                $this->cors->handle();
            });
        } elseif ($this->events->hasListeners('kernel.handled')) {
            $this->events->listen('kernel.handled', function (Request $request, Response $response) {
                $this->cors->setRequest('laravel', $request);
                $this->cors->setResponse('laravel', $response);
                $this->cors->handle();
            });
        }

        $this->cors->setRequest($type, $request);
        $this->cors->setResponse($type, $response = $next($request));
        $this->cors->handle();

        return $response;
    }
}
