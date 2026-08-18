<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume;

use Romainsickenberg\ProgrammaticResume\Console\GenerateResumeCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$command = new GenerateResumeCommand();

$application = new Application('programmatic-resume');
$application->addCommand($command);
$application->setDefaultCommand($command->getName(), true);

exit($application->run());
