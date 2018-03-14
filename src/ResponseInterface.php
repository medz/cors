<?php

declare(strict_types=1);

namespace Medz\Cors;

interface ResponseInterface
{
    /**
     * Setting response headers.
     *
     * @param string $name
     * @param string $value
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setHeader(string $name, string $value);
}
