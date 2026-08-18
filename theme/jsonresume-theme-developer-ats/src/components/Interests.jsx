import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'

export default function Interests ({ interests = [] }) {
  if (interests.length === 0) return null

  return (
    <Section title="Interests">
      <SimpleList>
        {interests.map((interest, index) => (
          <SimpleItem key={index} label={interest.name} keywords={interest.keywords} />
        ))}
      </SimpleList>
    </Section>
  )
}
