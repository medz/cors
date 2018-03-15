<?php

declare(strict_types=1);

namespace Medz\Cors\Laravel\Middleware;

use Closure;

class ShouldGroup
{
    /**
     * The should group handle.
     *
     * @param mixed   $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
