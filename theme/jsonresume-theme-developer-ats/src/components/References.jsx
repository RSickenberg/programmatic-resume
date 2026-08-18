import Section from './Section.jsx'
import Entry from './Entry.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function References ({ references = [] }) {
  const title = useSectionTitle('references')

  if (references.length === 0) return null

  return (
    <Section title={title}>
      {references.map((ref, index) => (
        <Entry key={index} title={ref.name} description={ref.reference} />
      ))}
    </Section>
  )
}
