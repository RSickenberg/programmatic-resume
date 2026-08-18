import { cx } from '../lib/format.js'

export default function Section ({ title, children, className }) {
  return (
    <section className={cx('mb-6 last:mb-0', className)}>
      <h2 className="mb-3 border-b-2 border-accent pb-1 font-mono text-[11pt] font-bold uppercase tracking-widest text-muted print:break-after-avoid">
        {title}
      </h2>
      {children}
    </section>
  )
}
