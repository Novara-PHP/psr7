<?php

declare(strict_types=1);

use Novara\Base\Autoload\Loader;

Loader::register(new class {
    public const string PREFIX = 'Novara\\Psr7\\';
    public const string DIRECTORY = __DIR__ . '/src';
});
