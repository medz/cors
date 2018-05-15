<?php

declare(strict_types=1);

namespace Medz\Cors\Slim;

use Medz\Cors\Cors as CorsHandle;
use Medz\Cors\CorsInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Cors
{
    /**
     * The CORS service.
     *
     * @var \Medz\Cors\CorsInterface
     */
    protected $cors;

    /**
     * Append CORS to all response.
     *
     * @var bool
     */
    protected $append = false;

    /**
     * Create the Slim framework middleware instance.
     *
     * @param \Medz\Cors\CorsInterface|array|null $payload
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($payload = null, bool $append = false)
    {
        $this->append = $append;
        // Default CORS settings.
        $settings = static::parseSettings([]);

        // The payload is \Medz\Cors\CorsInterface instance.
        if ($payload instanceof CorsInterface) {
            $this->cors = $payload;

            return;

        // The payload is config.
        } elseif (is_array($payload)) {
            $settings = static::parseSettings($payload);
        }

        $this->cors = new CorsHandle($settings);
    }

    /**
     * The CORS middleware handle.
     *
     * @param \Psr\Http\Message\RequestInterface  $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param callable                            $next
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        // Defined the CORS type.
        $type = 'psr-7';

        // Set request.
        $this->cors->setRequest($type, $request);

        // The request is preflight.
        if ($this->cors->isPreflightRequest($type, $request)) {
            // Set response.
            $this->cors->setResponse($type, $response);
            $this->cors->handle();

            return $this
                ->cors
                ->getResponse()
                ->withStatus(204);
        }

        return $this->appendAllResponse($next($request, $response));
    }

    /**
     * Append all response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function appendAllResponse(ResponseInterface $response): ResponseInterface
    {
        if ($this->append) {
            $this->cors->setResponse('psr-7', $response);
            $this->cors->handle();

            return $this->cors->getResponse();
        }

        return $response;
    }

    /**
     * Parse settings.
     *
     * @param array $settings
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public static function parseSettings(array $settings = []): array
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
