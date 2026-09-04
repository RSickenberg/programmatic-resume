# Decisions

Short notes on content/source decisions that aren't obvious from the code, so
they don't get silently reverted or re-litigated later.

## Shared translation keys reused with different context per CV

`work.ilem.highlight_3` ("...CRM used across that same 20-store network")
restates the "20" figure that also appears in `work.ilem.highlight_1` on this
key's other consumer (`backend-dev`). Normally this would read as redundant
on one CV, but each generated CV is standalone: nobody reads two profile
variants side by side, so a figure repeated across two highlights that only
ever appear together on `backend-dev` is a non-issue there, and giving
`highlight_3` its own scale on `cx-specialist` (where `highlight_1` isn't
shown) is worth the shared-key trade-off. Decided 2026-08-28, per Romain.

## Known limitation: Chromium drops the space glyph at a line-wrap

Chromium's print-to-PDF silently omits the glyph for a regular space when it
falls exactly on a visual line-wrap, gluing the two halves of a word together
in the PDF's raw text layer (e.g. "needs assessment to representing" reads as
"needs assessment torepresenting" to anything parsing the content stream).
Invisible on screen; only shows up extracting the PDF's char stream. Confirmed
pervasive across plain prose everywhere in the theme, not limited to any one
component - reproduced it in a CV's own summary paragraph, untouched by any
content change.

First attempt (2026-08-28) reverted in `46b878f`: non-breaking spaces inside
`**bold**` terms and `Skills` keywords, so those specific atomic phrases
could never wrap internally. Fixed the targeted spots, but a long keyword
with nowhere normal left to wrap (`skills.lower_level_name`'s
`Swift 5 (iOS, WatchOS, Combine, Intents, Swift UI, etc.)`, a whole
comma-separated list crammed into one keyword string) fell back to breaking
mid-word on a narrower layout ("Comb-ine"), visible on screen, and
copy-pasting text across such a break picked up a spurious space
("management" -> "mana gement") - worse than the bug it targeted.

Root cause of that regression: `Skills.jsx` keywords and `**bold**` terms are
supposed to be single atomic labels, but three keywords across the codebase
were actually short comma-separated lists disguised as one string (Frontend's
`React, Next.js, Nuxt.js, NPM & Bun`, Mobile's Swift entry above, and
Support's `Microsoft 365 (Outlook, Teams, SharePoint)`). Non-breaking-space
protection is only safe when every protected string really is one term.

Fixed properly by splitting those three into genuine individual keywords
(`src/Generator/BackendDev.php`, `src/Generator/SupportN1N2N3.php`) - matching
how every other skill category was already authored - then re-applying the
non-breaking-space protection (`richText.jsx`, `Skills.jsx`) with `min-w-0` on
the grid cell (so it can shrink to its track width instead of stealing space
from siblings) and `overflow-wrap:anywhere` kept only as a last-resort safety
net, never expected to trigger in normal content. Verified against the real
JetBrains Mono font (fetched and installed locally to test faithfully) at
both the real print width and an artificially tightened one: no glued words,
no mid-word breaks, no column bleed, across backend, support and
cx-specialist.

Any future keyword or bolded term must be a genuine single label, not a list
- split multi-item content into separate keywords instead of one
comma-separated string, or this regression returns.

## Fixed: CSS gap is not text content

Distinct from the line-wrap bug above. `Header.jsx`'s contact row and
`Entry.jsx`'s title/meta row used `gap-x-*` / `justify-between` to space
sibling elements apart - a pure layout property. Chromium's PDF text layer
emits no glyph for it, so adjacent items glue together in the raw content
stream on a single line, wrap or no wrap (e.g. `CHr.sickenberg@gmail.com`,
`Full-Stack03.2024`). Confirmed via char x-position: a visible gap on the
page, zero characters between the elements in the text layer.

Fixed (2026-09-04) by inserting a literal `·` as its own text node between
items in both components, instead of relying on layout spacing. Verified by
re-extracting the raw content stream after rebuild: both cases now read
`...CH·r.sickenberg@gmail.com·...` and `Full-Stack Software Developer·03.2024 - 12.2025`.

This is a narrower, safer fix than the reverted one above: it adds a real
character where none existed, rather than making an already-present space
non-breaking, so it carries none of that attempt's mid-word-break risk.

**Not yet fixed, same root cause:** block-level elements (e.g. `Entry`'s meta
row and the next block's subtitle) still glue on a vertical boundary -
`12.2025Academic Work SA` in the raw stream - because a CSS margin between
blocks is exactly as textless as a flex gap. Lower real-world risk than the
single-line case (most PDF text extractors infer a break from the Y-position
jump, unlike same-line, same-Y adjacent elements), and not something any CV
audit has flagged, so left alone rather than fixed speculatively. If it ever
needs fixing, the same "insert a real character" approach applies.
