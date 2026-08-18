import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'

export default function Certificates ({ certificates = [] }) {
  if (certificates.length === 0) return null

  return (
    <Section title="Certificates">
      <SimpleList>
        {certificates.map((cert, index) => (
          <SimpleItem
            key={index}
            label={cert.name}
            labelHref={cert.url}
            meta={cert.issuer}
            date={cert.date}
          />
        ))}
      </SimpleList>
    </Section>
  )
}
