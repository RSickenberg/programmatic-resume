import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Certificates ({ certificates = [] }) {
  const title = useSectionTitle('certificates')

  if (certificates.length === 0) return null

  return (
    <Section title={title}>
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
