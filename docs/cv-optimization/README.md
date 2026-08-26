# CV optimisation

The working parts live in `.claude/agents/`:

- **`ats-reviewer`** audits a built PDF, scores it over 8 dimensions, and returns
  ranked fixes addressed to YAML keys. Read-only.
- **`cv-optimizer`** applies those fixes to `translations/messages.{en,fr}.yaml`
  and the generator classes, then hands the build back to Romain.

Both carry one hard rule: **no fact may enter a CV that is not already in the
source.** Metric gaps are reported as questions, never filled with plausible
numbers. A fabricated figure is a claim he gets interviewed against.

## Loop

```
make all  ->  ats-reviewer  ->  verdict ITERATE  ->  cv-optimizer  ->  make all  ->  ...
```

`php` and `bun` are on Romain's machine, not on the session shell, so the agent
edits the source and he runs the build.

## Where things are

| Path | What |
|---|---|
| `translations/messages.{en,fr}.yaml` | every CV string |
| `src/Generator/` | structure, skills, which sections each profile shows |
| `theme/.../src/components/` | layout and pagination rules |
| `bin/check-translations.php` | locale parity + adjacent bold spans, wired into `make deps` |
| `output/ats/*.txt` | extracted text layer, what a parser ingests |
| `output/ats/AUDIT.md` | the scored audit |

## Results

Backend went from 59 to 85, and from four pages to two. Support sits at 76,
held back by its single collapsed work entry. `AUDIT.md` has the breakdown and
the open items.

## Pagination invariants

These were learned the hard way and are recorded in the component headers.
Read those before changing layout.

- A section holds together on one page (`Section.jsx`). Work experience opts out
  with `breakable`, because it is larger than a page and the browser would drop
  the constraint anyway.
- An entry under 5 highlights never splits. Above that it splits once, after the
  second highlight, so a break can never strand a lone bullet (`Entry.jsx`).
- The separator between entries is a **top** border. CSS has no selector for
  "first element on a page", so a bottom border strands a dangling rule at the
  foot of a page. Excluded with `:first-of-type`, not `:first-child`: the
  section renders its `<h2>` first, so the leading entry is `:nth-child(2)`.
- Coursework is attached to its diploma with `break-before-avoid` but stays
  internally breakable, so it fills a page instead of pushing the whole entry over.
- A dated highlight (`text || date`) does not wrap as a row. The text shrinks and
  wraps in its own column while the date holds its place (`HighlightList.jsx`).

## Two measurement traps

**Extraction order.** `extract_text()` sorts by visual position and interleaves
multi-column blocks. `''.join(c['text'] for c in page.chars)` follows the PDF
content stream and preserves reading order. The SKILLS grid looks scrambled in
the first and reads correctly in the second, so check both before calling a
layout defect real.

**Page fill.** White space at the foot of a page always equals the size of the
next atomic block that did not fit. There is no CSS way to stretch content into
it; the only lever is making atomic blocks smaller. Measure, do not estimate.
