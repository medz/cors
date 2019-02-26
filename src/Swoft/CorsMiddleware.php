<?php

namespace Medz\Cors\Swoft;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Core\RequestContext;
use Swoft\Http\Message\Middleware\MiddlewareInterface;

/**
 * @\Swoft\Bean\Annotation\Bean('cors.middleware')
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @\Swoft\Bean\Annotation\Inject('cors.main')
     */
    protected $cors;

    /**
     * The middleware handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->cors->onlyPreflight()) {
            if ($this->cors->isPreflightRequest('psr-7', $request)) {
                if (!(($response = $this->getResponse()) instanceof ResponseInterface)) {
                    $response = $handler->handle($request);
                }

                return $this
                    ->handle($request, $response)
                    ->withStatus(204);
            }

            return $handler->handle($request);
        }

        return $this->handle($request, $handler->handle($request));
    }

    /**
     * The cors handle.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function handle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cors = $this->cors->getCors();
        $cors->setRequest('psr-7', $request);
        $cors->setResponse('psr-7', $response);
        $cors->handle();

        return $cors->getResponse();
    }

    /**
     * Using Swoft request context get response instance.
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    protected function getResponse()
    {
        return RequestContext::getResponse();
    }
}
