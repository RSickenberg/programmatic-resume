<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Enums;

enum WorkTypes: string
{
    case IT = 'it';
    case SECURITY = 'security';
    case PILOT = 'pilot';
    case MISC = 'misc';
    case BREAKS = 'breaks';
}
