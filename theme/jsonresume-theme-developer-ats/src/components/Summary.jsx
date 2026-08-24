import { renderRichText } from '../lib/richText.jsx'

export default function Summary ({ summary }) {
  if (!summary) return null

  return (
    <section className="mb-6 print:break-inside-avoid">
      <p className="text-[10pt] leading-relaxed whitespace-pre-line text-ink/80">{renderRichText(summary)}</p>
    </section>
  )
}
