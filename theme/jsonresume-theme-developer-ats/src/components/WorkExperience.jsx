import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'

export default function WorkExperience ({ work = [] }) {
  if (work.length === 0) return null

  return (
    <Section title="Experience">
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
