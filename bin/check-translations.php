<?php

declare(strict_types=1);

/*
 * Guards the translation files against two defects that are invisible on screen
 * and only surface in the PDF's text layer, which is what an ATS actually reads.
 *
 *  1. Adjacent bold spans. `**GitHub Actions** **CI/CD**` renders with a visible
 *     gap, but the separating space never reaches the PDF: the text layer holds
 *     the single token "ActionsCI/CD", so an ATS matches neither keyword. Merge
 *     them into one span: `**GitHub Actions CI/CD**`.
 *
 *  2. Key drift between locales. A key present in one file and missing from the
 *     other renders the raw key ("work.antistatique.highlight_6") into the CV.
 */

use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/../vendor/autoload.php';

const LOCALE_FILES = [
    'en' => __DIR__ . '/../translations/messages.en.yaml',
    'fr' => __DIR__ . '/../translations/messages.fr.yaml',
];

/**
 * Flatten a parsed YAML tree into dot-separated key => scalar pairs.
 *
 * @param array<string, mixed> $node
 * @return array<string, mixed>
 */
function flatten(array $node, string $prefix = ''): array
{
    $flat = [];

    foreach ($node as $key => $value) {
        $path = $prefix . $key;

        if (is_array($value)) {
            $flat += flatten($value, $path . '.');

            continue;
        }

        $flat[$path] = $value;
    }

    return $flat;
}

$locales = [];
foreach (LOCALE_FILES as $locale => $path) {
    if (!is_file($path)) {
        fwrite(STDERR, sprintf("x missing translation file: %s\n", $path));

        exit(1);
    }

    $locales[$locale] = flatten((array) Yaml::parseFile($path));
}

$failed = false;

// 1. Adjacent bold spans.
foreach ($locales as $locale => $strings) {
    foreach ($strings as $key => $value) {
        if (!is_string($value) || !str_contains($value, '** **')) {
            continue;
        }

        $failed = true;
        fwrite(STDERR, sprintf(
            "x [%s] %s - adjacent bold spans; the separating space is dropped from the PDF text layer.\n    %s\n",
            $locale,
            $key,
            $value,
        ));
    }
}

if ($failed) {
    fwrite(STDERR, "  Merge them into a single **...** span so both keywords stay readable to an ATS.\n");
}

// 2. Key parity across locales.
$en = array_keys($locales['en']);
$fr = array_keys($locales['fr']);

foreach ([['en', 'fr', array_diff($en, $fr)], ['fr', 'en', array_diff($fr, $en)]] as [$present, $absent, $missing]) {
    if ([] === $missing) {
        continue;
    }

    $failed = true;
    implode(', ', $missing)
        |> (static fn($x) => sprintf("x key(s) in %s but not in %s: %s\n", $present, $absent, $x,))
        |> (static fn($x) => fwrite(STDERR, $x));
}

if ($failed) {
    fwrite(STDERR, "  Every key must exist in both locales, or the raw key renders into the CV.\n");

    exit(1);
}

echo "translations ok: no adjacent bold spans, locales in sync\n";

exit(0);
