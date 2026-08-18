<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Single place to register a resume profile generator.
 * Add a new class implementing AbstractResume, then add one line here to
 * make it available as `php entrypoint.php <slug>`.
 */
final class GeneratorRegistry
{
    /** @var array<string, class-string<AbstractResume>> */
    private const array PROFILES = [
        'backend-dev' => BackendDev::class,
    ];

    /**
     * @return array<string, class-string<AbstractResume>>
     */
    public static function all(): array
    {
        return self::PROFILES;
    }

    public static function resolve(string $slug, TranslatorInterface $translator, string $locale = 'en'): AbstractResume
    {
        $class = self::PROFILES[$slug] ?? throw new \InvalidArgumentException(\sprintf(
            'Unknown resume profile "%s". Available: %s.',
            $slug,
            implode(', ', array_keys(self::PROFILES)),
        ));

        return new $class($translator, $locale);
    }
}
