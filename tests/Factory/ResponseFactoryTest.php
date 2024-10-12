<?php

namespace Novara\Psr7\Tests\Factory;

use Novara\Psr7\Factory\ResponseFactory;
use Novara\Psr7\Factory\StreamFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ResponseFactory::class)]
#[UsesClass(StreamFactory::class)]
final class ResponseFactoryTest extends TestCase
{
    public function test(): void
    {
        $response = (new ResponseFactory())->createResponse();
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('OK', $response->getReasonPhrase());

        $response = $response->withBody(
            (new StreamFactory())->createStream(':)'),
        );
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('OK', $response->getReasonPhrase());
        self::assertSame(':)', $response->getBody()->getContents());
    }
}
