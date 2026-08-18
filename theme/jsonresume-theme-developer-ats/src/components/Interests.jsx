import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Interests ({ interests = [] }) {
  const title = useSectionTitle('interests')

  if (interests.length === 0) return null

  return (
    <Section title={title}>
      <SimpleList>
        {interests.map((interest, index) => (
          <SimpleItem key={index} label={interest.name} keywords={interest.keywords} />
        ))}
      </SimpleList>
    </Section>
  )
}
