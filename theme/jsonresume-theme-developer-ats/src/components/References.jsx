import Section from './Section.jsx'
import Entry from './Entry.jsx'

export default function References ({ references = [] }) {
  if (references.length === 0) return null

  return (
    <Section title="References">
      {references.map((ref, index) => (
        <Entry key={index} title={ref.name} description={ref.reference} />
      ))}
    </Section>
  )
}
