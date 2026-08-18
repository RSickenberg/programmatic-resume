import Section from './Section.jsx'
import { useSectionTitle } from '../lib/i18n.js'

export default function Skills ({ skills = [] }) {
  const title = useSectionTitle('skills')

  if (skills.length === 0) return null

  return (
    <Section title={title}>
      <div className="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 sm:auto-rows-fr print:break-inside-avoid">
        {skills.map((skill, index) => (
          <div key={index} className="flex flex-col print:break-inside-avoid">
            <h3 className="mb-1.5 font-mono text-[9pt] font-bold uppercase tracking-wider text-accent">
              {skill.name}
            </h3>
            {skill.keywords && skill.keywords.length > 0 && (
              <p className="flex-1 rounded border-l-2 border-accent bg-surface px-3 py-2 font-mono text-[9pt] leading-relaxed text-muted">
                {skill.keywords.join(', ')}
              </p>
            )}
          </div>
        ))}
      </div>
    </Section>
  )
}
