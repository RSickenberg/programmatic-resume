import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Awards ({ awards = [] }) {
  const title = useSectionTitle('awards')

  if (awards.length === 0) return null

  return (
    <Section title={title}>
      <SimpleList>
        {awards.map((award, index) => (
          <Entry
            key={index}
            title={award.title}
            subtitle={award.awarder}
            meta={<DateRange startDate={award.date} endDate={award.date} />}
            description={award.summary}
          />
        ))}
      </SimpleList>
    </Section>
  )
}
