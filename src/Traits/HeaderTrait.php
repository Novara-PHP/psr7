<?php

declare(strict_types=1);

namespace Novara\Psr7\Traits;

use Novara\Base\Novara;
use Psr\Http\Message\MessageInterface;

trait HeaderTrait
{
    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getHeaders(): array
    {
        return static::HEADERS;
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function hasHeader(string $name): bool
    {
        return isset(static::HEADERS[strtolower(func_get_arg(0))]);
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getHeader(string $name): array
    {
        return static::HEADERS[strtolower(func_get_arg(0))] ?? [];
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getHeaderLine(string $name): string
    {
        return join(', ', static::HEADERS[strtolower(func_get_arg(0))] ?? []);
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        return static::staticSetHeaders(
            Novara::Map::replaceKey(
                static::HEADERS ?? [],
                strtolower(func_get_arg(0)),
                is_array(func_get_arg(1)) ? func_get_arg(1) : [func_get_arg(1)],
            ),
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return static::staticSetHeaders(
            Novara::Map::appendToKey(
                static::HEADERS ?? [],
                strtolower(func_get_arg(0)),
                is_array(func_get_arg(1)) ? func_get_arg(1) : [func_get_arg(1)],
            ),
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function withoutHeader(string $name): MessageInterface
    {
        return static::staticSetHeaders(
            array_filter(Novara::Map::replaceKey(
                static::HEADERS ?? [],
                strtolower(func_get_arg(0)),
                null,
            )),
        );
    }

    abstract protected static function staticSetHeaders();
}
