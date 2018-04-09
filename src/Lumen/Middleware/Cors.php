<?php

declare(strict_types=1);

namespace Medz\Cors\Lumen\Middleware;

use Closure;
use Illuminate\Http\Response;
use Medz\Cors\CorsInterface;

class Cors
{
    /**
     * The CORS service.
     *
     * @var \Medz\Cors\CorsInterface
     */
    protected $cors;

    /**
     * Create the CORS middleware instance.
     *
     * @param \Medz\Cors\CorsInterface $cors
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(CorsInterface $cors)
    {
        $this->cors = $cors;
    }

    /**
     * The CORS middleware handle.
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
        if ($this->cors->isPreflightRequest($type, $request)) {
            $this->cors->setRequest($type, $request);
            $this->cors->setResponse($type, $response = new Response());
            $this->cors->handle();

            // Set Preflight response status code.
            $response->setStatusCode(204);

            return $response;
        }

        return $next($request);
    }
}
