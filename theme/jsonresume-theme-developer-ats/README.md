# Developer ATS

Technical, ATS-safe theme with monospace headers and sans-serif body. Single-column,
semantic HTML built from small, separated React components and styled entirely with
Tailwind CSS utility classes — no styled-components, no runtime CSS-in-JS.

**Tags:** technical, developer, monospace, ats-friendly, print

## Design goals

- **Separated components** — `src/Resume.jsx` only composes section components from
  `src/components/`; each JSON Resume section (work, projects, education, skills, …)
  is its own file, sharing small primitives (`Entry`, `Section`, `SimpleItem`, `Link`,
  `DateRange`) instead of one large file.
- **Tailwind, precompiled** — styles are authored in `src/styles/tailwind.css` (Tailwind
  v4, CSS-first config) and compiled once at build time into a single inlined
  `<style>` block. The published theme has zero client-side dependencies and no
  network calls (no external fonts, no CDN) — safe to render in sandboxed/offline PDF
  pipelines.
- **Print/ATS friendly** — single-column reading order throughout; the only grid
  (skills, languages, interests) collapses to one column under `@media print` so PDF
  text-extraction stays linear. Entries use `break-inside: avoid` so a job or project
  is never split across a page boundary, while sections themselves are allowed to
  flow across pages. `@page { size: A4; margin: 0 }` plus padding on the page element
  keeps on-screen preview and print output visually identical.

## Use it

Install and use it with the [resuml](https://www.npmjs.com/package/resuml) CLI:

```sh
resuml render --resume resume.json --theme developer-ats --output resume.html
resuml pdf --resume resume.json --theme developer-ats --output resume.pdf
```

## Sections

Renders all standard JSON Resume sections: work, education, skills, projects,
volunteer, awards, certificates, publications, languages, interests, references.

## Development

```sh
bun install
bun run build   # compiles Tailwind CSS, then bundles src/index.jsx -> dist/index.js
bun run watch   # rebuilds dist/index.js on change (rerun `build:css` after editing classes)
```

`dist/` is the package entry point (`main`/`exports`) and is committed, since this
theme is consumed via a local `file:` dependency rather than published to npm.

## License

MIT
