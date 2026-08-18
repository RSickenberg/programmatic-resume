import { safeUrl, getLinkRel, cx } from '../lib/format.js'

export default function Link ({ href, children, className, underline = true, ...rest }) {
  const safeHref = safeUrl(href)

  if (!safeHref) {
    return <span className={className}>{children}</span>
  }

  const isExternal = /^https?:/i.test(safeHref)

  return (
    <a
      href={safeHref}
      className={cx(
        'text-accent',
        underline && 'underline decoration-accent/30 underline-offset-2 hover:decoration-accent print:decoration-ink/40',
        className
      )}
      {...(isExternal ? { target: '_blank', rel: getLinkRel(safeHref, true) } : {})}
      {...rest}
    >
      {children}
    </a>
  )
}
