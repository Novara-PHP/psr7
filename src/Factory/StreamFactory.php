<?php

namespace Novara\Psr7\Factory;

use Novara\DynamicReadonlyClasses\DRCFactory;
use Novara\Psr7\Stream\ConstantStream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        return DRCFactory::create(
            ConstantStream::class,
            [
                'CONTENTS' => func_get_arg(0),
            ],
        );
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return DRCFactory::create(
            ConstantStream::class,
            [
                'CONTENTS' => file_get_contents(func_get_arg(0)),
            ],
        );
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        return DRCFactory::create(
            ConstantStream::class,
            [
                'CONTENTS' => stream_get_contents(func_get_arg(0)),
            ],
        );
    }
}
