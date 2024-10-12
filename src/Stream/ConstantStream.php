<?php

declare(strict_types=1);

namespace Novara\Psr7\Stream;

use Error;
use Psr\Http\Message\StreamInterface;

abstract class ConstantStream implements StreamInterface
{
    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    private static function safeGetContents(): string
    {
        try {
            return static::CONTENTS;
        } catch (Error) {
            return '';
        }
    }

    public function __toString(): string
    {
        return static::safeGetContents();
    }

    public function close(): void
    {
    }

    public function detach(): null
    {
        return null;
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getSize(): ?int
    {
        try {
            return strlen(self::CONTENT);
        } catch (Error) {
            return 0;
        }
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return false;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write(string $string): int
    {
        return 0;
    }

    public function isReadable(): bool
    {
        return false;
    }

    public function read(int $length): string
    {
        return '';
    }

    public function getContents(): string
    {
        return static::safeGetContents();
    }

    public function getMetadata(?string $key = null)
    {
        return null;
    }
}
