import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Projects ({ projects = [] }) {
  const title = useSectionTitle('projects')

  if (projects.length === 0) return null

  return (
    <Section title={title}>
      {projects.map((project, index) => (
        <Entry
          key={index}
          title={project.name}
          titleHref={project.url}
          meta={(project.startDate || project.endDate) && (
            <DateRange startDate={project.startDate} endDate={project.endDate} />
          )}
          description={project.description}
          highlights={project.highlights}
        />
      ))}
    </Section>
  )
}
