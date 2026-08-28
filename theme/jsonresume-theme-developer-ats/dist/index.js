import { renderToStaticMarkup } from "react-dom/server";
import { jsx, jsxs } from "react/jsx-runtime";
import { createContext, useContext } from "react";
//#region node_modules/@jsonresume/utils/src/url.js
/**
* URL safety utilities for JSON Resume.
*
* Framework-free. Prevents XSS attacks and ensures safe URL handling.
*
* @module @jsonresume/utils/url
*/
/**
* Sanitizes URLs to prevent XSS attacks
* - Blocks javascript:, data:, vbscript: schemes
* - Allows http:, https:, mailto:, tel: schemes
* - Returns null for invalid/dangerous URLs
*
* @param {string} url - The URL to sanitize
* @returns {string|null} - Safe URL or null if dangerous
*
* @example
* safeUrl('https://example.com') // 'https://example.com'
* safeUrl('javascript:alert(1)') // null
* safeUrl('mailto:user@example.com') // 'mailto:user@example.com'
*/
function safeUrl(url) {
	if (!url || typeof url !== "string") return null;
	const trimmed = url.trim();
	if (/^(javascript|data|vbscript|file|about):/i.test(trimmed)) return null;
	if (/^(https?|mailto|tel|sms|ftp):/i.test(trimmed)) return trimmed;
	if (trimmed.startsWith("/") || trimmed.startsWith(".")) return trimmed;
	if (/^www\./i.test(trimmed)) return `https://${trimmed}`;
	if (/^[a-z0-9][a-z0-9.-]+\.[a-z]{2,}$/i.test(trimmed)) return `https://${trimmed}`;
	return trimmed;
}
/**
* Returns proper rel attribute for external links
* Adds security attributes for links opening in new windows
*
* @param {string} url - The URL to check
* @param {boolean} [openInNewTab=false] - Whether link opens in new tab
* @returns {string} - rel attribute value
*
* @example
* getLinkRel('https://example.com', true) // 'noopener noreferrer'
* getLinkRel('mailto:user@example.com', false) // ''
*/
function getLinkRel(url, openInNewTab = false) {
	if (!url || typeof url !== "string") return "";
	if (openInNewTab && /^https?:/i.test(url)) return "noopener noreferrer";
	return "";
}
//#endregion
//#region src/lib/format.js
/**
* Reads the year/month straight off a JSON Resume date string (YYYY,
* YYYY-MM or YYYY-MM-DD) instead of going through Date/Intl, which would
* shift first-of-month dates a day back in timezones west of UTC.
*/
function yearMonth(dateStr) {
	const match = /^(\d{4})(?:-(\d{2}))?/.exec(dateStr);
	if (!match) return null;
	const [, year, month] = match;
	return `${month || "01"}.${year}`;
}
/**
* Uniform MM.YYYY / MM.YYYY - MM.YYYY formatting. Deliberately not the
* locale-numeric Intl format (`MM/YYYY`): a bare slash reads, to a naive
* ATS date parser, as ambiguous with other slash-separated tokens elsewhere
* on the page (e.g. "PHP 7.4/8.0"), and adjacent entries sharing an exact
* boundary date can get their ranges cross-attributed.
*/
function formatDateRange({ startDate, endDate, presentLabel = "Present" }) {
	if (!startDate) return "";
	const start = yearMonth(startDate);
	if (!start) return "";
	if (endDate === void 0) return start;
	return `${start} - ${(endDate ? yearMonth(endDate) : null) || presentLabel}`;
}
function displayUrl(url) {
	return url.replace(/^https?:\/\//, "").replace(/\/$/, "");
}
function formatLocation(location = {}) {
	return [
		location.city,
		location.region,
		location.countryCode
	].filter(Boolean).join(", ");
}
function cx(...classes) {
	return classes.filter(Boolean).join(" ");
}
//#endregion
//#region src/components/Link.jsx
function Link({ href, children, className, underline = true, ...rest }) {
	const safeHref = safeUrl(href);
	if (!safeHref) return /* @__PURE__ */ jsx("span", {
		className,
		children
	});
	const isExternal = /^https?:/i.test(safeHref);
	return /* @__PURE__ */ jsx("a", {
		href: safeHref,
		className: cx("text-accent", underline && "underline decoration-accent/30 underline-offset-2 hover:decoration-accent print:decoration-ink/40", className),
		...isExternal ? {
			target: "_blank",
			rel: getLinkRel(safeHref, true)
		} : {},
		...rest,
		children
	});
}
//#endregion
//#region src/lib/richText.jsx
/**
* Renders `**word**` as bold, a minimal Markdown-style convention so resume
* content (translations) can emphasize a word without the theme needing to
* know why — e.g. `education.courses.*` or `basics.summary_role` keys.
*
* A bolded phrase is always a single named term ("LHC Lausanne", "GitHub
* Actions CI/CD"), never a full clause, so its internal spaces are replaced
* with non-breaking spaces: Chromium's PDF text layer silently drops the
* glyph for a regular space that lands exactly on a visual line-wrap, which
* glues the two halves of the term together for any parser reading the raw
* content stream. A non-breaking space can never be a wrap point, so the
* term either fits or moves to the next line whole.
*/
function renderRichText(text) {
	if (typeof text !== "string" || !text.includes("**")) return text;
	return text.split(/\*\*(.+?)\*\*/g).map((part, index) => index % 2 === 1 ? /* @__PURE__ */ jsx("strong", {
		className: "font-semibold text-ink",
		children: part.replace(/ /g, "\xA0")
	}, index) : part);
}
//#endregion
//#region src/components/Header.jsx
function Header({ basics = {} }) {
	const { name, label, email, phone, url, location, profiles = [] } = basics;
	const locationStr = formatLocation(location);
	return /* @__PURE__ */ jsxs("header", {
		className: "mb-4 border-b border-border pb-3",
		children: [
			name && /* @__PURE__ */ jsx("h1", {
				className: "font-mono text-[22pt] font-bold leading-none tracking-tight text-ink",
				children: name
			}),
			label && /* @__PURE__ */ jsx("p", {
				className: "mt-1 font-mono text-[12pt] font-semibold text-accent",
				children: renderRichText(label)
			}),
			/* @__PURE__ */ jsxs("div", {
				className: "mt-2 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[9pt] text-muted",
				children: [
					locationStr && /* @__PURE__ */ jsx("span", { children: locationStr }),
					email && /* @__PURE__ */ jsx(Link, {
						href: `mailto:${email}`,
						underline: false,
						children: email
					}),
					phone && /* @__PURE__ */ jsx(Link, {
						href: `tel:${phone}`,
						underline: false,
						children: phone
					}),
					url && /* @__PURE__ */ jsx(Link, {
						href: url,
						underline: false,
						children: displayUrl(url)
					}),
					profiles.filter((profile) => profile.url).map((profile, index) => /* @__PURE__ */ jsx(Link, {
						href: profile.url,
						underline: false,
						children: displayUrl(profile.url)
					}, index))
				]
			})
		]
	});
}
//#endregion
//#region src/components/Summary.jsx
function Summary({ summary }) {
	if (!summary) return null;
	return /* @__PURE__ */ jsx("section", {
		className: "mb-3 print:break-inside-avoid",
		children: /* @__PURE__ */ jsx("p", {
			className: "text-[10pt] leading-snug whitespace-pre-line text-ink/80",
			children: renderRichText(summary)
		})
	});
}
//#endregion
//#region src/components/Section.jsx
/**
* A section holds together on one page: its entries never straddle a page
* boundary. Pass `breakable` for a section whose content cannot fit a single
* page, where the constraint is impossible and the browser would drop it
* anyway (work experience being the obvious one).
*
* The cost is deliberate: an atomic section that does not fit in the space
* left moves whole, leaving a gap its own size behind it. Whole blocks are
* worth more here than a tightly packed page.
*/
function Section({ title, children, className, breakable = false }) {
	return /* @__PURE__ */ jsxs("section", {
		className: cx("mb-2.5 last:mb-0", !breakable && "print:break-inside-avoid", className),
		children: [/* @__PURE__ */ jsx("h2", {
			className: "mb-1.5 border-b-2 border-accent pb-0.5 font-mono text-[10.5pt] font-bold uppercase tracking-widest text-muted print:break-after-avoid",
			children: title
		}), children]
	});
}
//#endregion
//#region src/components/HighlightList.jsx
/**
* A highlight may carry its own right-aligned meta (typically a date range) by
* ending with `|| <meta>`. Used by grouped entries that bundle several dated
* items under one heading, where a single entry-level date range would be
* misleading.
*
* The row does NOT wrap: `flex-wrap` would drop the date onto a line of its own
* as soon as the text grew too long, which is what happens in French. Instead
* the text shrinks (`min-w-0`) and wraps inside its own column while the date
* holds its place on the first line (`shrink-0`).
*/
function splitMeta(item) {
	if (typeof item !== "string") return [item, null];
	const index = item.lastIndexOf("||");
	if (index === -1) return [item, null];
	return [item.slice(0, index).trim(), item.slice(index + 2).trim()];
}
function HighlightList({ items, continuation = false }) {
	if (!items || items.length === 0) return null;
	return /* @__PURE__ */ jsx("ul", {
		className: `${continuation ? "mt-0.5" : "mt-1.5"} list-outside list-disc space-y-0.5 pl-4 marker:text-accent`,
		children: items.map((item, index) => {
			const [text, meta] = splitMeta(item);
			return /* @__PURE__ */ jsx("li", {
				className: "pl-0.5 text-[10pt] leading-snug text-ink/80 print:break-inside-avoid",
				children: meta ? /* @__PURE__ */ jsxs("span", {
					className: "flex items-baseline justify-between gap-x-4",
					children: [/* @__PURE__ */ jsx("span", {
						className: "min-w-0",
						children: renderRichText(text)
					}), /* @__PURE__ */ jsx("span", {
						className: "shrink-0 whitespace-nowrap font-mono text-[8.5pt] font-medium text-subtle",
						children: meta
					})]
				}) : renderRichText(text)
			}, index);
		})
	});
}
//#endregion
//#region src/components/Entry.jsx
/**
* The separating rule is a TOP border, not a bottom one. CSS has no selector
* for "first element on a page", so a rule attached to an entry renders
* wherever that entry lands. Attached to the bottom, it strands a dangling
* line at the foot of a page with nothing beneath it; attached to the top, it
* travels with the entry it introduces and reads as a deliberate rule instead.
*
* The exclusion uses :first-of-type, not :first-child - Section renders its
* <h2> first, so the leading <article> is :nth-child(2) and :first-child would
* never match it, printing a stray rule directly under the section heading.
*
* An entry short enough to fit a page is never split. Only a long one may
* break, and then only at a controlled point: its heading always travels with
* the first two highlights, and at least three follow, so a page break can
* never strand a lone bullet or an orphaned heading.
*
* `children` sits outside the unbreakable group and sets its own break rules.
* Held inside it, a long child (Education's coursework) turned the whole entry
* into one large atom, and an atom that does not fit leaves a gap its own size
* at the foot of the page. See Education.jsx for how it stays attached to its
* heading without becoming unbreakable.
*/
var SPLIT_MIN_ITEMS = 5;
var KEEP_WITH_HEADING = 2;
function Entry({ title, titleHref, meta, subtitle, description, highlights, children, className }) {
	const items = highlights ?? [];
	const splits = items.length >= SPLIT_MIN_ITEMS;
	const withHeading = splits ? items.slice(0, KEEP_WITH_HEADING) : items;
	const rest = splits ? items.slice(KEEP_WITH_HEADING) : [];
	return /* @__PURE__ */ jsxs("article", {
		className: cx("mt-1.5 border-t border-border pt-1.5 [&:first-of-type]:mt-0 [&:first-of-type]:border-t-0 [&:first-of-type]:pt-0", className),
		children: [
			/* @__PURE__ */ jsxs("div", {
				className: "print:break-inside-avoid",
				children: [
					/* @__PURE__ */ jsxs("div", {
						className: "flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1",
						children: [/* @__PURE__ */ jsx("h3", {
							className: "font-mono text-[12pt] font-semibold text-ink",
							children: titleHref ? /* @__PURE__ */ jsx(Link, {
								href: titleHref,
								underline: false,
								children: title
							}) : renderRichText(title)
						}), meta]
					}),
					subtitle && /* @__PURE__ */ jsx("p", {
						className: "mt-0.5 text-[10pt] font-semibold text-subaccent",
						children: renderRichText(subtitle)
					}),
					description && /* @__PURE__ */ jsx("p", {
						className: "mt-1 text-[10pt] leading-snug text-ink/80",
						children: renderRichText(description)
					}),
					/* @__PURE__ */ jsx(HighlightList, { items: withHeading })
				]
			}),
			rest.length > 0 && /* @__PURE__ */ jsx(HighlightList, {
				items: rest,
				continuation: true
			}),
			children
		]
	});
}
//#endregion
//#region src/lib/locale.jsx
var LocaleContext = createContext("en");
function LocaleProvider({ locale, children }) {
	return /* @__PURE__ */ jsx(LocaleContext.Provider, {
		value: locale,
		children
	});
}
function useLocale() {
	return useContext(LocaleContext);
}
//#endregion
//#region src/lib/i18n.js
var SECTION_TITLES = {
	en: {
		experience: "Experience",
		skills: "Skills",
		education: "Education",
		projects: "Projects",
		volunteer: "Volunteer",
		awards: "Awards",
		certificates: "Certificates",
		publications: "Publications",
		languages: "Languages",
		interests: "Interests",
		references: "References"
	},
	fr: {
		experience: "Expérience",
		skills: "Compétences",
		education: "Formation",
		projects: "Projets",
		volunteer: "Bénévolat",
		awards: "Distinctions",
		certificates: "Certifications",
		publications: "Publications",
		languages: "Langues",
		interests: "Centres d'intérêt",
		references: "Références"
	}
};
var UI_STRINGS = {
	en: {
		present: "Present",
		coursework: "Relevant Coursework",
		in: "in",
		gpa: "GPA"
	},
	fr: {
		present: "Présent",
		coursework: "Cours pertinents",
		in: "dans",
		gpa: "Moyenne"
	}
};
function useSectionTitle(key) {
	return SECTION_TITLES[useLocale()]?.[key] ?? SECTION_TITLES.en[key];
}
function useUiString(key) {
	return UI_STRINGS[useLocale()]?.[key] ?? UI_STRINGS.en[key];
}
function usePresentLabel() {
	return useUiString("present");
}
//#endregion
//#region src/components/DateRange.jsx
function DateRange({ startDate, endDate, className }) {
	const formatted = formatDateRange({
		startDate,
		endDate,
		presentLabel: usePresentLabel()
	});
	if (!formatted) return null;
	return /* @__PURE__ */ jsx("span", {
		className: cx("whitespace-nowrap font-mono text-[8.5pt] font-medium text-subtle", className),
		children: formatted
	});
}
//#endregion
//#region src/components/WorkExperience.jsx
function WorkExperience({ work = [] }) {
	const title = useSectionTitle("experience");
	if (work.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		breakable: true,
		children: work.map((job, index) => /* @__PURE__ */ jsx(Entry, {
			title: job.position || job.name,
			subtitle: job.name,
			meta: /* @__PURE__ */ jsx(DateRange, {
				startDate: job.startDate,
				endDate: job.endDate
			}),
			description: job.summary,
			highlights: job.highlights
		}, index))
	});
}
//#endregion
//#region src/components/Skills.jsx
function Skills({ skills = [] }) {
	const title = useSectionTitle("skills");
	if (skills.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx("div", {
			className: "grid grid-cols-1 gap-x-5 gap-y-2.5 sm:grid-cols-3",
			children: skills.map((skill, index) => /* @__PURE__ */ jsxs("div", {
				className: "flex flex-col print:break-inside-avoid",
				children: [/* @__PURE__ */ jsx("h3", {
					className: "mb-1 font-mono text-[9pt] font-bold uppercase tracking-wider text-accent",
					children: renderRichText(skill.name)
				}), skill.keywords && skill.keywords.length > 0 && /* @__PURE__ */ jsx("p", {
					className: "flex-1 rounded border-l-2 border-accent bg-surface px-2.5 py-1.5 font-mono text-[9pt] leading-snug text-muted",
					children: renderRichText(skill.keywords.map((keyword) => keyword.replace(/ /g, "\xA0")).join(", "))
				})]
			}, index))
		})
	});
}
//#endregion
//#region src/components/Projects.jsx
function Projects({ projects = [] }) {
	const title = useSectionTitle("projects");
	if (projects.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: projects.map((project, index) => /* @__PURE__ */ jsx(Entry, {
			title: project.name,
			titleHref: project.url,
			meta: (project.startDate || project.endDate) && /* @__PURE__ */ jsx(DateRange, {
				startDate: project.startDate,
				endDate: project.endDate
			}),
			description: project.description,
			highlights: project.highlights
		}, index))
	});
}
//#endregion
//#region src/components/Education.jsx
function Education({ education = [] }) {
	const title = useSectionTitle("education");
	const courseworkLabel = useUiString("coursework");
	const connector = useUiString("in");
	const gpaLabel = useUiString("gpa");
	if (education.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: education.map((edu, index) => /* @__PURE__ */ jsx(Entry, {
			title: edu.institution,
			titleHref: edu.url,
			subtitle: edu.studyType && edu.area ? `${edu.studyType} ${connector} ${edu.area}` : edu.studyType || edu.area,
			meta: /* @__PURE__ */ jsx(DateRange, {
				startDate: edu.startDate,
				endDate: edu.endDate
			}),
			description: edu.score ? `${gpaLabel}: ${edu.score}` : void 0,
			children: edu.courses && edu.courses.length > 0 && /* @__PURE__ */ jsxs("div", {
				className: "mt-2 print:break-before-avoid",
				children: [/* @__PURE__ */ jsx("p", {
					className: "text-[8pt] font-semibold uppercase tracking-wide text-subtle print:break-after-avoid",
					children: courseworkLabel
				}), /* @__PURE__ */ jsx("p", {
					className: "mt-1 text-[8pt] leading-snug text-ink/70",
					children: renderRichText(edu.courses.join(", "))
				})]
			})
		}, index))
	});
}
//#endregion
//#region src/components/Volunteer.jsx
function Volunteer({ volunteer = [] }) {
	const title = useSectionTitle("volunteer");
	if (volunteer.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: volunteer.map((vol, index) => /* @__PURE__ */ jsx(Entry, {
			title: vol.position,
			subtitle: vol.organization,
			meta: /* @__PURE__ */ jsx(DateRange, {
				startDate: vol.startDate,
				endDate: vol.endDate
			}),
			description: vol.summary,
			highlights: vol.highlights
		}, index))
	});
}
//#endregion
//#region src/components/SimpleList.jsx
function SimpleList({ children }) {
	return /* @__PURE__ */ jsx("div", {
		className: "grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 print:grid-cols-1 print:gap-y-3 print:break-inside-avoid",
		children
	});
}
//#endregion
//#region src/components/Awards.jsx
function Awards({ awards = [] }) {
	const title = useSectionTitle("awards");
	if (awards.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx(SimpleList, { children: awards.map((award, index) => /* @__PURE__ */ jsx(Entry, {
			title: award.title,
			subtitle: award.awarder,
			meta: /* @__PURE__ */ jsx(DateRange, {
				startDate: award.date,
				endDate: award.date
			}),
			description: award.summary
		}, index)) })
	});
}
//#endregion
//#region src/components/SimpleItem.jsx
function SimpleItem({ label, labelHref, meta, date, keywords }) {
	return /* @__PURE__ */ jsxs("div", {
		className: "border-b border-border pb-3 last:border-b-0 last:pb-0 print:break-inside-avoid",
		children: [
			/* @__PURE__ */ jsxs("p", {
				className: "text-[10pt] leading-relaxed text-ink/80",
				children: [/* @__PURE__ */ jsx("span", {
					className: "font-semibold text-ink",
					children: labelHref ? /* @__PURE__ */ jsx(Link, {
						href: labelHref,
						children: label
					}) : renderRichText(label)
				}), meta && /* @__PURE__ */ jsxs("span", { children: [" — ", renderRichText(meta)] })]
			}),
			date && /* @__PURE__ */ jsx("p", {
				className: "mt-1 font-mono text-[8.5pt] text-subtle",
				children: date
			}),
			keywords && keywords.length > 0 && /* @__PURE__ */ jsx("ul", {
				className: "mt-1.5 flex flex-wrap gap-1.5",
				children: keywords.map((keyword, index) => /* @__PURE__ */ jsx("li", {
					className: "rounded-full border border-accent px-2 py-0.5 font-mono text-[8pt] text-muted",
					children: renderRichText(keyword)
				}, index))
			})
		]
	});
}
//#endregion
//#region src/components/Certificates.jsx
function Certificates({ certificates = [] }) {
	const title = useSectionTitle("certificates");
	if (certificates.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx(SimpleList, { children: certificates.map((cert, index) => /* @__PURE__ */ jsx(SimpleItem, {
			label: cert.name,
			labelHref: cert.url,
			meta: cert.issuer,
			date: cert.date
		}, index)) })
	});
}
//#endregion
//#region src/components/Publications.jsx
function Publications({ publications = [] }) {
	const title = useSectionTitle("publications");
	if (publications.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx(SimpleList, { children: publications.map((pub, index) => /* @__PURE__ */ jsx(SimpleItem, {
			label: pub.name,
			labelHref: pub.url,
			meta: pub.publisher,
			date: pub.releaseDate
		}, index)) })
	});
}
//#endregion
//#region src/components/Languages.jsx
/**
* Rendered as a single wrapped line rather than the stacked SimpleList: three
* languages taking three full rows cost more vertical space than they earn.
*/
function Languages({ languages = [] }) {
	const title = useSectionTitle("languages");
	if (languages.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx("p", {
			className: "text-[10pt] leading-snug text-ink/80",
			children: languages.map((lang, index) => /* @__PURE__ */ jsxs("span", { children: [
				index > 0 && /* @__PURE__ */ jsx("span", {
					className: "text-subtle",
					children: " · "
				}),
				/* @__PURE__ */ jsx("span", {
					className: "font-semibold text-ink",
					children: renderRichText(lang.language)
				}),
				lang.fluency && /* @__PURE__ */ jsxs("span", { children: [": ", renderRichText(lang.fluency)] })
			] }, index))
		})
	});
}
//#endregion
//#region src/components/Interests.jsx
function Interests({ interests = [] }) {
	const title = useSectionTitle("interests");
	if (interests.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: /* @__PURE__ */ jsx(SimpleList, { children: interests.map((interest, index) => /* @__PURE__ */ jsx(SimpleItem, {
			label: interest.name,
			keywords: interest.keywords
		}, index)) })
	});
}
//#endregion
//#region src/components/References.jsx
function References({ references = [] }) {
	const title = useSectionTitle("references");
	if (references.length === 0) return null;
	return /* @__PURE__ */ jsx(Section, {
		title,
		children: references.map((ref, index) => /* @__PURE__ */ jsx(Entry, {
			title: ref.name,
			description: ref.reference
		}, index))
	});
}
//#endregion
//#region src/Resume.jsx
function Resume({ resume, locale = "en" }) {
	const { basics = {}, work = [], education = [], skills = [], projects = [], volunteer = [], awards = [], certificates = [], publications = [], languages = [], interests = [], references = [] } = resume;
	return /* @__PURE__ */ jsx(LocaleProvider, {
		locale,
		children: /* @__PURE__ */ jsxs("main", {
			className: ["mx-auto w-[210mm] min-h-[297mm] bg-white px-[13mm] py-[10mm] font-sans text-[10pt] leading-[1.4] text-ink shadow-lg", "print:m-0 print:w-auto print:min-h-0 print:p-0 print:shadow-none"].join(" "),
			children: [
				/* @__PURE__ */ jsx(Header, { basics }),
				/* @__PURE__ */ jsx(Summary, { summary: basics.summary }),
				/* @__PURE__ */ jsx(WorkExperience, { work }),
				/* @__PURE__ */ jsx(Skills, { skills }),
				/* @__PURE__ */ jsx(Education, { education }),
				/* @__PURE__ */ jsx(Projects, { projects }),
				/* @__PURE__ */ jsx(Volunteer, { volunteer }),
				/* @__PURE__ */ jsx(Awards, { awards }),
				/* @__PURE__ */ jsx(Certificates, { certificates }),
				/* @__PURE__ */ jsx(Publications, { publications }),
				/* @__PURE__ */ jsx(Languages, { languages }),
				/* @__PURE__ */ jsx(Interests, { interests }),
				/* @__PURE__ */ jsx(References, { references })
			]
		})
	});
}
//#endregion
//#region src/generated/tailwind.css?raw
var tailwind_default = "/*! tailwindcss v4.3.3 | MIT License | https://tailwindcss.com */\n@layer properties{@supports (((-webkit-hyphens:none)) and (not (margin-trim:inline))) or ((-moz-orient:inline) and (not (color:rgb(from red r g b)))){*,:before,:after,::backdrop{--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-y-reverse:0;--tw-border-style:solid;--tw-leading:initial;--tw-font-weight:initial;--tw-tracking:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-alpha:100%;--tw-drop-shadow-size:initial}}}@layer theme{:root,:host{--font-sans:Inter, \"Segoe UI\", ui-sans-serif, system-ui, -apple-system, sans-serif;--font-mono:\"JetBrains Mono\", ui-monospace, SFMono-Regular, Menlo, Consolas, \"Liberation Mono\", monospace;--color-white:#fff;--spacing:.25rem;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--tracking-tight:-.025em;--tracking-wide:.025em;--tracking-wider:.05em;--tracking-widest:.1em;--leading-snug:1.375;--leading-relaxed:1.625;--default-font-family:var(--font-sans);--default-mono-font-family:var(--font-mono);--color-ink:#18181b;--color-muted:#52525b;--color-subtle:#71717a;--color-accent:#2563eb;--color-subaccent:#092564;--color-border:#e4e4e7;--color-surface:#f7f8fa}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}::file-selector-button{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;tab-size:4;line-height:1.5;font-family:var(--default-font-family,-apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", \"Noto Sans\", Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);-webkit-tap-highlight-color:transparent}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;-webkit-text-decoration:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--default-mono-font-family,ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, \"Liberation Mono\", \"Courier New\", monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-variation-settings:var(--default-mono-font-variation-settings,normal);font-size:1em}small{font-size:80%}sub,sup{vertical-align:baseline;font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}:-moz-focusring:where(:not(iframe)){outline:auto}progress{vertical-align:baseline}summary{display:list-item}ol,ul,menu{list-style:none}img,svg,video,canvas,audio,iframe,embed,object{vertical-align:middle;display:block}img,video{max-width:100%;height:auto}button,input,select,optgroup,textarea{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}::file-selector-button{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1}@supports (not ((-webkit-appearance:-apple-pay-button))) or (contain-intrinsic-size:1px){::placeholder{color:currentColor}@supports (color:color-mix(in lab, red, red)){::placeholder{color:color-mix(in oklab, currentcolor 50%, transparent)}}}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit{padding-block:0}::-webkit-datetime-edit-year-field{padding-block:0}::-webkit-datetime-edit-month-field{padding-block:0}::-webkit-datetime-edit-day-field{padding-block:0}::-webkit-datetime-edit-hour-field{padding-block:0}::-webkit-datetime-edit-minute-field{padding-block:0}::-webkit-datetime-edit-second-field{padding-block:0}::-webkit-datetime-edit-millisecond-field{padding-block:0}::-webkit-datetime-edit-meridiem-field{padding-block:0}::-webkit-calendar-picker-indicator{line-height:1}:-moz-ui-invalid{box-shadow:none}button,input:where([type=button],[type=reset],[type=submit]){appearance:button}::file-selector-button{appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}*,:before,:after{box-sizing:border-box}html,body{background:#fff;margin:0;padding:0}body{-webkit-font-smoothing:antialiased}h1,h2,h3,h4{break-after:avoid}a{color:inherit}@page{size:A4;margin:10mm 13mm}@media print{html,body{-webkit-print-color-adjust:exact;print-color-adjust:exact}a[href]:after{content:none}}}@layer components;@layer utilities{.static{position:static}.mx-auto{margin-inline:auto}.mt-0{margin-top:0}.mt-0\\.5{margin-top:calc(var(--spacing) * .5)}.mt-1{margin-top:var(--spacing)}.mt-1\\.5{margin-top:calc(var(--spacing) * 1.5)}.mt-2{margin-top:calc(var(--spacing) * 2)}.mt-3{margin-top:calc(var(--spacing) * 3)}.mb-1{margin-bottom:var(--spacing)}.mb-1\\.5{margin-bottom:calc(var(--spacing) * 1.5)}.mb-2{margin-bottom:calc(var(--spacing) * 2)}.mb-2\\.5{margin-bottom:calc(var(--spacing) * 2.5)}.mb-3{margin-bottom:calc(var(--spacing) * 3)}.mb-4{margin-bottom:calc(var(--spacing) * 4)}.mb-6{margin-bottom:calc(var(--spacing) * 6)}.block{display:block}.flex{display:flex}.grid{display:grid}.inline{display:inline}.table{display:table}.min-h-\\[297mm\\]{min-height:297mm}.w-\\[210mm\\]{width:210mm}.min-w-0{min-width:0}.flex-1{flex:1}.shrink{flex-shrink:1}.shrink-0{flex-shrink:0}.grow{flex-grow:1}.transform{transform:var(--tw-rotate-x,) var(--tw-rotate-y,) var(--tw-rotate-z,) var(--tw-skew-x,) var(--tw-skew-y,)}.list-outside{list-style-position:outside}.list-disc{list-style-type:disc}.columns-2{columns:2}.columns-3{columns:3}.break-before-avoid{break-before:avoid}.break-after-avoid{break-after:avoid}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.flex-col{flex-direction:column}.flex-wrap{flex-wrap:wrap}.items-baseline{align-items:baseline}.items-center{align-items:center}.justify-between{justify-content:space-between}.gap-1{gap:var(--spacing)}.gap-1\\.5{gap:calc(var(--spacing) * 1.5)}:where(.space-y-0>:not(:last-child)){--tw-space-y-reverse:0;margin-block:0}:where(.space-y-0\\.5>:not(:last-child)){--tw-space-y-reverse:0;margin-block-start:calc(calc(var(--spacing) * .5) * var(--tw-space-y-reverse));margin-block-end:calc(calc(var(--spacing) * .5) * calc(1 - var(--tw-space-y-reverse)))}.gap-x-3{column-gap:calc(var(--spacing) * 3)}.gap-x-4{column-gap:calc(var(--spacing) * 4)}.gap-x-5{column-gap:calc(var(--spacing) * 5)}.gap-x-6{column-gap:calc(var(--spacing) * 6)}.gap-y-0{row-gap:0}.gap-y-0\\.5{row-gap:calc(var(--spacing) * .5)}.gap-y-1{row-gap:var(--spacing)}.gap-y-2{row-gap:calc(var(--spacing) * 2)}.gap-y-2\\.5{row-gap:calc(var(--spacing) * 2.5)}.gap-y-4{row-gap:calc(var(--spacing) * 4)}.rounded{border-radius:.25rem}.rounded-full{border-radius:3.40282e38px}.border{border-style:var(--tw-border-style);border-width:1px}.border-t{border-top-style:var(--tw-border-style);border-top-width:1px}.border-b{border-bottom-style:var(--tw-border-style);border-bottom-width:1px}.border-b-2{border-bottom-style:var(--tw-border-style);border-bottom-width:2px}.border-l-2{border-left-style:var(--tw-border-style);border-left-width:2px}.border-accent{border-color:var(--color-accent)}.border-border{border-color:var(--color-border)}.border-subaccent{border-color:var(--color-subaccent)}.bg-surface{background-color:var(--color-surface)}.bg-white{background-color:var(--color-white)}.px-2{padding-inline:calc(var(--spacing) * 2)}.px-2\\.5{padding-inline:calc(var(--spacing) * 2.5)}.px-3{padding-inline:calc(var(--spacing) * 3)}.px-\\[13mm\\]{padding-inline:13mm}.py-0{padding-block:0}.py-0\\.5{padding-block:calc(var(--spacing) * .5)}.py-1{padding-block:var(--spacing)}.py-1\\.5{padding-block:calc(var(--spacing) * 1.5)}.py-2{padding-block:calc(var(--spacing) * 2)}.py-\\[10mm\\]{padding-block:10mm}.pt-1{padding-top:var(--spacing)}.pt-1\\.5{padding-top:calc(var(--spacing) * 1.5)}.pb-0{padding-bottom:0}.pb-0\\.5{padding-bottom:calc(var(--spacing) * .5)}.pb-1{padding-bottom:var(--spacing)}.pb-2{padding-bottom:calc(var(--spacing) * 2)}.pb-3{padding-bottom:calc(var(--spacing) * 3)}.pb-4{padding-bottom:calc(var(--spacing) * 4)}.pl-0{padding-left:0}.pl-0\\.5{padding-left:calc(var(--spacing) * .5)}.pl-4{padding-left:calc(var(--spacing) * 4)}.font-mono{font-family:var(--font-mono)}.font-sans{font-family:var(--font-sans)}.text-\\[8\\.5pt\\]{font-size:8.5pt}.text-\\[8pt\\]{font-size:8pt}.text-\\[9pt\\]{font-size:9pt}.text-\\[10\\.5pt\\]{font-size:10.5pt}.text-\\[10pt\\]{font-size:10pt}.text-\\[12pt\\]{font-size:12pt}.text-\\[22pt\\]{font-size:22pt}.leading-\\[1\\.4\\]{--tw-leading:1.4;line-height:1.4}.leading-none{--tw-leading:1;line-height:1}.leading-relaxed{--tw-leading:var(--leading-relaxed);line-height:var(--leading-relaxed)}.leading-snug{--tw-leading:var(--leading-snug);line-height:var(--leading-snug)}.font-bold{--tw-font-weight:var(--font-weight-bold);font-weight:var(--font-weight-bold)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.font-semibold{--tw-font-weight:var(--font-weight-semibold);font-weight:var(--font-weight-semibold)}.tracking-tight{--tw-tracking:var(--tracking-tight);letter-spacing:var(--tracking-tight)}.tracking-wide{--tw-tracking:var(--tracking-wide);letter-spacing:var(--tracking-wide)}.tracking-wider{--tw-tracking:var(--tracking-wider);letter-spacing:var(--tracking-wider)}.tracking-widest{--tw-tracking:var(--tracking-widest);letter-spacing:var(--tracking-widest)}.whitespace-nowrap{white-space:nowrap}.whitespace-pre-line{white-space:pre-line}.text-accent{color:var(--color-accent)}.text-ink{color:var(--color-ink)}.text-ink\\/70{color:#18181bb3}@supports (color:color-mix(in lab, red, red)){.text-ink\\/70{color:color-mix(in oklab, var(--color-ink) 70%, transparent)}}.text-ink\\/80{color:#18181bcc}@supports (color:color-mix(in lab, red, red)){.text-ink\\/80{color:color-mix(in oklab, var(--color-ink) 80%, transparent)}}.text-muted{color:var(--color-muted)}.text-subaccent{color:var(--color-subaccent)}.text-subtle{color:var(--color-subtle)}.uppercase{text-transform:uppercase}.underline{text-decoration-line:underline}.decoration-accent{-webkit-text-decoration-color:var(--color-accent);-webkit-text-decoration-color:var(--color-accent);text-decoration-color:var(--color-accent)}.decoration-accent\\/30{text-decoration-color:#2563eb4d}@supports (color:color-mix(in lab, red, red)){.decoration-accent\\/30{-webkit-text-decoration-color:color-mix(in oklab, var(--color-accent) 30%, transparent);-webkit-text-decoration-color:color-mix(in oklab, var(--color-accent) 30%, transparent);text-decoration-color:color-mix(in oklab, var(--color-accent) 30%, transparent)}}.underline-offset-2{text-underline-offset:2px}.shadow-lg{--tw-shadow:0 10px 15px -3px var(--tw-shadow-color,#0000001a), 0 4px 6px -4px var(--tw-shadow-color,#0000001a);box-shadow:var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)}.filter{filter:var(--tw-blur,) var(--tw-brightness,) var(--tw-contrast,) var(--tw-grayscale,) var(--tw-hue-rotate,) var(--tw-invert,) var(--tw-saturate,) var(--tw-sepia,) var(--tw-drop-shadow,)}.marker\\:text-accent ::marker{color:var(--color-accent)}.marker\\:text-accent::marker{color:var(--color-accent)}.marker\\:text-accent ::-webkit-details-marker{color:var(--color-accent)}.marker\\:text-accent::-webkit-details-marker{color:var(--color-accent)}.last\\:mb-0:last-child{margin-bottom:0}.last\\:border-b-0:last-child{border-bottom-style:var(--tw-border-style);border-bottom-width:0}.last\\:pb-0:last-child{padding-bottom:0}@media (hover:hover){.hover\\:decoration-accent:hover{-webkit-text-decoration-color:var(--color-accent);-webkit-text-decoration-color:var(--color-accent);text-decoration-color:var(--color-accent)}}@media (min-width:40rem){.sm\\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.sm\\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}}@media print{.print\\:m-0{margin:0}.print\\:min-h-0{min-height:0}.print\\:w-auto{width:auto}.print\\:break-before-avoid{break-before:avoid}.print\\:break-inside-avoid{break-inside:avoid}.print\\:break-after-avoid{break-after:avoid}.print\\:grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}.print\\:gap-y-3{row-gap:calc(var(--spacing) * 3)}.print\\:p-0{padding:0}.print\\:decoration-ink\\/40{text-decoration-color:#18181b66}@supports (color:color-mix(in lab, red, red)){.print\\:decoration-ink\\/40{-webkit-text-decoration-color:color-mix(in oklab, var(--color-ink) 40%, transparent);-webkit-text-decoration-color:color-mix(in oklab, var(--color-ink) 40%, transparent);text-decoration-color:color-mix(in oklab, var(--color-ink) 40%, transparent)}}.print\\:shadow-none{--tw-shadow:0 0 #0000;box-shadow:var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow)}}.\\[\\&\\:first-of-type\\]\\:mt-0:first-of-type{margin-top:0}.\\[\\&\\:first-of-type\\]\\:border-t-0:first-of-type{border-top-style:var(--tw-border-style);border-top-width:0}.\\[\\&\\:first-of-type\\]\\:pt-0:first-of-type{padding-top:0}}@property --tw-rotate-x{syntax:\"*\";inherits:false}@property --tw-rotate-y{syntax:\"*\";inherits:false}@property --tw-rotate-z{syntax:\"*\";inherits:false}@property --tw-skew-x{syntax:\"*\";inherits:false}@property --tw-skew-y{syntax:\"*\";inherits:false}@property --tw-space-y-reverse{syntax:\"*\";inherits:false;initial-value:0}@property --tw-border-style{syntax:\"*\";inherits:false;initial-value:solid}@property --tw-leading{syntax:\"*\";inherits:false}@property --tw-font-weight{syntax:\"*\";inherits:false}@property --tw-tracking{syntax:\"*\";inherits:false}@property --tw-shadow{syntax:\"*\";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:\"*\";inherits:false}@property --tw-shadow-alpha{syntax:\"<percentage>\";inherits:false;initial-value:100%}@property --tw-inset-shadow{syntax:\"*\";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:\"*\";inherits:false}@property --tw-inset-shadow-alpha{syntax:\"<percentage>\";inherits:false;initial-value:100%}@property --tw-ring-color{syntax:\"*\";inherits:false}@property --tw-ring-shadow{syntax:\"*\";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:\"*\";inherits:false}@property --tw-inset-ring-shadow{syntax:\"*\";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:\"*\";inherits:false}@property --tw-ring-offset-width{syntax:\"<length>\";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:\"*\";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:\"*\";inherits:false;initial-value:0 0 #0000}@property --tw-blur{syntax:\"*\";inherits:false}@property --tw-brightness{syntax:\"*\";inherits:false}@property --tw-contrast{syntax:\"*\";inherits:false}@property --tw-grayscale{syntax:\"*\";inherits:false}@property --tw-hue-rotate{syntax:\"*\";inherits:false}@property --tw-invert{syntax:\"*\";inherits:false}@property --tw-opacity{syntax:\"*\";inherits:false}@property --tw-saturate{syntax:\"*\";inherits:false}@property --tw-sepia{syntax:\"*\";inherits:false}@property --tw-drop-shadow{syntax:\"*\";inherits:false}@property --tw-drop-shadow-color{syntax:\"*\";inherits:false}@property --tw-drop-shadow-alpha{syntax:\"<percentage>\";inherits:false;initial-value:100%}@property --tw-drop-shadow-size{syntax:\"*\";inherits:false}";
//#endregion
//#region src/index.jsx
function render(resume, options = {}) {
	const locale = options.locale || "en";
	const html = renderToStaticMarkup(/* @__PURE__ */ jsx(Resume, {
		resume,
		locale
	}));
	return `<!DOCTYPE html>
<html lang="${locale}" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>${resume.basics?.name ? `${resume.basics.name} - Resume` : "Resume"}</title>
<style>${tailwind_default}</style>
</head>
<body>${html}</body>
</html>`;
}
//#endregion
export { render };
