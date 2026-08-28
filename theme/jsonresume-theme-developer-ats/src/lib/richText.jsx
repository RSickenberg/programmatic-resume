/**
 * Renders `**word**` as bold, a minimal Markdown-style convention so resume
 * content (translations) can emphasize a word without the theme needing to
 * know why — e.g. `education.courses.*` or `basics.summary_role` keys.
 */
export function renderRichText (text) {
  if (typeof text !== 'string' || !text.includes('**')) return text

  return text.split(/\*\*(.+?)\*\*/g).map((part, index) => (
    index % 2 === 1 ? <strong key={index} className="font-semibold text-ink">{part}</strong> : part
  ))
}
