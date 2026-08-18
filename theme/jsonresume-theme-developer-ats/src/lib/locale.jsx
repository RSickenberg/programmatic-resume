import { createContext, useContext } from 'react'

const LocaleContext = createContext('en')

export function LocaleProvider ({ locale, children }) {
  return <LocaleContext.Provider value={locale}>{children}</LocaleContext.Provider>
}

export function useLocale () {
  return useContext(LocaleContext)
}
