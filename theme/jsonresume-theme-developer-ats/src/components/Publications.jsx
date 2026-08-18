import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'

export default function Publications ({ publications = [] }) {
  if (publications.length === 0) return null

  return (
    <Section title="Publications">
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
