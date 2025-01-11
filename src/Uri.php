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
        return (static::safeGetConstant('USER_INFO'))
            . (
                static::safeGetConstant('HOST') !== ''
                    ? (static::safeGetConstant('USER_INFO') !== '' ? '@' : '') . static::safeGetConstant('HOST')
                    : ''
            )
            . (
                static::safeGetConstant('PORT') !== null
                    ? ':' . static::safeGetConstant('PORT')
                    : ''
            );
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
        return str_starts_with(static::safeGetConstant('PATH') ?? '', '/')
            ? '/' . ltrim(static::safeGetConstant('PATH') ?? '', '/')
            : static::safeGetConstant('PATH') ?? '';
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
        );
    }

    public function withPort(?int $port): UriInterface
    {
        return self::withConstants(
            null,
            func_get_arg(0) === 80 || func_get_arg(0) === 443
                ? null
                : func_get_arg(0),
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
            str_replace(' ', '%20', func_get_arg(0)),
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
            func_get_arg(0),
            null,
        );
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        return self::withConstants(
            null,
            null,
            null,
            null,
            null,
            null,
            Novara::Call::pass(
                func_num_args() > 1 && strlen(func_get_arg(1))
                    ? urlencode(urldecode(func_get_arg(0))) . ':' . urlencode(urldecode(func_get_arg(1)))
                    : urlencode(urldecode(func_get_arg(0))),
                fn () => func_get_arg(0) === ':' ? '' : func_get_arg(0),
            ),
        );
    }

    public function __toString(): string
    {
        return (static::safeGetConstant('SCHEME') ?? '')
            . '://'
            . (
                static::safeGetConstant('USER_INFO') !== ''
                    ? static::safeGetConstant('USER_INFO') . '@'
                    : ''
            )
            . static::safeGetConstant('HOST')
            . (
                static::safeGetConstant('PORT') !== null
                    ? ':' . static::safeGetConstant('PORT')
                    : ''
            )
            . (static::safeGetConstant('PATH') ?: '')
            . (
                static::safeGetConstant('QUERY') !== ''
                    ? '?' . static::safeGetConstant('QUERY')
                    : ''
            )
            . (
                static::safeGetConstant('FRAGMENT') !== ''
                    ? '#' . static::safeGetConstant('FRAGMENT')
                    : ''
            );
    }

    private static function withConstants(): Uri
    {
        return Novara::Call::pass(
            Novara::Call::args(
                [
                    'query',
                    'port',
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
