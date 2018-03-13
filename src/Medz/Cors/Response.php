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

        return $this;
    }

    public function setAccessControlAllowCredentials(bool $credentiale)
    {
        $this->setHeader('Access-Control-Allow-Credentials', $credentiale ? 'true' : 'false');

        return $this;
    }

    public function setAccessControlAllowMethods($methods)
    {
        if (is_array($methods)) {
            $methods = implode(', ', $methods);
        }

        $this->setHeader('Access-Control-Allow-Methods', (string) $methods);

        return $this;
    }

    public function setAccessControlAllowHeaders($headers)
    {
        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        $this->setHeader('Access-Control-Allow-Headers', (string) $headers)

        return $this;
    }

    public function setAccessControlAllowOrigin(string $origin)
    {
        $this->setHeader('Access-Control-Allow-Origin', $origin);

        return $this;
    }

    public function setAccessControlExposeHeaders($headers)
    {
        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        $this->setHeader('Access-Control-Expose-Headers', (string) $headers);

        return $this;
    }

    public function setAccessControlMaxAge(int $maxAge = 0)
    {
        if ($maxAge) {
            $this->setHeader('Access-Control-Max-Age', $maxAge);
        }

        return $this;
    }

    public function returnNative()
    {
        return $this->response;
    }
}
