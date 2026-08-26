import Link from './Link.jsx'
import { displayUrl, formatLocation } from '../lib/format.js'
import { renderRichText } from '../lib/richText.jsx'

export default function Header ({ basics = {} }) {
  const { name, label, email, phone, url, location, profiles = [] } = basics
  const locationStr = formatLocation(location)

  return (
    <header className="mb-4 border-b border-border pb-3">
      {name && (
        <h1 className="font-mono text-[22pt] font-bold leading-none tracking-tight text-ink">{name}</h1>
      )}
      {label && (
        <p className="mt-1 font-mono text-[12pt] font-semibold text-accent">{renderRichText(label)}</p>
      )}
      <div className="mt-2 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[9pt] text-muted">
        {locationStr && <span>{locationStr}</span>}
        {email && <Link href={`mailto:${email}`} underline={false}>{email}</Link>}
        {phone && <Link href={`tel:${phone}`} underline={false}>{phone}</Link>}
        {url && <Link href={url} underline={false}>{displayUrl(url)}</Link>}
        {profiles.filter((profile) => profile.url).map((profile, index) => (
          <Link key={index} href={profile.url} underline={false}>{displayUrl(profile.url)}</Link>
        ))}
      </div>
    </header>
  )
}
