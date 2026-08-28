import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle, useUiString } from '../lib/i18n.js'
import { renderRichText } from '../lib/richText.jsx'

export default function Education ({ education = [] }) {
  const title = useSectionTitle('education')
  const courseworkLabel = useUiString('coursework')
  const connector = useUiString('in')
  const gpaLabel = useUiString('gpa')

  if (education.length === 0) return null

  return (
    <Section title={title}>
      {education.map((edu, index) => (
        <Entry
          key={index}
          title={edu.institution}
          titleHref={edu.url}
          subtitle={edu.studyType && edu.area ? `${edu.studyType} ${connector} ${edu.area}` : edu.studyType || edu.area}
          meta={<DateRange startDate={edu.startDate} endDate={edu.endDate} />}
          description={edu.score ? `${gpaLabel}: ${edu.score}` : undefined}
        >
          {edu.courses && edu.courses.length > 0 && (
            {/*
              * break-before-avoid keeps the list with its diploma, so it can
              * never open a page on its own; the list itself stays breakable,
              * so it fills the page instead of pushing the whole entry over.
              * The label carries break-after-avoid so it cannot be the last
              * thing on a page either.
              */} &&
            <div className="mt-2 print:break-before-avoid">
              <p className="text-[8pt] font-semibold uppercase tracking-wide text-subtle print:break-after-avoid">{courseworkLabel}</p>
              <p className="mt-1 text-[8pt] leading-snug text-ink/70">
                {renderRichText(edu.courses.join(', '))}
              </p>
            </div>
          )}
        </Entry>
      ))}
    </Section>
  )
}
