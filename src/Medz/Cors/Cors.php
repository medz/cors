<?php

declare(strict_types=1);

namespace Medz\Cors;

class Cors
{
    protected $request;
    protected $response;

    protected $origins = [];
    protected $methods = [];
    protected $allowedHeaders = [];
    protected $exposeHeaders = [];
    protected $maxAge = 0;

    public function __construct(array $config = [])
    {
        $this->allowedHeaders = (array) $config['allow-headers'] ?? [];
        $this->exposeHeaders = (array) $config['expose-headers'] ?? [];
        $this->origins = (array) $config['origins'] ?? [];
        $this->methods = (array) $config['methods'] ?? [];
        $this->maxAge = (int) $config['max-age'] ?? 0;
    }

    public function handle()
    {
        // todo.
    }

    protected function setRequest($request)
    {
        $this->request = new Request($request);

        return $this;
    }

    protected function setRsponse(&$response)
    {
        $this->response = new Response($response);

        return $this;
    }
}
