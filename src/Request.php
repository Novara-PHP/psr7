<?php

declare(strict_types=1);

namespace Novara\Psr7;

use Error;
use Novara\Base\Novara;
use Novara\DynamicReadonlyClasses\DRCFactory;
use Novara\Psr7\Factory\StreamFactory;
use Novara\Psr7\Traits\HeaderTrait;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

abstract class Request implements RequestInterface
{
    use HeaderTrait;

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getProtocolVersion(): string
    {
        return static::safeGetConstant('PROTOCOL_VERSION') ?? '';
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            func_get_arg(0),
            null,
        );
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
            null,
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getRequestTarget(): string
    {
        return static::safeGetConstant('REQUEST_TARGET') ?? '/';
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        return self::withConstants(
            func_get_arg(0),
            null,
            null,
            null,
            null,
            null,
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getMethod(): string
    {
        return static::safeGetConstant('METHOD') ?? 'GET';
    }

    public function withMethod(string $method): RequestInterface
    {
        return self::withConstants(
            null,
            func_get_arg(0),
            null,
            null,
            null,
            null,
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getUri(): UriInterface
    {
        return Uri::fromArray(json_decode(static::URI, true));
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        return Novara::Call::pass(
            self::withConstants(
                null,
                null,
                null,
                null,
                null,
                json_encode([
                    'query' => func_get_arg(0)->getQuery(),
                    'port' => func_get_arg(0)->getPort(),
                    'authority' => func_get_arg(0)->getAuthority(),
                    'host' => func_get_arg(0)->getHost(),
                    'path' => func_get_arg(0)->getPath(),
                    'fragment' => func_get_arg(0)->getFragment(),
                    'scheme' => func_get_arg(0)->getScheme(),
                    'userInfo' => func_get_arg(0)->getUserInfo(),
                ])
            ),
            fn () => !$preserveHost && $uri->getHost() !== ''
                ? func_get_arg(0)->withHeader('host', $uri->getHost())
                : func_get_arg(0),
        );
    }

    protected static function staticSetHeaders(): Request
    {
        return self::withConstants(
            null,
            null,
            func_get_arg(0),
            null,
            null,
            null,
        );
    }

    private static function withConstants(): Request
    {
        return Novara::Call::pass(
            Novara::Call::args(
                [
                    'requestTarget',
                    'method',
                    'headers',
                    'body',
                    'protocolVersion',
                    'uri',
                ],
                func_get_args(),
            ),
            fn () => DRCFactory::create(Request::class, [
                'REQUEST_TARGET' => func_get_arg(0)->requestTarget
                    ?? static::safeGetConstant('STATUS_CODE'),
                'METHOD' => func_get_arg(0)->method
                    ?? static::safeGetConstant('REASON_PHRASE'),
                'HEADERS' => func_get_arg(0)->headers
                    ?? static::safeGetConstant('HEADERS'),
                'BODY' => func_get_arg(0)->body
                    ?? static::safeGetConstant('BODY'),
                'PROTOCOL_VERSION' => func_get_arg(0)->protocolVersion
                    ?? static::safeGetConstant('PROTOCOL_VERSION'),
                'URI' => func_get_arg(0)->uri
                    ?? static::safeGetConstant('URI'),
            ]),
        );
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    private static function safeGetConstant(): mixed
    {
        try {
            return match (func_get_arg(0)) {
                'REQUEST_TARGET' => static::REQUEST_TARGET,
                'METHOD' => static::METHOD,
                'HEADERS' => static::HEADERS,
                'BODY' => static::BODY,
                'PROTOCOL_VERSION' => static::PROTOCOL_VERSION,
                'URI' => static::URI,
                default => null,
            };
        } catch (Error) {
            return null;
        }
    }
}
