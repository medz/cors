<?php

declare(strict_types=1);

namespace Medz\Cors\Tests;

use Medz\Cors\Cors;
use Medz\Cors\CorsInterface;

class CorsTest extends TestCase
{
    public function testInterfaceImplements()
    {
        $cors = $this->createMock(Cors::class);

        $this->assertTrue($cors instanceof CorsInterface);
    }
}
