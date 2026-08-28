import { renderRichText } from '../lib/richText.jsx'

/**
 * A highlight may carry its own right-aligned meta (typically a date range) by
 * ending with `|| <meta>`. Used by grouped entries that bundle several dated
 * items under one heading, where a single entry-level date range would be
 * misleading.
 *
 * The row does NOT wrap: `flex-wrap` would drop the date onto a line of its own
 * as soon as the text grew too long, which is what happens in French. Instead
 * the text shrinks (`min-w-0`) and wraps inside its own column while the date
 * holds its place on the first line (`shrink-0`).
 */
function splitMeta (item) {
  if (typeof item !== 'string') return [item, null]

  const index = item.lastIndexOf('||')
  if (index === -1) return [item, null]

  return [item.slice(0, index).trim(), item.slice(index + 2).trim()]
}

export default function HighlightList ({ items, continuation = false }) {
  if (!items || items.length === 0) return null

  return (
    <ul className={`${continuation ? 'mt-0.5' : 'mt-1.5'} list-outside list-disc space-y-0.5 pl-4 marker:text-accent`}>
      {items.map((item, index) => {
        const [text, meta] = splitMeta(item)

        return (
          <li key={index} className="pl-0.5 text-[10pt] leading-tight text-ink/80 print:break-inside-avoid">
            {meta
              ? (
                <span className="flex items-baseline justify-between gap-x-4">
                  <span className="min-w-0">{renderRichText(text)}</span>
                  <span className="shrink-0 whitespace-nowrap font-mono text-[8.5pt] font-medium text-subtle">{meta}</span>
                </span>
                )
              : renderRichText(text)}
          </li>
        )
      })}
    </ul>
  )
}
