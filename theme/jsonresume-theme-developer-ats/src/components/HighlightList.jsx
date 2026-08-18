export default function HighlightList ({ items }) {
  if (!items || items.length === 0) return null

  return (
    <ul className="mt-2 list-outside list-disc space-y-1 pl-4 marker:text-accent">
      {items.map((item, index) => (
        <li key={index} className="pl-0.5 text-[10pt] leading-relaxed text-ink/80">
          {item}
        </li>
      ))}
    </ul>
  )
}
