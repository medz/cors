<?php

namespace Medz\Cors\PSR;

use Medz\Cors\Cors;
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
     * Only using preflight request.
     *
     * @var bool
     */
    protected $onlyPreflight;

    /**
     * Create the middleware.
     *
     * @param \Medz\Cors\CorsInterface|array $payload
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($payload = null, $onlyPreflight = false)
    {
        $this->cors = static::resoveCors($payload);
        $this->onlyPreflight = $onlyPreflight;
    }

    /**
     * The middleware process.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($this->onlyPreflight) {
            if ($this->cors->isPreflightRequest('psr-7', $request)) {
                return $this
                    ->handle($request, $response)
                    ->withStatus(204);
            }

            return $response;
        }

        return $this->handle($request, $response);
    }

    /**
     * Process cors headers.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $type = 'psr-7';
        $this->cors->setRequest($type, $request);
        $this->cors->setResponse($type, $response);
        $this->cors->handle();

        return $this->cors->getResponse();
    }

    /**
     * Resolve cors instance.
     *
     * @param \Medz\Cors\CorsInterface|array $payload
     *
     * @return \Medz\Cors\CorsInterface
     */
    protected function resoveCors($payload): CorsInterface
    {
        if ($payload instanceof CorsInterface) {
            return $payload;
        } elseif (!is_array($payload)) {
            $payload = [];
        }

        return new Cors(static::parseSettings($payload));
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
}
