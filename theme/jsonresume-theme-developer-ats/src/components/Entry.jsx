import Link from './Link.jsx'
import HighlightList from './HighlightList.jsx'
import { cx } from '../lib/format.js'
import { renderRichText } from '../lib/richText.jsx'

export default function Entry ({ title, titleHref, meta, subtitle, description, highlights, children, className }) {
  return (
    <article className={cx('mb-3 border-b border-border pb-3 last:mb-0 last:border-b-0 last:pb-0 print:break-inside-avoid', className)}>
      <div className="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
        <h3 className="font-mono text-[12pt] font-semibold text-ink">
          {titleHref ? <Link href={titleHref} underline={false}>{title}</Link> : renderRichText(title)}
        </h3>
        {meta}
      </div>
      {subtitle && (
        <p className="mt-0.5 text-[10pt] font-semibold text-subaccent">{renderRichText(subtitle)}</p>
      )}
      {description && (
        <p className="mt-2 text-[10pt] leading-relaxed text-ink/80">{renderRichText(description)}</p>
      )}
      <HighlightList items={highlights} />
      {children}
    </article>
  )
}
