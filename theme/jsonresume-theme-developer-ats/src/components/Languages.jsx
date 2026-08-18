import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Languages ({ languages = [] }) {
  const title = useSectionTitle('languages')

  if (languages.length === 0) return null

  return (
    <Section title={title}>
      <SimpleList>
        {languages.map((lang, index) => (
          <SimpleItem key={index} label={lang.language} meta={lang.fluency} />
        ))}
      </SimpleList>
    </Section>
  )
}
