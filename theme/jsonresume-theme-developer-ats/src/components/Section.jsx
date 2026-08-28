import { cx } from '../lib/format.js'

/**
 * A section holds together on one page: its entries never straddle a page
 * boundary. Pass `breakable` for a section whose content cannot fit a single
 * page, where the constraint is impossible and the browser would drop it
 * anyway (work experience being the obvious one).
 *
 * The cost is deliberate: an atomic section that does not fit in the space
 * left moves whole, leaving a gap its own size behind it. Whole blocks are
 * worth more here than a tightly packed page.
 */
export default function Section ({ title, children, className, breakable = false }) {
  return (
    <section className={cx('mb-2.5 last:mb-0', !breakable && 'print:break-inside-avoid', className)}>
      <h2 className="mb-1.5 border-b-2 border-accent pb-0.5 font-mono text-[10.5pt] font-bold text-muted print:break-after-avoid">
        {title}
      </h2>
      {children}
    </section>
  )
}
