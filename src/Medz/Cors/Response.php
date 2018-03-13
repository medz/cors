<?php

declare(strict_types=1);

namespace Medz\Cors;

use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class Response
{
    protected $response;

    public function __constrcut($response = null)
    {
        $this->response = $response;

        if (! $response instanceof Psr7ResponseInterface) {
            $this->response = [];
        }
    }

    public function setHeader(string $name, string $value)
    {
        if ($this->response instanceof Psr7ResponseInterface) {
            $this->response = $this->response->withHeader($name, $value);

            return $this;
        }

        $this->response[$name] = $value;
    }

    public function returnNative()
    {
        return $this->response;
    }
}
