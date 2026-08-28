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

Tried and reverted (2026-08-28): non-breaking spaces inside `**bold**` terms
and `Skills` keywords, so those specific atomic phrases could never wrap
internally. Fixed the targeted spots, but a long keyword with nowhere normal
left to wrap (e.g. `skills.lower_level_name`'s
`Swift 5 (iOS, WatchOS, Combine, Intents, Swift UI, etc.)`) fell back to
breaking mid-word on a narrower layout ("Comb-ine"), which is visible on
screen, not just to a parser, and copy-pasting text across such a break picks
up a spurious space ("management" -> "mana gement") - worse than the bug it
targeted. Reverted in `46b878f`.

Do not re-attempt a content-level or CSS-level fix without a design that
handles a keyword/phrase too long to fit its column - protecting every
multi-word phrase in every sentence recreates this same regression
everywhere. A real fix would need a different PDF-generation approach, not a
whitespace patch.
