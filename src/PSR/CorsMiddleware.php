<?php

namespace Medz\Cors\PSR;

use Medz\Cors\CorsInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * The CORS instance.
     *
     * @var \Medz\Cors\CorsInterface
     */
    protected $cors;

    /**
     * Create the middleware.
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
     * The middleware process.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handle
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handle): ResponseInterface
    {
        $type = 'psr-7';
        if ($this->cors->isPreflightRequest($type, $request)) {
            $this->cors->setRequest($type, $request);
            $this->cors->setResponse('array', []);
            $this->cors->handle();

            ob_end_clean();
            ob_start();
            $headers = $this->cors->getResponse();
            $statusCode = 204;
            foreach ($headers as $headerKey => $headerValue) {
                header($headerKey.': '.$headerValue, false, $statusCode);
            }
            header('HTTP/1.1 204 No Content');
            ob_end_flush();
            exit;
        }

        return $handle->handle($request);
    }
}
