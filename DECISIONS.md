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
