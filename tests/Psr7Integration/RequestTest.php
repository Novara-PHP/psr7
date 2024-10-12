<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\RequestIntegrationTest;
use Novara\Psr7\Factory\StreamFactory;

define('STREAM_FACTORY', StreamFactory::class);

final class RequestTest extends RequestIntegrationTest
{
    public function createSubject()
    {
        // TODO: Implement createSubject() method.
    }
}
