import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Publications ({ publications = [] }) {
  const title = useSectionTitle('publications')

  if (publications.length === 0) return null

  return (
    <Section title={title}>
      <SimpleList>
        {publications.map((pub, index) => (
          <SimpleItem
            key={index}
            label={pub.name}
            labelHref={pub.url}
            meta={pub.publisher}
            date={pub.releaseDate}
          />
        ))}
      </SimpleList>
    </Section>
  )
}
