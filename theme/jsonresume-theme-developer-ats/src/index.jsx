import { renderToStaticMarkup } from 'react-dom/server'
import Resume from './Resume.jsx'
import tailwindCss from './generated/tailwind.css?raw'

export function render (resume) {
  const html = renderToStaticMarkup(<Resume resume={resume} />)
  const title = resume.basics?.name ? `${resume.basics.name} - Resume` : 'Resume'

  return `<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>${title}</title>
<style>${tailwindCss}</style>
</head>
<body>${html}</body>
</html>`
}
