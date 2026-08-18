import Header from './components/Header.jsx'
import Summary from './components/Summary.jsx'
import WorkExperience from './components/WorkExperience.jsx'
import Skills from './components/Skills.jsx'
import Projects from './components/Projects.jsx'
import Education from './components/Education.jsx'
import Volunteer from './components/Volunteer.jsx'
import Awards from './components/Awards.jsx'
import Certificates from './components/Certificates.jsx'
import Publications from './components/Publications.jsx'
import Languages from './components/Languages.jsx'
import Interests from './components/Interests.jsx'
import References from './components/References.jsx'

export default function Resume ({ resume }) {
  const {
    basics = {},
    work = [],
    education = [],
    skills = [],
    projects = [],
    volunteer = [],
    awards = [],
    certificates = [],
    publications = [],
    languages = [],
    interests = [],
    references = [],
  } = resume

  return (
    <main
      className={[
        'mx-auto w-[210mm] min-h-[297mm] bg-white px-[16mm] py-[14mm] font-sans text-[10pt] leading-[1.5] text-ink shadow-lg',
        'print:m-0 print:w-auto print:min-h-0 print:p-0 print:shadow-none',
      ].join(' ')}
    >
      <Header basics={basics} />
      <Summary summary={basics.summary} />
      <WorkExperience work={work} />
      <Skills skills={skills} />
      <Education education={education} />
      <Projects projects={projects} />
      <Volunteer volunteer={volunteer} />
      <Awards awards={awards} />
      <Certificates certificates={certificates} />
      <Publications publications={publications} />
      <Languages languages={languages} />
      <Interests interests={interests} />
      <References references={references} />
    </main>
  )
}
