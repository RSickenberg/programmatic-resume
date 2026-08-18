import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'

export default function Languages ({ languages = [] }) {
  if (languages.length === 0) return null

  return (
    <Section title="Languages">
      <SimpleList>
        {languages.map((lang, index) => (
          <SimpleItem key={index} label={lang.language} meta={lang.fluency} />
        ))}
      </SimpleList>
    </Section>
  )
}
