<?php

declare(strict_types=1);

namespace Medz\Cors;

class Cors implements CorsInterface
{
    /**
     * Seted request.
     *
     * @var any
     */
    protected $request;

    /**
     * Seted response.
     *
     * @var any
     */
    protected $response;

    /**
     * Allowed request origins.
     *
     * @var array
     */
    protected $origins = [];

    /**
     * Allowed request methods.
     *
     * @var array
     */
    protected $methods = [];

    /**
     * Allowed request credentials.
     *
     * @var bool
     */
    protected $allowedCredentials = false;

    /**
     * Allowed request headers.
     *
     * @var array
     */
    protected $allowedHeaders = [];

    /**
     * Expose request headers.
     *
     * @var array
     */
    protected $exposeHeaders = [];

    /**
     * Request max-age.
     *
     * @var int
     */
    protected $maxAge = 0;

    /**
     * The CORS inited status.
     *
     * @var bool[]
     */
    protected $initialized = [
        'request'  => false,
        'response' => false,
    ];

    /**
     * Create the CORS Handle.
     *
     * @param array $config
     * @param any   $request
     * @param any   $response
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(array $config)
    {
        $this->allowedCredentials = (bool) ($config['allow-credentials'] ?? false);
        $this->allowedHeaders = (array) ($config['allow-headers'] ?? []);
        $this->exposeHeaders = (array) ($config['expose-headers'] ?? []);
        $this->origins = (array) ($config['origins'] ?? []);
        $this->methods = (array) ($config['methods'] ?? []);
        $this->maxAge = (int) ($config['max-age'] ?? 0);
    }

    /**
     * Run the CORS handle.
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        if (in_array(false, array_values($this->initialized))) {
            throw new CorsInitException('Not set "request" or "response"');
        }

        $this->response->setAccessControlAllowCredentials($this->getCredentials());
        $this->response->setAccessControlAllowOrigin($this->getOrigin());
        $this->response->setAccessControlAllowMethods($this->getMethods());
        $this->response->setAccessControlAllowHeaders($this->getAllowheaders());
        $this->response->setAccessControlExposeHeaders($this->getExposeHeaders());
        $this->response->setAccessControlMaxAge($this->getMaxAge());
        $this->response->setAddedStatus(true);
    }

    /**
     * Get allowed credentials.
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCredentials(): bool
    {
        return $this->allowedCredentials;
    }

    /**
     * Get allowed origin.
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getOrigin(): string
    {
        // The origin is seted "*"?
        if (in_array('*', $this->origins)) {
            return '*';
        }

        // Get request heaer origin line.
        // If the request origin in config
        // to be origins return the request origin value.
        $requestOrigin = $this->request->getOrigin();
        if (in_array($requestOrigin, $this->origins)) {
            return $requestOrigin;
        }

        // Return empty.
        return '';
    }

    /**
     * Get allowed methods.
     *
     * @return string|array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getMethods()
    {
        $requestMethod = $this->request->getAccessControlRequestMethod();
        if (in_array('*', $this->methods) && $requestMethod) {
            return $requestMethod;
        }

        return $this->methods;
    }

    /**
     * Get allow-headers.
     *
     * @return string|array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAllowheaders()
    {
        $requestHeaders = $this->request->getAccessControlRequestHeaders();
        if (in_array('*', $this->allowedHeaders) && $requestHeaders) {
            return $requestHeaders;
        }

        return $this->allowedHeaders;
    }

    /**
     * Get expose-headers.
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getExposeHeaders(): array
    {
        return $this->exposeHeaders;
    }

    /**
     * Get cache max-age line value.
     *
     * @return int
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getMaxAge(): int
    {
        return $this->maxAge;
    }

    /**
     * Set a request.
     *
     * @param string $type
     * @param any    $request set a request, if native PHP set the "$request" null.
     *                        the "$request" MUST be implemented.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setRequest(string $type, $request)
    {
        $this->request = new Request($type, $request);
        $this->initialized['request'] = true;

        return $this;
    }

    /**
     * Set a response.
     *
     * @param string $type
     * @param any    $response the "$response" is framework interface or array.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setResponse(string $type, $response)
    {
        $this->response = new Response($type, $response);
        $this->initialized['response'] = true;

        return $this;
    }

    /**
     * Has added.
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function hasAdded(): bool
    {
        if (!$this->response instanceof ResponseInterface) {
            return false;
        }

        return $this->response->hasAdded();
    }

    /**
     * Get set CORS response.
     *
     * @return any The return to the solution depends on the set of response.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getResponse()
    {
        return $this->response->returnNative();
    }

    /**
     * The request is CORE request.
     *
     * @param string $type
     * @param any    $request
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function isCorsRequest(string $type, $request): bool
    {
        $origin = (new Request($type, $request))->getOrigin();

        switch (strtolower($type)) {
            case 'psr-7':
                $scheme = $request->getUri()->getScheme();
                $httpHost = $scheme.'://'.$request->getUri()->getHost();
                $port = $request->getUri()->getPort();

                if (('http' == $scheme && 80 == $port) || ('https' == $scheme && 443 == $port)) {
                    $isSameHost = $origin === $httpHost;
                    break;
                }

                $isSameHost = $origin === ($httpHost.':'.$port);
                break;

            case 'laravel':
            case 'symfony':
                $isSameHost = $origin === $request->getSchemeAndHttpHost();
                break;

            default:
                $isSameHost = false;
                break;
        }

        return $origin && !$isSameHost;
    }

    /**
     * The request is perflight request.
     *
     * @param string $type
     * @param any    $request
     *
     * @return bool
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function isPreflightRequest(string $type, $request): bool
    {
        $requestManager = new Request($type, $request);
        switch (strtolower($type)) {
            case 'psr-7':
            case 'laravel':
            case 'symfony':
                $method = $request->getMethod();
                break;

            default:
                $method = strtolower($_SERVER['REQUEST_METHOD']);
                if ($method === 'post') {
                    if ($_method = $requestManager->getHeader('X-HTTP-METHOD-OVERRIDE')) {
                        $method = $_method;
                    }
                }
                break;
        }

        return $this->isCorsRequest($type, $request)
            && 'options' === strtolower($method)
            && $requestManager->getHeader('Access-Control-Request-Method');
    }
}
