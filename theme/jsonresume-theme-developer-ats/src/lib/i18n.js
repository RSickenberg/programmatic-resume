import { useLocale } from './locale.jsx'

const SECTION_TITLES = {
  en: {
    experience: 'Experience',
    skills: 'Skills',
    education: 'Education',
    projects: 'Projects',
    volunteer: 'Volunteer',
    awards: 'Awards',
    certificates: 'Certificates',
    publications: 'Publications',
    languages: 'Languages',
    interests: 'Interests',
    references: 'References',
  },
  fr: {
    experience: 'Expérience',
    skills: 'Compétences',
    education: 'Formation',
    projects: 'Projets',
    volunteer: 'Bénévolat',
    awards: 'Distinctions',
    certificates: 'Certifications',
    publications: 'Publications',
    languages: 'Langues',
    interests: "Centres d'intérêt",
    references: 'Références',
  },
}

const UI_STRINGS = {
  en: {
    present: 'Present',
    coursework: 'Relevant Coursework',
  },
  fr: {
    present: 'Présent',
    coursework: 'Cours pertinents',
  },
}

export function useSectionTitle (key) {
  const locale = useLocale()
  return SECTION_TITLES[locale]?.[key] ?? SECTION_TITLES.en[key]
}

export function useUiString (key) {
  const locale = useLocale()
  return UI_STRINGS[locale]?.[key] ?? UI_STRINGS.en[key]
}

export function usePresentLabel () {
  return useUiString('present')
}
