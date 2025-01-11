<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\RequestIntegrationTest;
use Novara\Psr7\Factory\RequestFactory;
use Novara\Psr7\Factory\StreamFactory;
use Novara\Psr7\Factory\UriFactory;
use Novara\Psr7\Request;
use Novara\Psr7\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Psr\Http\Message\RequestInterface;

define('STREAM_FACTORY', StreamFactory::class);
define('URI_FACTORY', UriFactory::class);

#[CoversClass(Request::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(Uri::class)]
#[UsesClass(UriFactory::class)]
final class RequestTest extends RequestIntegrationTest
{
    protected $skippedTests = [
        'testWithHeaderInvalidArguments' => 'no',
        'testWithAddedHeaderInvalidArguments' => 'no',
    ];

    public function createSubject(): RequestInterface
    {
        return (new RequestFactory())->createRequest('GET', new class () extends Uri {});
    }
}
