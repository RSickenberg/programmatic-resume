# ATS audit

Generated from the PDF text layer of the four CVs in `output/`. The extracted
text sits beside this file as `<variant>.txt`: that is what a recruiter gets
from a select-all copy, and roughly what a parser ingests.

## Scores

| Dimension | Max | backend-en | backend-fr | n1n2n3-en | n1n2n3-fr |
|---|---|---|---|---|---|
| Keyword coverage | 25 | 21 | 21 | 21 | 21 |
| Format & parsing | 15 | 12 | 12 | 11 | 11 |
| Evidenced achievements | 20 | 16 | 16 | 11 | 11 |
| Verb & language strength | 15 | 14 | 14 | 14 | 14 |
| Skill relevance & order | 10 | 8 | 8 | 9 | 9 |
| Experience relevance | 10 | 9 | 9 | 6 | 6 |
| Length & density | 3 | 3 | 3 | 2 | 2 |
| Contact & links | 2 | 2 | 2 | 2 | 2 |
| **Total** | **100** | **85** | **85** | **76** | **76** |

Backend started this work at 59.

## What the text layer confirms

- No raw translation keys, no leaked `**` markdown, no double spaces.
- No glued tokens. `GitHub`, `TypeScript`, `WordPress`, `SharePoint` and
  `TeamViewer` trip a naive camel-case check but are legitimate brand casing.
  The `ActionsCI/CD` class of defect is gone and `make lint-translations`
  now fails the build if it returns.
- Reading order survives. The PDF content stream runs column by column
  (`BACKEND` then its keywords, then `DATABASES` then its keywords), so a
  parser reading the stream sees the intended order. `pdfplumber` sorts by
  visual position instead and interleaves the columns, which is why the
  `.txt` files here look scrambled in the SKILLS block. Both modes still
  expose every keyword as a matchable substring.

## Open items

**1. SKILLS reads as one run in a naive extraction (both CVs).**
Block labels and their keyword lists are separate elements, so a parser that
concatenates the stream without inserting positional whitespace produces
`BACKENDPHP`, `FRONTENDTypeScript`. Keyword matching still finds the terms as
substrings, and any position-aware extractor spaces them correctly, so the
impact is limited to parsers doing structured skill-category extraction.
Fixing it properly means putting label and keywords in one text run
(`Backend: PHP, Python, ...`), which changes the boxed grid design.
Left as a decision, not applied.

**2. `n1n2n3` collapses nine years into one entry.**
`USE_BACKEND_DEV_EXPERIENCE = false` folds four employers into a single block
with one date range. The entry now carries five support-relevant highlights,
so it is defensible, but an ATS reconstructing employment history sees one
job rather than four, and per-employer dates are absent. This is the main
reason support scores 76 against backend's 85.

**3. `n1n2n3` has thin quantification.**
Two of five highlights carry numbers (44 iPads, 20 stores). More would need
figures that do not exist in the source; none were invented.

**4. Missing keywords that are genuinely absent from the source.**
Backend: `microservices` (68% of postings), `Kubernetes`, message queues,
`GraphQL`, unit-testing vocabulary beyond Codeception and Cypress.
Support: `SLA`, `service desk`, `asset management`, onboarding/offboarding.
None of these were added, because nothing in the source evidences them.
Adding a keyword that cannot survive an interview question is worse than
missing it.

## Layout

| CV | Pages | White space left at the foot of page 1 |
|---|---|---|
| backend-en | 2 | 19pt (7mm) |
| backend-fr | 2 | 69pt (24mm) |
| n1n2n3-en | 2 | 17pt (6mm) |
| n1n2n3-fr | 2 | 124pt (44mm) |

The gap always equals the size of the next atomic block that did not fit.
Sections are atomic by design (see `Section.jsx`), which is a deliberate
trade: whole blocks over tightly packed pages. `n1n2n3-fr` pays the most for
it because its EDUCATION block, coursework included, is large.

## Reproducing

```bash
make all
python3 - <<'PY'
import pdfplumber
for f in ['backend-en','backend-fr','n1n2n3-en','n1n2n3-fr']:
    with pdfplumber.open(f'output/romain-sickenberg-{f}.pdf') as pdf:
        open(f'output/ats/{f}.txt','w').write(
            "\n".join(p.extract_text() or '' for p in pdf.pages))
PY
```
