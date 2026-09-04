import Section from './Section.jsx'
import { useSectionTitle } from '../lib/i18n.js'
import { renderRichText } from '../lib/richText.jsx'

/*
 * Cells stay atomic so a keyword list never splits mid-box. The section around
 * them is atomic too (see Section.jsx), so in practice the whole grid travels
 * as one block; the per-cell rule is what holds if the section ever grows past
 * a single page and the browser has to drop the outer constraint.
 */

export default function Skills ({ skills = [] }) {
  const title = useSectionTitle('skills')

  if (skills.length === 0) return null

  return (
    <Section title={ title }>
      <div className='grid grid-cols-1 gap-x-5 gap-y-2.5 sm:grid-cols-3'>
        { skills.map((skill, index) => (
          <div key={ index } className='flex min-w-0 flex-col print:break-inside-avoid'>
            <h3 className='mb-1 font-mono text-[9pt] font-bold text-accent'>
              { renderRichText(skill.name) }
            </h3>
            { skill.keywords && skill.keywords.length > 0 && (
              <p className='flex-1 rounded border-l-2 border-accent bg-surface px-2.5 py-1.5 text-[9pt] font-mono text-muted wrap-anywhere text-pretty'>
                {/*
                  * Non-breaking spaces inside each keyword, same reasoning as
                  * renderRichText's bold-span handling: a keyword is one
                  * atomic term, and a regular space that lands on a visual
                  * line-wrap loses its glyph in the PDF text layer, gluing
                  * the keyword's words together for any parser reading the
                  * raw content stream.
                  *
                  * "·" instead of "," between keywords, for two reasons: a
                  * comma stranded alone at the start of a wrapped line (from
                  * "keyword,\nnext") reads as a typo, where a lone "·" reads
                  * as a bullet; and unlike a comma, "·" is never mistaken for
                  * part of the keyword itself if a raw-text extractor drops
                  * the space next to it (same Chromium quirk as
                  * Header.jsx/Entry.jsx, harmless here either way since it
                  * only affects text extraction, not the printed page). The
                  * space around it stays normal and breakable - see
                  * DECISIONS.md for why it's not made non-breaking too (that
                  * regressed once already).
                  *
                  * Requires every keyword to be genuinely short (a real
                  * label, not a comma-separated list crammed into one
                  * string - split those into their own keywords instead, see
                  * DECISIONS.md). min-w-0 on the grid cell lets it shrink to
                  * its track width instead of stealing space from siblings
                  * to fit an atomic run; overflow-wrap:anywhere is a last
                  * -resort safety net so an unexpectedly long keyword still
                  * overflows visibly rather than off-page, never silently.
                  */ }
                { renderRichText(skill.keywords.map((keyword) => keyword.replace(/ /g, ' ')).join(', ')) }
              </p>
            ) }
          </div>
        )) }
      </div>
    </Section>
  )
}
