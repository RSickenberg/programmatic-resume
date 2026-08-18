import Section from './Section.jsx'
import Entry from './Entry.jsx'
import DateRange from './DateRange.jsx'

export default function Projects ({ projects = [] }) {
  if (projects.length === 0) return null

  return (
    <Section title="Projects">
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
