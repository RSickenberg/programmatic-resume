# jsonresume-theme-developer-ats

## 0.3.0

### Minor Changes

- Reworked the theme from a single `Resume.jsx` file into separated components
  under `src/components/` (`Header`, `Summary`, `WorkExperience`, `Projects`,
  `Education`, `Volunteer`, `Skills`, `Awards`, `Certificates`, `Publications`,
  `Languages`, `Interests`, `References`, plus shared `Entry`/`Section`/`Link`/
  `DateRange`/`SimpleItem` primitives).
- Replaced styled-components with Tailwind CSS v4, precompiled at build time into
  a single inlined `<style>` block (no runtime CSS-in-JS, no external font/CDN
  requests).
- Made print output ATS-safe: the skills/languages/interests grid collapses to a
  single column under `@media print` so PDF text-extraction reads linearly, work/
  project/education entries use `break-inside: avoid` so an entry is never split
  across a page, and `@page { margin: 0 }` with page-level padding keeps screen
  preview and print output visually identical.
- Fixed the multi-paragraph summary rendering as a single run-on line (missing
  `white-space: pre-line`).
- Dropped the `@jsonresume/core` and `styled-components` dependencies in favor of
  the framework-free `@jsonresume/utils` (date formatting, URL safety).

## 0.2.5

### Patch Changes

- 304a130: Theme curation wave: full JSON Resume section coverage and SSR fixes.

  - Add missing sections (Interests, Certificates, et al.) across 15+ themes so every registered theme renders all schema sections
  - tokyo-modernist: inline styled-components (fixes webpack resolution SSR crash) — re-enabled in the registry
  - tailwind: fix social-icon SSR crash, add 7 missing sections with missing-data guards — re-enabled in the registry
  - Register previously orphaned desert-modern and elegant-pink themes with metadata
  - @jsonresume/core@0.3.2

## 0.2.3

### Patch Changes

- c621133: use @jsonresume/core/ssr renderResumeDocument
- Updated dependencies [ff09f75]
  - @jsonresume/core@0.3.1

## 0.2.2

### Patch Changes

- Updated dependencies [ff0f85b]
  - @jsonresume/core@0.3.0

## 0.2.1

### Patch Changes

- Updated dependencies [36d1759]
  - @jsonresume/core@0.2.0

## 0.2.0

### Minor Changes

- 718690c: Add missing JSON Resume sections (certificates/volunteer/publications); visual and crash fixes.

  Publishes the wave 5-7 theme improvements that currently exist only in git (refs #275). The
  published npm versions are stale: most themes never rendered certificates/volunteer/publications,
  and all carried the `@resume/core` import (renamed to `@jsonresume/core`) plus the Date-shadow
  rendering crash.

  Minor (gained rendered sections via the "render missing sections" batches #363-#366 and the
  operations-precision a11y/markdown work): all themes listed above as `minor` now render the
  previously-missing certificates/volunteer/publications (and related) sections.

  Patch (no new sections; visual, crash and dependency-rename fixes only):

  - consultant-polished: stop crash when certificates/publications present (#359).
  - tokyo-modernist: exports/CI fixes; styled-components moved to dependencies.
  - @jsonresume/theme-stackoverflow: consistent date formatting (#259) + a11y fixes (the
    Yarn-Berry lockfile removal did not change source and does not drive this bump).
  - community-garden, desert-modern, elegant-pink: `@resume/core` -> `@jsonresume/core` import
    fix and visual polish; sections were already rendered.

  Excludes `@jsonresume/jsonresume-theme-professional` (already current on npm) and the private
  themes (claude, creative-confidence, flat, tailwind).
