<?php

declare(strict_types=1);

namespace Medz\Cors;

use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * The any response.
     *
     * @var any
     */
    protected $response;

    /**
     * The response type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create the response.
     *
     * @param string $type
     * @param any $response
     *
     * @return void
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __constrcut(string $type, $response = null)
    {
        $this->type = strtolower($type);
        $this->response = $response;

        if ($this->type === 'array' || !$response || is_array($response)) {
            $this->response = is_array($response) ? $response : [];
            $this->type = 'array';
        }
    }

    /**
     * Setting response headers.
     *
     * @param string $name
     * @param string $value
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setHeader(string $name, string $value)
    {
        switch ($this->type) {
            case 'psr-7':
                $this->response = $this->response->withHeader($name, $value);
                break;

            case 'laravel':
            case 'symfony':
                $this->response->headers->set($name, $value);
                break;
            
            default:
                $this->response[$name] = $value;
                break;
        }

        return $this;
    }

    /**
     * Set "Access-Control-Alow-Credentials" header line.
     *
     * @param bool $credentiale
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlAllowCredentials(bool $credentiale)
    {
        $this->setHeader('Access-Control-Allow-Credentials', $credentiale ? 'true' : 'false');

        return $this;
    }

    /**
     * Set "Access-Control-Allow-Methods" header line.
     *
     * @param string|string[] $methods
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlAllowMethods($methods)
    {
        if (is_array($methods)) {
            $methods = implode(', ', $methods);
        }

        $this->setHeader('Access-Control-Allow-Methods', (string) $methods);

        return $this;
    }

    /**
     * Set "Access-Control-Allow-Headers" header line.
     *
     * @param string|string[] $headers
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlAllowHeaders($headers)
    {
        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        $this->setHeader('Access-Control-Allow-Headers', (string) $headers);

        return $this;
    }

    /**
     * Set "Access-Control-Allow-Origin" header line.
     *
     * @param string $origin
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlAllowOrigin(string $origin)
    {
        $this->setHeader('Access-Control-Allow-Origin', $origin);

        return $this;
    }

    /**
     * Set "Access-Control-Expose-Headers" header line.
     *
     * @param string|string[] $headers
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlExposeHeaders($headers)
    {
        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        $this->setHeader('Access-Control-Expose-Headers', (string) $headers);

        return $this;
    }

    /**
     * Set "Access-Control-Max-Age" header line.
     *
     * @param int $maxAge
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setAccessControlMaxAge(int $maxAge = 0)
    {
        if ($maxAge) {
            $this->setHeader('Access-Control-Max-Age', $maxAge);
        }

        return $this;
    }

    /**
     * Return native.
     *
     * @return any
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function returnNative()
    {
        return $this->response;
    }
}