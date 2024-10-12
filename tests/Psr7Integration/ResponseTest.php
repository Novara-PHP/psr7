<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\ResponseIntegrationTest;
use Novara\Psr7\Factory\ResponseFactory;
use Novara\Psr7\Factory\StreamFactory;
use Novara\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\ResponseInterface;

define('STREAM_FACTORY', StreamFactory::class);

#[CoversClass(Response::class)]
#[UsesClass(ResponseFactory::class)]
final class ResponseTest extends ResponseIntegrationTest
{
    public function createSubject(): ResponseInterface
    {
        return (new ResponseFactory())->createResponse();
    }
}
