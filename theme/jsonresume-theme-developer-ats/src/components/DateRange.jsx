import { formatDateRange, cx } from '../lib/format.js'
import { usePresentLabel } from '../lib/i18n.js'

export default function DateRange ({ startDate, endDate, className }) {
  const presentLabel = usePresentLabel()
  const formatted = formatDateRange({ startDate, endDate, presentLabel })

  if (!formatted) return null

  return (
    <span className={ cx('whitespace-nowrap font-mono text-[8.5pt] font-medium text-subtle', className) }>
      { formatted }
    </span>
  )
}
