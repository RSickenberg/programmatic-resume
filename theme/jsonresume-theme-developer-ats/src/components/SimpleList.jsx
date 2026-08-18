export default function SimpleList ({ children }) {
  return <div className="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 print:grid-cols-1 print:gap-y-3">{children}</div>
}
