import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Volunteer ({ volunteer = [] }) {
  const title = useSectionTitle('volunteer')

  if (volunteer.length === 0) return null

  return (
    <Section title={title}>
      {volunteer.map((vol, index) => (
        <Entry
          key={index}
          title={vol.position}
          subtitle={vol.organization}
          meta={<DateRange startDate={vol.startDate} endDate={vol.endDate} />}
          description={vol.summary}
          highlights={vol.highlights}
        />
      ))}
    </Section>
  )
}
