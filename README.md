# programmatic-resume

A JSON Resume, generated from PHP data objects and rendered with a custom
[jsonresume-theme-developer-ats](theme/jsonresume-theme-developer-ats) theme.

## Install

```bash
bun install
composer install
```

## Generate

```bash
make backend      # resume.json / resume.html / resume.pdf (English)
make backend-fr   # resume.fr.json / resume.fr.html / resume.fr.pdf (French)
```

Or directly via the console command:

```bash
php entrypoint.php backend-dev --locale fr -o resume.fr.json
bunx resuml render -t jsonresume-theme-developer-ats -r resume.fr.json --language fr -o resume.fr.html
bunx resuml pdf    -t jsonresume-theme-developer-ats -r resume.fr.json --language fr -o resume.fr.pdf
```

Running `php entrypoint.php` with no profile argument prompts interactively
for one; `--help` lists all options.

## Localization

Resume content (job titles, summaries, highlights, skills, languages,
interests) is translated via [Symfony Translation](https://symfony.com/doc/current/translation.html),
with catalogs at `translations/messages.en.yaml` and `translations/messages.fr.yaml`.
Adding a new locale means adding a `translations/messages.<locale>.yaml`
catalog and registering it in `GenerateResumeCommand::LOCALES`; adding a new
resume profile (e.g. `SupportN1N2`) means implementing `AbstractResume`
and calling `$this->trans('...')` for its content instead of hardcoding
strings — see [BaseResume.php](src/Generator/BaseResume.php) and
[BackendDev.php](src/Generator/BackendDev.php) for the pattern.

The theme itself localizes its own static chrome (section titles like
"Experience"/"Skills", and the "Present" label for ongoing roles) based on
the `locale` passed to its `render(resume, { locale })` function — see
[src/lib/i18n.js](theme/jsonresume-theme-developer-ats/src/lib/i18n.js).

## Adding a new resume profile

See [GeneratorRegistry.php](src/Generator/GeneratorRegistry.php): implement
`AbstractResume`, register the class there, and it becomes available as
`php entrypoint.php <slug>` in every supported locale automatically.
