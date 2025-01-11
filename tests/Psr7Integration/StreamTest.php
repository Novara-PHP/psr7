<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\StreamIntegrationTest;
use Novara\Psr7\Factory\StreamFactory;
use Novara\Psr7\Stream\ConstantStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\StreamInterface;

#[CoversClass(ConstantStream::class)]
#[UsesClass(StreamFactory::class)]
final class StreamTest extends StreamIntegrationTest
{
    protected $skippedTests = [
        'testWrite' => 'ConstantStream is constant.',
        'testClose' => 'ConstantStream is constant.',
        'testDetach' => 'ConstantStream is constant.',
        'testGetSize' => 'ConstantStream is constant.',
        'testTell' => 'ConstantStream is constant.',
        'testEof' => 'ConstantStream is constant.',
        'testIsSeekable' => 'ConstantStream is constant.',
        'testIsWritable' => 'ConstantStream is constant.',
        'testIsReadable' => 'ConstantStream is constant.',
        'testSeek' => 'ConstantStream is constant.',
        'testRead' => 'ConstantStream is constant.',
        'testGetContentsError' => 'ConstantStream is constant.',
        'testRewind' => 'ConstantStream is constant.',
        'testIsNotReadable' => 'ConstantStream is constant.',
        'testIsNotSeekable' => 'Causes warnings.',
        'testIsNotWritable' => 'Causes warnings.',
        'testRewindNotSeekable' => 'Causes warnings.',
    ];

    public function createStream($data): StreamInterface
    {
        return (new StreamFactory())->createStream($data);
    }

    public function testGetContents()
    {
        $resource = fopen('php://memory', 'rw');
        fwrite($resource, 'abcdef');
        fseek($resource, 3);
        $stream = $this->createStream($resource);

        self::assertSame('abcdef', $stream->getContents());
    }
}
