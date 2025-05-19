<?php

declare(strict_types=1);

namespace Novara\Psr7\Factory;

use Novara\Psr7\Request;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        return (new class () extends Request {})
            ->withMethod(func_get_arg(0))
            ->withUri(func_get_arg(1));
    }
}
