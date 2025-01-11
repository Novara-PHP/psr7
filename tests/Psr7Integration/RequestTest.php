<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\RequestIntegrationTest;
use Novara\Psr7\Factory\RequestFactory;
use Novara\Psr7\Factory\StreamFactory;
use Novara\Psr7\Factory\UriFactory;
use Novara\Psr7\Request;
use Novara\Psr7\Stream\ConstantStream;
use Novara\Psr7\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\RequestInterface;

define('STREAM_FACTORY', StreamFactory::class);
define('URI_FACTORY', UriFactory::class);

#[CoversClass(Request::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(Uri::class)]
#[UsesClass(UriFactory::class)]
#[UsesClass(StreamFactory::class)]
#[UsesClass(ConstantStream::class)]
final class RequestTest extends RequestIntegrationTest
{
    protected $skippedTests = [
        'testWithHeaderInvalidArguments' => 'no',
        'testWithAddedHeaderInvalidArguments' => 'no',
        'testMethodWithInvalidArguments' => 'nope',
    ];

    public function createSubject(): RequestInterface
    {
        return (new RequestFactory())->createRequest('GET', new class () extends Uri {});
    }
    public function testBody()
    {
        if (isset($this->skippedTests[__FUNCTION__])) {
            $this->markTestSkipped($this->skippedTests[__FUNCTION__]);
        }

        $initialMessage = $this->getMessage();
        $original = clone $initialMessage;
        $stream = $this->buildStream('foo');
        $message = $initialMessage->withBody($stream);
        $this->assertNotSameObject($initialMessage, $message);
        $this->assertEquals($initialMessage, $original, 'Message object MUST not be mutated');

        // Original just checked Equals, which fails because of copy on write
        $this->assertEquals((string)$stream, (string)$message->getBody());
    }

    public function testWithoutHeader()
    {
        if (isset($this->skippedTests[__FUNCTION__])) {
            $this->markTestSkipped($this->skippedTests[__FUNCTION__]);
        }

        $message = $this->getMessage()->withAddedHeader('content-type', 'text/html');
        $message = $message->withAddedHeader('Age', '0');
        $message = $message->withAddedHeader('X-Foo', 'bar');

        $headers = $message->getHeaders();
        $headerCount = count($headers);
        // Original tests used isset() which breaks with strtolower and shouldn't be done this way
        $this->assertTrue($message->hasHeader('Age'));

        // Remove a header
        $message = $message->withoutHeader('age');
        $headers = $message->getHeaders();
        $this->assertCount($headerCount - 1, $headers);
        $this->assertFalse($message->hasHeader('Age'));
    }
}
