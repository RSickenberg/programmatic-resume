import Section from './Section.jsx'
import SimpleList from './SimpleList.jsx'
import SimpleItem from './SimpleItem.jsx'

export default function Awards ({ awards = [] }) {
  if (awards.length === 0) return null

  return (
    <Section title="Awards">
      <SimpleList>
        {awards.map((award, index) => (
          <SimpleItem key={index} label={award.title} meta={award.awarder} date={award.date} />
        ))}
      </SimpleList>
    </Section>
  )
}
