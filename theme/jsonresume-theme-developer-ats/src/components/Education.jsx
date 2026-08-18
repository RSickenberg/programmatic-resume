import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Education ({ education = [] }) {
  const title = useSectionTitle('education')

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
        />
      ))}
    </Section>
  )
}
