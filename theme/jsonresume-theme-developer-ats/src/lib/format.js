import { safeUrl, getLinkRel } from '@jsonresume/utils/url'

export { safeUrl, getLinkRel }

/**
 * Reads the year/month straight off a JSON Resume date string (YYYY,
 * YYYY-MM or YYYY-MM-DD) instead of going through Date/Intl, which would
 * shift first-of-month dates a day back in timezones west of UTC.
 */
function yearMonth (dateStr) {
  const match = /^(\d{4})(?:-(\d{2}))?/.exec(dateStr)
  if (!match) return null
  const [, year, month] = match
  return `${month || '01'}.${year}`
}

/**
 * Uniform MM.YYYY / MM.YYYY - MM.YYYY formatting. Deliberately not the
 * locale-numeric Intl format (`MM/YYYY`): a bare slash reads, to a naive
 * ATS date parser, as ambiguous with other slash-separated tokens elsewhere
 * on the page (e.g. "PHP 7.4/8.0"), and adjacent entries sharing an exact
 * boundary date can get their ranges cross-attributed.
 */
export function formatDateRange ({ startDate, endDate, presentLabel = 'Present' }) {
  if (!startDate) return ''

  const start = yearMonth(startDate)
  if (!start) return ''

  if (endDate === undefined) return start

  const end = endDate ? yearMonth(endDate) : null

  return `${start} - ${end || presentLabel}`
}

export function displayUrl (url) {
  return url.replace(/^https?:\/\//, '').replace(/\/$/, '')
}

/**
 * Strips the leading internal module code ("100 - ", "226A - ") and
 * trailing period from a course entry, e.g. "100 - Prepare Data,
 * distinguish and evaluate." -> "Prepare Data, distinguish and evaluate".
 * The code is meaningless to an outside reader; dropping it keeps a long
 * coursework list scannable instead of cluttered with reference numbers.
 */
export function formatCourseName (course) {
  return course.replace(/^\S+\s*-\s*/, '').replace(/\.$/, '')
}

export function formatLocation (location = {}) {
  return [location.city, location.region, location.countryCode]
    .filter(Boolean)
    .join(', ')
}

export function cx (...classes) {
  return classes.filter(Boolean).join(' ')
}
