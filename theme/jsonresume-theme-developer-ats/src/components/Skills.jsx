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
    <Section title={title}>
      <div className="grid grid-cols-1 gap-x-5 gap-y-2.5 sm:grid-cols-3">
        {skills.map((skill, index) => (
          <div key={index} className="flex flex-col print:break-inside-avoid">
            <h3 className="mb-1 font-mono text-[9pt] font-bold uppercase tracking-wider text-accent">
              {renderRichText(skill.name)}
            </h3>
            {skill.keywords && skill.keywords.length > 0 && (
              <p className="flex-1 rounded border-l-2 border-accent bg-surface px-2.5 py-1.5 font-mono text-[9pt] leading-snug text-muted">
                {/*
                  * Non-breaking spaces inside each keyword, same reasoning as
                  * renderRichText's bold-span handling: a keyword is one
                  * atomic term, and a regular space that lands on a visual
                  * line-wrap loses its glyph in the PDF text layer, gluing
                  * the keyword's words together for any parser reading the
                  * raw content stream. The ", " between keywords stays a
                  * normal, breakable space.
                  */}
                {renderRichText(skill.keywords.map((keyword) => keyword.replace(/ /g, ' ')).join(', '))}
              </p>
            )}
          </div>
        ))}
      </div>
    </Section>
  )
}
