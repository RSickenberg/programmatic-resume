import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle, useUiString } from '../lib/i18n.js'
import { formatCourseName } from '../lib/format.js'

export default function Education ({ education = [] }) {
  const title = useSectionTitle('education')
  const courseworkLabel = useUiString('coursework')

  if (education.length === 0) return null

  return (
    <Section title={title}>
      {education.map((edu, index) => (
        <Entry
          key={index}
          title={edu.institution}
          titleHref={edu.url}
          subtitle={edu.studyType && edu.area ? `${edu.studyType} in ${edu.area}` : edu.studyType || edu.area}
          meta={<DateRange startDate={edu.startDate} endDate={edu.endDate} />}
          description={edu.score ? `GPA: ${edu.score}` : undefined}
        >
          {edu.courses && edu.courses.length > 0 && (
            <div className="mt-2 print:break-inside-avoid">
              <p className="text-[8pt] font-semibold uppercase tracking-wide text-subtle">{courseworkLabel}</p>
              <p className="mt-1 columns-3 gap-x-5 text-[7.5pt] leading-snug text-ink/70">
                {edu.courses.map(formatCourseName).join(', ')}
              </p>
            </div>
          )}
        </Entry>
      ))}
    </Section>
  )
}
