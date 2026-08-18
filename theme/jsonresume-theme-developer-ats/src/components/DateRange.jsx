import { formatDateRange, cx } from '../lib/format.js'

export default function DateRange ({ startDate, endDate, className }) {
  const formatted = formatDateRange({ startDate, endDate })

  if (!formatted) return null

  return (
    <span className={ cx('whitespace-nowrap font-mono text-[8.5pt] font-medium text-subtle', className) }>
      { formatted }
    </span>
  )
}
