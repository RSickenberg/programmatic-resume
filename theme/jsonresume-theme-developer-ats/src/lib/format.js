import { safeUrl, getLinkRel } from '@jsonresume/utils/url'
import { formatDateRange } from '@jsonresume/utils/dates'

export { safeUrl, getLinkRel, formatDateRange }

export function displayUrl (url) {
  return url.replace(/^https?:\/\//, '').replace(/\/$/, '')
}

export function formatLocation (location = {}) {
  return [location.city, location.region, location.countryCode]
    .filter(Boolean)
    .join(', ')
}

export function cx (...classes) {
  return classes.filter(Boolean).join(' ')
}
