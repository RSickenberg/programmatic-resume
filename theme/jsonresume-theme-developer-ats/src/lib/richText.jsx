/**
 * Renders `**word**` as bold, a minimal Markdown-style convention so resume
 * content (translations) can emphasize a word without the theme needing to
 * know why — e.g. `education.courses.*` or `basics.summary_role` keys.
 *
 * A bolded phrase is always a single named term ("LHC Lausanne", "GitHub
 * Actions CI/CD"), never a full clause, so its internal spaces are replaced
 * with non-breaking spaces: Chromium's PDF text layer silently drops the
 * glyph for a regular space that lands exactly on a visual line-wrap, which
 * glues the two halves of the term together for any parser reading the raw
 * content stream. A non-breaking space can never be a wrap point, so the
 * term either fits or moves to the next line whole.
 */
export function renderRichText (text) {
  if (typeof text !== 'string' || !text.includes('**')) return text

  return text.split(/\*\*(.+?)\*\*/g).map((part, index) => (
    index % 2 === 1 ? <strong key={index} className="font-semibold text-ink">{part.replace(/ /g, ' ')}</strong> : part
  ))
}
