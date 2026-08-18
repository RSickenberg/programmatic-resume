import Section from './Section.jsx'

export default function Skills ({ skills = [] }) {
  if (skills.length === 0) return null

  return (
    <Section title="Skills">
      <div className="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
        {skills.map((skill, index) => (
          <div key={index}>
            <h3 className="mb-1.5 font-mono text-[9pt] font-bold uppercase tracking-wider text-accent">
              {skill.name}
            </h3>
            {skill.keywords && skill.keywords.length > 0 && (
              <p className="rounded border-l-2 border-accent bg-surface px-3 py-2 font-mono text-[9pt] leading-relaxed text-muted">
                {skill.keywords.join(', ')}
              </p>
            )}
          </div>
        ))}
      </div>
    </Section>
  )
}
