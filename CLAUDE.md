# CLAUDE.md

Guidance for Claude Code when working in this repository. Keep this file
short and factual — delete any section that doesn't apply, fill in the rest.

## What this project is

A JSON Resume generated from PHP data objects and rendered with a custom
ATS-friendly theme, with English/French localization.

## Stack

- PHP 8.5 (Symfony Console + Symfony Translation, `juststoveking/resume-php`) — generates `resume.json` from `src/Generator/*` profile classes
- `theme/jsonresume-theme-developer-ats` — a React 19 + Tailwind CSS 4 (Vite) theme package, built with Bun
- `resuml` (via `bunx`) renders the JSON Resume into HTML/PDF using the theme
- Composer for PHP deps, Bun for JS deps

## Common commands

<!-- Keep this in sync with the Makefile / package.json scripts, don't duplicate logic here. -->

| Task | Command |
|---|---|
| Install deps | `bun install && composer install` |
| Generate English resume | `make backend` |
| Generate French resume | `make backend-fr` |
| Generate all locales | `make backends` |
| Run generator directly | `php entrypoint.php backend-dev --locale fr -o resume.fr.json` |
| Build the theme package | `cd theme/jsonresume-theme-developer-ats && bun run build` |
| Lint / format PHP | `vendor/bin/php-cs-fixer fix` |
| Release a new version | `npm run release` |

There is no automated test suite in this repo currently.

## Conventions

- Commit messages: Conventional Commits (`feat:`, `fix:`, `chore:`, `build(recipe):`, …) — `.release-it.ts` + `auto-changelog` turn these into `CHANGELOG.md` entries automatically.
- Branch `main` is what `release-it` requires and pushes tags to; day-to-day work happens on `dev` (or feature branches), merged in via PR.
- New resume content goes through `AbstractResume` subclasses in `src/Generator/`, registered in `GeneratorRegistry.php`; strings are translated via `$this->trans('...')` against `translations/messages.<locale>.yaml`, never hardcoded.

## Guardrails

- Never edit `.env*` files with real secrets in place — only `.env.example`.
- Ask before force-pushing, rewriting history, or touching CI/CD config.

## Think Before Coding

**Don't assume. Don't hide confusion. Surface tradeoffs.**

Before implementing:
- State your assumptions explicitly. If uncertain, ask.
- If multiple interpretations exist, present them - don't pick silently.
- If a simpler approach exists, say so. Push back when warranted.
- If something is unclear, stop. Name what's confusing. Ask.

## Simplicity First

**Minimum code that solves the problem. Nothing speculative.**

- No features beyond what was asked.
- No abstractions for single-use code.
- No "flexibility" or "configurability" that wasn't requested.
- No error handling for impossible scenarios.
- If you write 200 lines and it could be 50, rewrite it.

Ask yourself: "Would a senior engineer say this is overcomplicated?" If yes, simplify.

## Surgical Changes

**Touch only what you must. Clean up only your own mess.**

When editing existing code:
- Don't "improve" adjacent code, comments, or formatting.
- Don't refactor things that aren't broken.
- Match existing style, even if you'd do it differently.
- If you notice unrelated dead code, mention it - don't delete it.

When your changes create orphans:
- Remove imports/variables/functions that YOUR changes made unused.
- Don't remove pre-existing dead code unless asked.

The test: Every changed line should trace directly to the user's request.

## Goal-Driven Execution

**Define success criteria. Loop until verified.**

Transform tasks into verifiable goals:
- "Add validation" → "Write tests for invalid inputs, then make them pass"
- "Fix the bug" → "Write a test that reproduces it, then make it pass"
- "Refactor X" → "Ensure tests pass before and after"

For multi-step tasks, state a brief plan:
```
1. [Step] → verify: [check]
2. [Step] → verify: [check]
3. [Step] → verify: [check]
```

Strong success criteria let you loop independently. Weak criteria ("make it work") require constant clarification.

---

**These guidelines are working if:** fewer unnecessary changes in diffs, fewer rewrites due to overcomplication, and clarifying questions come before implementation rather than after mistakes.
