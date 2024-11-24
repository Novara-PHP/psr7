<?php

declare(strict_types=1);

namespace Novara\Psr7\Factory;

use Novara\DynamicReadonlyClasses\DRCFactory;
use Novara\Psr7\Stream\ConstantStream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    /**
     * @param string|resource $content
     */
    public function createStream(mixed $content = ''): StreamInterface
    {
        if (is_resource(func_get_arg(0))) {
            rewind(func_get_arg(0));

            return DRCFactory::create(
                ConstantStream::class,
                [
                    'CONTENTS' => stream_get_contents(func_get_arg(0)),
                ],
            );
        }

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
