<?php

declare(strict_types=1);

namespace Medz\Cors;

class Request implements RequestInterface
{
    /**
     * request native.
     *
     * @var any.
     */
    protected $request;

    /**
     * The request type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create the request.
     *
     * @param string $type
     * @param any    $request
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(string $type, $request = null)
    {
        $this->type = strtolower($type);
        $this->request = $request;

        // $request not is framework interface, using global veriable $_REQUEST
        if ($this->type === 'array' || !$request || is_array($request)) {
            $this->request = array_merge($_REQUEST, (array) $request);
            $this->type = 'array';
        }
    }

    /**
     * Get header line.
     *
     * @param string $name
     * @param string $default
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getHeader(string $name, string $default = ''): string
    {
        switch ($this->type) {
            // Check the request is PSR-7 http message.
            case 'psr-7':
                return $this->request->getHeaderLine($name) ?: $default;

            // Check the is Laravel or Symfony.
            case 'laravel':
            case 'symfony':
                return $this->request->headers->get($name, $default);

            case 'thinkphp':
                return $this->request->header($name, $default);
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
     *
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
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAccessControlRequestHeaders(): string
    {
        $name = 'Access-Control-Request-Headers';
        $default = '';

        return $this->getHeader($name, $default);
    }

    /**
     * Get "Origin" header line string.
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getOrigin(): string
    {
        $name = 'Origin';
        $default = '';

        return $this->getHeader($name, $default);
    }
}
