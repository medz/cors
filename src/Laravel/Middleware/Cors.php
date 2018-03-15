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

        // The request not is CORS request,
        // break the handle.
        if (!$this->cors->isCorsRequest($type, $request)) {
            return $next($request);

        // Check the request is option,
        // is "option" request return CORS headers response.
        } elseif ($this->isPreflightRequest($type, $request)) {
            $this->cors->setRequest($type, $request);
            $this->cors->setResponse('laravel', $response = new Response());
            $this->cors->handle();

            return $response;

        // Add the headers on the Request Handled event as fallback in case of exceptions
        } elseif (class_exists(RequestHandled::class)) {
            $this->events->listen(RequestHandled::class, function (RequestHandled $event) use ($type) {
                $event->response = $this->corsHandle($type, $event->request, $event->response);
            });
        }

        return $this->corsHandle($type, $request, $next($request));
    }

    /**
     * CORS serve singleton handle.
     *
     * @param string $type
     * @param any    $request
     * @param any    $response
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function corsHandle(string $type, $request, $response)
    {
        if (!$this->cors->hasAdded()) {
            $this->cors->setRequest($type, $request);
            $this->cors->setResponse($type, $response);
            $this->cors->handle();
        }

        return $this->cors->getResponse();
    }
}
