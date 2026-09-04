import Link from './Link.jsx'
import { displayUrl, formatLocation } from '../lib/format.js'
import { renderRichText } from '../lib/richText.jsx'

export default function Header ({ basics = {} }) {
  const { name, label, email, phone, url, location, profiles = [] } = basics
  const locationStr = formatLocation(location)

  const items = [
    locationStr && <span key="location">{locationStr}</span>,
    email && <Link key="email" href={`mailto:${email}`} underline={false}>{email}</Link>,
    phone && <Link key="phone" href={`tel:${phone}`} underline={false}>{phone}</Link>,
    url && <Link key="url" href={url} underline={false}>{displayUrl(url)}</Link>,
    ...profiles.filter((profile) => profile.url).map((profile, index) => (
      <Link key={`profile-${index}`} href={profile.url} underline={false}>{displayUrl(profile.url)}</Link>
    )),
  ].filter(Boolean)

  return (
    <header className="mb-4 border-b border-border pb-3">
      {name && (
        <h1 className="font-mono text-[22pt] font-bold leading-none tracking-tight text-ink">{name}</h1>
      )}
      {label && (
        <p className="mt-1 font-mono text-[12pt] font-semibold text-accent">{renderRichText(label)}</p>
      )}
      {/*
        * gap-x-3 alone is layout spacing, not text content: Chromium's PDF
        * text layer emits no glyph for it, so adjacent items glue together
        * for anything reading the raw content stream (e.g. an email and the
        * next field become one bogus token). A real "·" character between
        * items guarantees a byte in the text layer regardless of wrapping -
        * see DECISIONS.md for why we don't rely on space glyphs for this.
        */}
      <div className="mt-2 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[9pt] text-muted">
        {items.flatMap((item, index) => index === 0 ? [item] : [
          <span key={`sep-${index}`} aria-hidden="true">&middot;</span>,
          item,
        ])}
      </div>
    </header>
  )
}
