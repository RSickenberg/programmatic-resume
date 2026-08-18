import Link from './Link.jsx'

export default function SimpleItem ({ label, labelHref, meta, date, keywords }) {
  return (
    <div className="border-b border-border pb-3 last:border-b-0 last:pb-0 print:break-inside-avoid">
      <p className="text-[10pt] leading-relaxed text-ink/80">
        <span className="font-semibold text-ink">
          {labelHref ? <Link href={labelHref}>{label}</Link> : label}
        </span>
        {meta && <span> — {meta}</span>}
      </p>
      {date && <p className="mt-1 font-mono text-[8.5pt] text-subtle">{date}</p>}
      {keywords && keywords.length > 0 && (
        <ul className="mt-1.5 flex flex-wrap gap-1.5">
          {keywords.map((keyword, index) => (
            <li
              key={index}
              className="rounded-full border border-border px-2 py-0.5 font-mono text-[8pt] text-muted"
            >
              {keyword}
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}
