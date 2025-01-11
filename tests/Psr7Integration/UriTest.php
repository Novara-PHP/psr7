<?php

declare(strict_types=1);

namespace Novara\Psr7\Tests\Psr7Integration;

use Http\Psr7Test\UriIntegrationTest;
use Novara\Psr7\Factory\UriFactory;
use Novara\Psr7\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Uri::class)]
#[UsesClass(UriFactory::class)]
final class UriTest extends UriIntegrationTest
{
    protected $skippedTests = [
        'testWithSchemeInvalidArguments' => '',
    ];

    public function createUri($uri)
    {
        return (new UriFactory())->createUri($uri);
    }
}
