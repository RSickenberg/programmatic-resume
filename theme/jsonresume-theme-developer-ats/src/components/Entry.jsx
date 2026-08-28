import Link from './Link.jsx'
import HighlightList from './HighlightList.jsx'
import { cx } from '../lib/format.js'
import { renderRichText } from '../lib/richText.jsx'

/**
 * The separating rule is a TOP border, not a bottom one. CSS has no selector
 * for "first element on a page", so a rule attached to an entry renders
 * wherever that entry lands. Attached to the bottom, it strands a dangling
 * line at the foot of a page with nothing beneath it; attached to the top, it
 * travels with the entry it introduces and reads as a deliberate rule instead.
 *
 * The exclusion uses :first-of-type, not :first-child - Section renders its
 * <h2> first, so the leading <article> is :nth-child(2) and :first-child would
 * never match it, printing a stray rule directly under the section heading.
 *
 * An entry short enough to fit a page is never split. Only a long one may
 * break, and then only at a controlled point: its heading always travels with
 * the first two highlights, and at least three follow, so a page break can
 * never strand a lone bullet or an orphaned heading.
 *
 * `children` sits outside the unbreakable group and sets its own break rules.
 * Held inside it, a long child (Education's coursework) turned the whole entry
 * into one large atom, and an atom that does not fit leaves a gap its own size
 * at the foot of the page. See Education.jsx for how it stays attached to its
 * heading without becoming unbreakable.
 */
const SPLIT_MIN_ITEMS = 5
const KEEP_WITH_HEADING = 2

export default function Entry ({ title, titleHref, meta, subtitle, description, highlights, children, className }) {
  const items = highlights ?? []
  const splits = items.length >= SPLIT_MIN_ITEMS
  const withHeading = splits ? items.slice(0, KEEP_WITH_HEADING) : items
  const rest = splits ? items.slice(KEEP_WITH_HEADING) : []

  return (
    <article className={cx('mt-1.5 border-t border-border pt-1.5 [&:first-of-type]:mt-0 [&:first-of-type]:border-t-0 [&:first-of-type]:pt-0', className)}>
      <div className="print:break-inside-avoid">
        <div className="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
          <h3 className="font-mono text-[12pt] font-medium text-ink">
            {titleHref ? <Link href={titleHref} underline={false}>{title}</Link> : renderRichText(title)}
          </h3>
          {meta}
        </div>
        {subtitle && (
          <p className="mt-0.5 text-[10pt] font-medium text-subaccent">{renderRichText(subtitle)}</p>
        )}
        {description && (
          <p className="mt-1 text-[10pt] leading-tight text-ink/80">{renderRichText(description)}</p>
        )}
        <HighlightList items={withHeading} />
      </div>
      {rest.length > 0 && <HighlightList items={rest} continuation />}
      {children}
    </article>
  )
}
