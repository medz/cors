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
     * @param any $request
     * @param any $response
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(array $config, $request = null, $response = [])
    {
        $this->allowedHeaders = (array) $config['allow-headers'] ?? [];
        $this->exposeHeaders = (array) $config['expose-headers'] ?? [];
        $this->origins = (array) $config['origins'] ?? [];
        $this->methods = (array) $config['methods'] ?? [];
        $this->maxAge = (int) $config['max-age'] ?? 0;

        $this->setRequest($request);
        $this->setResponse($response);
    }

    /**
     * Run the CORS handle.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        // todo.
    }

    /**
     * Set a request.
     *
     * @param any $request set a request, if native PHP set the "$request" null.
     *                     the "$request" MUST be implemented.
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
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getResponse()
    {
        return $this->response->returnNative();
    }
}
