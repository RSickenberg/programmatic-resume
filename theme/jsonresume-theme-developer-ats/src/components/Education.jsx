import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'

export default function Education ({ education = [] }) {
  if (education.length === 0) return null

  return (
    <Section title="Education">
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
