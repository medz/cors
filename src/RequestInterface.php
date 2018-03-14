<?php

declare(strict_types=1);

namespace Medz\Cors;

interface RequestInterface
{
    /**
     * Get header by "$name" line.
     *
     * @param string $name
     * @param string $default
     *
     * @return string
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getHeader(string $name, string $default = ''): string;
}
