<?php

declare(strict_types=1);

namespace Novara\Psr7;

use Alexanderpas\Common\HTTP\ReasonPhrase;
use Error;
use Novara\Base\Novara;
use Novara\DynamicReadonlyClasses\DRCFactory;
use Novara\Psr7\Factory\StreamFactory;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class Response implements ResponseInterface
{
    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    private static function safeGetConstant(): mixed
    {
        try {
            return match (func_get_arg(0)) {
                'STATUS_CODE' => static::STATUS_CODE,
                'REASON_PHRASE' => static::REASON_PHRASE,
                'HEADERS' => static::HEADERS,
                'BODY' => static::BODY,
                'PROTOCOL_VERSION' => static::PROTOCOL_VERSION,
                default => null,
            };
        } catch (Error) {
            return null;
        }
    }

    private static function withConstants(): Response
    {
        return Novara::Call::pass(
            Novara::Call::args(
                [
                    'code',
                    'reasonPhrase',
                    'headers',
                    'body',
                    'protocolVersion'
                ],
                func_get_args(),
            ),
            fn () => DRCFactory::create(Response::class, [
                'STATUS_CODE' => func_get_arg(0)->code
                    ?? static::safeGetConstant('STATUS_CODE'),
                'REASON_PHRASE' => func_get_arg(0)->reasonPhrase
                    ?? static::safeGetConstant('REASON_PHRASE'),
                'HEADERS' => func_get_arg(0)->headers
                    ?? static::safeGetConstant('HEADERS'),
                'BODY' => func_get_arg(0)->body
                    ?? static::safeGetConstant('BODY'),
                'PROTOCOL_VERSION' => func_get_arg(0)->protocolVersion
                    ?? static::safeGetConstant('PROTOCOL_VERSION'),
            ]),
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getProtocolVersion(): string
    {
        return static::PROTOCOL_VERSION;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            func_get_arg(0),
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getHeaders(): array
    {
        return static::HEADERS;
    }

    public function hasHeader(string $name): bool
    {
        // TODO
    }

    public function getHeader(string $name): array
    {
        // TODO: Implement getHeader() method.
    }

    public function getHeaderLine(string $name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withHeader() method.
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withAddedHeader() method.
    }

    public function withoutHeader(string $name): MessageInterface
    {
        // TODO: Implement withoutHeader() method.
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getBody(): StreamInterface
    {
        return (new StreamFactory())->createStream(static::BODY);
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            func_get_arg(0)->getContents(),
            null,
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getStatusCode(): int
    {
        return static::STATUS_CODE;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        return self::withConstants(
            func_get_arg(0),
            func_num_args() >= 2 ? func_get_arg(1) : '',
            null,
            null,
            null,
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getReasonPhrase(): string
    {
        return static::REASON_PHRASE === ''
            ? ReasonPhrase::fromInteger(static::STATUS_CODE)->value
            : static::REASON_PHRASE;
    }
}
