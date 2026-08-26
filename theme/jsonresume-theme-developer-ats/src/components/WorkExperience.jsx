import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function WorkExperience ({ work = [] }) {
  const title = useSectionTitle('experience')

  if (work.length === 0) return null

  return (
    <Section title={title} breakable>
      {work.map((job, index) => (
        <Entry
          key={index}
          title={job.position || job.name}
          subtitle={job.name}
          meta={<DateRange startDate={job.startDate} endDate={job.endDate} />}
          description={job.summary}
          highlights={job.highlights}
        />
      ))}
    </Section>
  )
}
