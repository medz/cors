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
     * Create the CORS Handle.
     *
     * @param array $config
     * @param any   $request
     * @param any   $response
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(array $config, $request = null, $response = [])
    {
        $this->allowedCredentials = (bool) ($config['allwo-credentiails'] ?? false);
        $this->allowedHeaders = (array) ($config['allow-headers'] ?? []);
        $this->exposeHeaders = (array) ($config['expose-headers'] ?? []);
        $this->origins = (array) ($config['origins'] ?? []);
        $this->methods = (array) ($config['methods'] ?? []);
        $this->maxAge = (int) ($config['max-age'] ?? 0);

        $this->setRequest($request);
        $this->setResponse($response);
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
        $this->response->setAccessControlAllowCredentials($this->getCredentials());
        $this->response->setAccessControlAllowOrigin($this->getOrigin());
        $this->response->setAccessControlAllowMethods($this->getMethods());
        $this->response->setAccessControlAllowHeaders($this->getAllowheaders());
        $this->response->setAccessControlExposeHeaders($this->getExposeHeaders());
        $this->response->setAccessControlMaxAge($this->getMaxAge());
    }

    public function getCredentials(): bool
    {
        return $this->allowedCredentials;
    }

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

    public function getMethods()
    {
        $requestMethod = $this->request->getAccessControlRequestMethod();
        if (in_array('*', $this->methods) && $requestMethod) {
            return $requestMethod;
        }

        return $this->methods;
    }

    public function getAllowheaders()
    {
        $requestHeaders = $this->request->getAccessControlRequestHeaders();
        if (in_array('*', $this->allowedHeaders) && $requestHeaders) {
            return $requestHeaders;
        }

        return $this->allowedHeaders;
    }

    public function getExposeHeaders(): array
    {
        return $this->exposeHeaders;
    }

    public function getMaxAge(): int
    {
        return $this->maxAge;
    }

    /**
     * Set a request.
     *
     * @param any $request set a request, if native PHP set the "$request" null.
     *                     the "$request" MUST be implemented.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setRequest($request)
    {
        $this->request = new Request($request);

        return $this;
    }

    /**
     * Set a response.
     *
     * @param any $response the "$response" is framework interface or array.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setResponse($response)
    {
        $this->response = new Response($response);

        return $this;
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
}
