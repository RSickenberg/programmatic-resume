<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume;
use Romainsickenberg\ProgrammaticResume\Generator\BackendDev;

require __DIR__ . '/vendor/autoload.php';

try {
    new BackendDev()();
} catch (\JsonException $e) {
    throw new \RuntimeException('Could not read backend dev json: ' . $e->getMessage());
}