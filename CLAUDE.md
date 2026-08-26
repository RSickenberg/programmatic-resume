# CLAUDE.md

Guidance for Claude Code when working in this repository. Keep this file
short and factual. Delete any section that doesn't apply, fill in the rest.

## What this project is

A JSON Resume generated from PHP data objects and rendered with a custom
ATS-friendly theme, with English/French localization.

## Stack

- PHP 8.5 (Symfony Console + Symfony Translation, `juststoveking/resume-php`) generates `resume.json` from `src/Generator/*` profile classes
- `theme/jsonresume-theme-developer-ats` is a React 19 + Tailwind CSS 4 (Vite) theme package, built with Bun
- `resuml` (via `bunx`) renders the JSON Resume into HTML/PDF using the theme
- Composer for PHP deps, Bun for JS deps

## Common commands

<!-- Keep this in sync with the Makefile / package.json scripts, don't duplicate logic here. -->

| Task | Command |
|---|---|
| Install deps | `bun install && composer install` |
| Generate one profile/locale | `make backend` / `make backend-fr` / `make support-n1n2` / `make support-n1n2-fr` |
| Generate every profile, every locale | `make all` |
| Run generator directly | `php entrypoint.php backend-dev --locale fr -o output/resume.fr.json` |
| Build the theme package | `cd theme/jsonresume-theme-developer-ats && bun run build` |
| Update dependencies (root + theme) | `make update-deps` |
| Check the translation files | `make lint-translations` (runs inside `make deps`) |
| Lint / format PHP | `vendor/bin/php-cs-fixer fix` |
| Release a new version | `npm run release` |

Generated `resume.json`/`.html`/`.pdf` files are written to `output/`.
There is no automated test suite in this repo currently.

## Conventions

- Commit messages: Conventional Commits (`feat:`, `fix:`, `chore:`, `build(recipe):`, …). `.release-it.ts` + `auto-changelog` turn these into `CHANGELOG.md` entries automatically.
- Branch `main` is what `release-it` requires and pushes tags to; day-to-day work happens on `dev` (or feature branches), merged in via PR.
- New resume content goes through `AbstractResume` subclasses in `src/Generator/`, registered in `GeneratorRegistry.php`; strings are translated via `$this->trans('...')` against `translations/messages.<locale>.yaml`, never hardcoded.
- The CV text lives in the YAML, the structure in the generator classes. Never edit a generated PDF: `make all` overwrites it.
- Removing a bullet means removing **both** the YAML key and its `$this->trans(...)` line. Drop only the key and Symfony Translation prints the raw key into the CV.
- `messages.en.yaml` and `messages.fr.yaml` must declare exactly the same keys. `make lint-translations` enforces it.
- Markdown bold in resume strings marks **technology**, never an organisation: `**Symfony**` yes, `SIL` or `Securitas` no.
- Never write two adjacent bold spans. `**GitHub Actions** **CI/CD**` renders a visible gap but emits no space into the PDF text layer, so a parser reads `ActionsCI/CD` and matches neither keyword. Write `**GitHub Actions CI/CD**`. The lint fails the build on this.
- No em dash (`—`) anywhere: prose, comments, strings, documentation.
- Never put a figure in a CV string that is not already in the source or stated by Romain. A fabricated metric is a claim he gets interviewed against.

## Generating and auditing the CVs

`output/` holds four variants: backend and support, each in English and French.
Two subagents in `.claude/agents/` drive the optimisation loop:

- `ats-reviewer` audits a built PDF and returns ranked fixes addressed to YAML keys. Read-only.
- `cv-optimizer` applies them to the source, then hands the build back.

Both carry a hard no-invention rule. Metric gaps are reported as questions for
Romain, never filled with plausible numbers.

`output/ats/` holds the extracted text layer of each CV plus `AUDIT.md`, the
scored audit. Regenerate them after any content change.

Two notes when measuring a PDF: `extract_text()` sorts by visual position and
interleaves multi-column blocks, while `''.join(c['text'] for c in page.chars)`
follows the content stream and preserves reading order. Check both before
calling a layout defect real. And measure page fill rather than estimating it:
the white space at the foot of a page always equals the size of the next atomic
block that did not fit.

## Guardrails

- Never edit `.env*` files with real secrets in place, only `.env.example`.
- Ask before force-pushing, rewriting history, or touching CI/CD config.
- No `.bak` files. The project is on git.
- The theme's component files carry header comments recording pagination invariants (which blocks may split, why a border is a top border, why the coursework is attached but breakable). Read them before changing layout: each one documents a bug that the comment prevents from returning.

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

### Attribution

**For PR descriptions**, include full attribution:

```
---
Generated with [Claude Code](https://claude.ai/code)
Co-Authored-By: Claude <model-name> <noreply@anthropic.com>
<XX>% AI / <YY>% Human
Claude: <what AI did>
Human: <what human did>
```

- Use the actual model name (e.g., `Claude Opus 4.5`, `Claude Sonnet 4`)
- The percentage split should honestly reflect the contribution balance for that specific work
- This provides a trackable record of AI-assisted development over time

**For issues and comments**, use simplified attribution:

```
---
Written by Claude <model-name> via [Claude Code](https://claude.ai/code)
```

**For commits**, include a Co-Authored-By trailer:

```
Co-Authored-By: Claude <claude@anthropic.com>
```


---

**These guidelines are working if:** fewer unnecessary changes in diffs, fewer rewrites due to overcomplication, and clarifying questions come before implementation rather than after mistakes.
