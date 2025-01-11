<?php

declare(strict_types=1);

namespace Novara\Psr7\Factory;

use Novara\Base\Novara;
use Novara\Psr7\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{
    public function createUri(string $uri = ''): UriInterface
    {
        return Novara::Call::pass(
            parse_url($uri),
            fn () => (new class () extends Uri {})
                ->withScheme(func_get_arg(0)['scheme'] ?? '')
                ->withHost(func_get_arg(0)['host'] ?? '')
                ->withPort(func_get_arg(0)['port'])
                ->withUserInfo(func_get_arg(0)['user'] ?? '', func_get_arg(0)['pass'] ?? '')
                ->withPath(func_get_arg(0)['path'] ?? '')
                ->withQuery(func_get_arg(0)['query'] ?? ''),
        );
    }
}
