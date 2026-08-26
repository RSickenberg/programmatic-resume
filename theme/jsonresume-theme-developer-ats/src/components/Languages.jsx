import Section from './Section.jsx'
import { useSectionTitle } from '../lib/i18n.js'
import { renderRichText } from '../lib/richText.jsx'

/**
 * Rendered as a single wrapped line rather than the stacked SimpleList: three
 * languages taking three full rows cost more vertical space than they earn.
 */
export default function Languages ({ languages = [] }) {
  const title = useSectionTitle('languages')

  if (languages.length === 0) return null

  return (
    <Section title={title}>
      <p className="text-[10pt] leading-snug text-ink/80">
        {languages.map((lang, index) => (
          <span key={index}>
            {index > 0 && <span className="text-subtle"> · </span>}
            <span className="font-semibold text-ink">{renderRichText(lang.language)}</span>
            {lang.fluency && <span> — {renderRichText(lang.fluency)}</span>}
          </span>
        ))}
      </p>
    </Section>
  )
}
