<?php

declare(strict_types=1);

namespace Medz\Cors;

use Psr\Http\Message\RequestInterface as Psr7RequestInterface;

class Request
{
    /**
     * request native.
     *
     * @var any.
     */
    protected $request;

    /**
     * Create the request.
     *
     * @param any $request
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct($request = null)
    {
        $this->request = $request;

        // $request not is framework interface, using global veriable $_REQUEST
        if (!$request instanceof Psr7RequestInterface) {
            $this->request = $_REQUEST;
        }
    }

    /**
     * Get header line.
     *
     * @param string $name
     * @param string $default
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getHeader(string $name, string $default = ''): string
    {
        // Check the request is PSR-7 http message.
        if ($this->request instanceof Psr7RequestInterface) {
            return $this->request->getHeaderLine($name) ?: $default;
        }

        // Not using framewoerk ?
        // Read global variable $_REQUEST.
        foreach ($this->request as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return (string) $value;
            }
        }

        // return default line string.
        return $default;
    }

    /**
     * Get "Access-Control-Request-Method" header line string.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAccessControlRequestMethod(): string
    {
        $name = 'Access-Control-Request-Method';
        $default = '';

        return $this->getHeader($name, $default);
    }

    /**
     * Get "Access-Control-Request-Headers" header line string.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAccessControlRequestHeaders(): string
    {
        $name = 'Access-Control-Request-Headers';
        $default = '';

        return $this->getHeader($name, $default);
    }
}
