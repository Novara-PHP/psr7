<?php

declare(strict_types=1);

namespace Novara\Psr7;

use Error;
use Novara\Base\Novara;
use Novara\DynamicReadonlyClasses\DRCFactory;
use Psr\Http\Message\UriInterface;

abstract class Uri implements UriInterface
{
    public static function fromArray(): Uri
    {
        return (new class () extends Uri {})
            ->withQuery(func_get_arg(0)['query'] ?? '')
            ->withPort(func_get_arg(0)['port'])
            // TODO: authority :)
            ->withHost(func_get_arg(0)['host'] ?? '')
            ->withPath(func_get_arg(0)['path'] ?? '')
            ->withFragment(func_get_arg(0)['fragment'] ?? '')
            ->withScheme(func_get_arg(0)['scheme'] ?? '')
            ->withUserInfo(func_get_arg(0)['userInfo'] ?? '');
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getQuery(): string
    {
        return static::safeGetConstant('QUERY') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getPort(): ?int
    {
        return static::safeGetConstant('PORT');
    }

    /**
    * @noinspection PhpUndefinedClassConstantInspection
    */
    public function getAuthority(): string
    {
        return static::safeGetConstant('AUTHORITY') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getHost(): string
    {
        return static::safeGetConstant('HOST') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getPath(): string
    {
        return static::safeGetConstant('PATH') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getFragment(): string
    {
        return static::safeGetConstant('FRAGMENT') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getScheme(): string
    {
        return static::safeGetConstant('SCHEME') ?? '';
    }

    /**
     * @noinspection PhpUndefinedClassConstantInspection
     */
    public function getUserInfo(): string
    {
        return static::safeGetConstant('USER_INFO') ?? '';
    }

    public function withQuery(string $query): UriInterface
    {
        return self::withConstants(
            func_get_arg(0),
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        );
    }

    public function withPort(?int $port): UriInterface
    {
        return self::withConstants(
            null,
            func_get_arg(0),
            null,
            null,
            null,
            null,
            null,
            null,
        );
    }

    public function withHost(string $host): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            strtolower(func_get_arg(0)),
            null,
            null,
            null,
            null,
        );
    }

    public function withPath(string $path): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            func_get_arg(0),
            null,
            null,
            null,
        );
    }

    public function withFragment(string $fragment): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            null,
            func_get_arg(0),
            null,
            null,
        );
    }

    public function withScheme(string $scheme): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            null,
            null,
            func_get_arg(0),
            null,
        );
    }

    // TODO: authority?
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            func_get_arg(1)
                ? func_get_arg(0) . ':' . func_get_arg(1)
                : func_get_arg(0),
        );
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }

    private static function withConstants(): Uri
    {
        return Novara::Call::pass(
            Novara::Call::args(
                [
                    'query',
                    'port',
                    'authority',
                    'host',
                    'path',
                    'fragment',
                    'scheme',
                    'userInfo',
                ],
                func_get_args(),
            ),
            fn () => DRCFactory::create(Uri::class, [
                'QUERY' => func_get_arg(0)->query
                    ?? static::safeGetConstant('QUERY'),
                'PORT' => func_get_arg(0)->port
                    ?? static::safeGetConstant('PORT'),
                'AUTHORITY' => func_get_arg(0)->authority
                    ?? static::safeGetConstant('AUTHORITY'),
                'HOST' => func_get_arg(0)->host
                    ?? static::safeGetConstant('HOST'),
                'PATH' => func_get_arg(0)->path
                    ?? static::safeGetConstant('PATH'),
                'FRAGMENT' => func_get_arg(0)->fragment
                    ?? static::safeGetConstant('FRAGMENT'),
                'SCHEME' => func_get_arg(0)->scheme
                    ?? static::safeGetConstant('SCHEME'),
                'USER_INFO' => func_get_arg(0)->userInfo
                    ?? static::safeGetConstant('USER_INFO'),
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
                'QUERY' => static::QUERY,
                'PORT' => static::PORT,
                'AUTHORITY' => static::AUTHORITY,
                'HOST' => static::HOST,
                'PATH' => static::PATH,
                'FRAGMENT' => static::FRAGMENT,
                'SCHEME' => static::SCHEME,
                'USER_INFO' => static::USER_INFO,
                default => null,
            };
        } catch (Error) {
            return null;
        }
    }
}
