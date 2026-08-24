<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Interest;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\ValueObjects\Url;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseResume implements AbstractResume
{
    public const string FULL_NAME = 'Romain Sickenberg';
    public const string EMAIL = 'r.sickenberg@gmail.com';
    public const string URL = 'https://rsickenberg.me';
    public const string PHONE = '+41 78 907 32 02';
    public const string LINKEDIN_URL = 'https://linkedin.com/in/a320rsck';
    public const string GITHUB_URL = 'https://github.com/rsickenberg';

    public function __construct(
        protected readonly TranslatorInterface $translator,
        protected readonly string $locale = 'en',
    ) {}

    protected function trans(string $id): string
    {
        return $this->translator->trans($id, locale: $this->locale);
    }

    public function __invoke(): Resume
    {
        /** @var ResumeBuilder $resume */
        $resume = new ResumeBuilder()
            ->basics($this->basics())
            |> $this->addLanguages(...)
            |> $this->addWorks(...)
            |> $this->addEducation(...)
            |> $this->addSkills(...)
            |> $this->addAwards(...)
            |> $this->addInterests(...)
            |> $this->addProjects(...);

        return $resume->build();
    }

    public function basics(): Basics
    {
        throw new \RuntimeException(\sprintf('Cannot invoke %s resume on its own.', __FUNCTION__));
    }

    public function addAwards(ResumeBuilder $builder): ResumeBuilder
    {
        return $builder;
    }

    public function addProjects(ResumeBuilder $builder): ResumeBuilder
    {
        return $builder;
    }

    /**
     * Add every work experience, from every sector, most recent first.
     */
    protected function addAllWorkExperiences(ResumeBuilder $builder): ResumeBuilder
    {
        $experiences = [];
        foreach ($this->getAllWorkExperiences() as $sectorExperiences) {
            array_push($experiences, ...$sectorExperiences);
        }

        return $this->addSortedWorks($builder, $experiences);
    }

    /**
     * Sort work experiences by most recent first and add them to the builder.
     * @param list<Work> $experiences
     */
    protected function addSortedWorks(ResumeBuilder $builder, array $experiences): ResumeBuilder
    {
        usort(
            $experiences,
            static fn(Work $a, Work $b): int => ($b->startDate) <=> ($a->startDate),
        );

        foreach ($experiences as $experience) {
            $builder->addWork($experience);
        }

        return $builder;
    }

    public function getLocation(): Location
    {
        return new Location(
            address: 'Chemin de la creuse 6',
            postalCode: '1027',
            city: 'Lonay',
            countryCode: 'CH',
            region: 'VD'
        );
    }

    public function addLanguages(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addLanguage(new Language(
                language: $this->trans('languages.french'),
                fluency: $this->trans('languages.fluency_native'),
            ))
            ->addLanguage(new Language(
                language: $this->trans('languages.english'),
                fluency: $this->trans('languages.fluency_fluent_c2'),
            ))
            ->addLanguage(new Language(
                language: $this->trans('languages.german'),
                fluency: $this->trans('languages.fluency_intermediate_b1'),
            ));

        return $builder;
    }

    public function addInterests(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addInterest(new Interest(
                name: $this->trans('interests.gaming_name'),
                keywords: [
                    $this->trans('interests.gaming_kw_pc_gamer'),
                    $this->trans('interests.gaming_kw_scifi'),
                    $this->trans('interests.gaming_kw_house'),
                ]
            ))
            ->addInterest(new Interest(
                name: $this->trans('interests.travel_name'),
                keywords: [
                    $this->trans('interests.travel_kw_backpacking'),
                    $this->trans('interests.travel_kw_solo'),
                    $this->trans('interests.travel_kw_diving'),
                ]
            ))
            ->addInterest(new Interest(
                name: $this->trans('interests.photography_name'),
                keywords: [
                    $this->trans('interests.photography_kw_drone'),
                    $this->trans('interests.photography_kw_dslr'),
                    $this->trans('interests.photography_kw_astro'),
                ]
            ));

        return $builder;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<\Romainsickenberg\ProgrammaticResume\Generator\WorkTypes, array<Work>
     */
    public function getAllWorkExperiences(): Collection
    {
        return new ArrayCollection([
            WorkTypes::IT->value => [
                new Work(
                    name: 'Academic Work SA',
                    position: $this->trans('work.academic_work.position'),
                    location: 'Lausanne',
                    url: new Url('https://www.academicwork.ch/'),
                    startDate: '2024-03-01',
                    endDate: '2025-12-31',
                    summary: $this->trans('work.academic_work.summary'),
                    highlights: [
                        $this->trans('work.academic_work.highlight_1'),
                        $this->trans('work.academic_work.highlight_2'),
                    ]
                ),
                // Antistatique
                new Work(
                    name: 'Antistatique SA',
                    position: $this->trans('work.antistatique.position'),
                    location: 'Lausanne',
                    url: new Url('https://antistatique.net/'),
                    startDate: '2021-10-31',
                    endDate: '2023-01-31',
                    summary: $this->trans('work.antistatique.summary'),
                    highlights: [
                        $this->trans('work.antistatique.highlight_1'),
                        $this->trans('work.antistatique.highlight_2'),
                        $this->trans('work.antistatique.highlight_3'),
                        $this->trans('work.antistatique.highlight_4'),
                        $this->trans('work.antistatique.highlight_5'),
                        $this->trans('work.antistatique.highlight_6'),
                        $this->trans('work.antistatique.highlight_7'),
                        $this->trans('work.antistatique.highlight_8'),
                        $this->trans('work.antistatique.highlight_9'),
                        $this->trans('work.antistatique.highlight_10'),
                    ],
                ),
                // Consulting @ Berdoz Vision via Ilem
                new Work(
                    name: 'Ilem Group',
                    position: $this->trans('work.ilem.position'),
                    location: $this->trans('location.geneva_ecublens'),
                    url: new Url('https://new.ilemgroup.com/'),
                    startDate: '2021-02-01',
                    endDate: '2021-10-31',
                    summary: $this->trans('work.ilem.summary'),
                    highlights: [
                        $this->trans('work.ilem.highlight_1'),
                        $this->trans('work.ilem.highlight_2'),
                        $this->trans('work.ilem.highlight_3'),
                        $this->trans('work.ilem.highlight_4'),
                    ],
                ),
                // --------- CARRER BREAK : Swiss Military School ---------------
                // Apprenticeship.
                new Work(
                    name: 'Liip AG',
                    position: $this->trans('work.liip.position'),
                    location: 'Lausanne',
                    url: new Url('https://www.liip.ch'),
                    startDate: '2016-06-01',
                    endDate: '2020-06-30',
                    summary: $this->trans('work.liip.summary'),
                    highlights: [
                        $this->trans('work.liip.highlight_1'),
                        $this->trans('work.liip.highlight_2'),
                        $this->trans('work.liip.highlight_3'),
                        $this->trans('work.liip.highlight_4'),
                    ],
                ),
            ],
            WorkTypes::SECURITY->value => [
                new Work(
                    name: 'Securitas AG',
                    position: $this->trans('work.securitas.position'),
                    location: 'Lausanne',
                    url: new Url('https://www.securitas.ch'),
                    startDate: '2023-07-01',
                    endDate: '2024-03-31',
                    summary: $this->trans('work.securitas.summary')
                ),
            ],
            WorkTypes::PILOT->value => [],
            WorkTypes::MISC->value => [],
            WorkTypes::BREAKS->value => [
                // 01.2026 | Studies & Job Search
                new Work(
                    name: $this->trans('work.break_travel.name'),
                    position: $this->trans('work.break_travel.position'),
                    startDate: '2023-02-01',
                    endDate: '2023-06-30',
                    summary: $this->trans('work.break_travel.summary'),
                ),
                new Work(
                    name: $this->trans('work.break_military.name'),
                    position: $this->trans('work.break_military.position'),
                    startDate: '2020-07-01',
                    endDate: '2020-11-30',
                    summary: $this->trans('work.break_military.summary'),
                ),
            ],
        ]);
    }

    public function addEducation(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addEducation(new Education(
                institution: $this->trans('education.institution'),
                url: new Url('https://www.bit.admin.ch/fr/informaticienne-cfc-developpement-applications'),
                area: $this->trans('education.area'),
                startDate: '2016-08-01',
                endDate: '2020-06-31',
                score: '4.4',
                courses: $this->getRelevantCourses(),
            ));

        return $builder;
    }

    /**
     * Curated for relevance to a Backend Software Engineer profile.
     * Excluded modules (kept here for reference, not shown on the resume):
     * '100 - Prepare Data, distinguish and evaluate.',
     * '117 - Establish the IT and network infrastructure of a small business.',
     * '123 - Activate server services.',
     * '253 - Visualize sensor signals.',
     * '301 - Apply office software tools.',
     * '302 - Use advanced Office functions.',
     * '304 - Install and configure a standalone computer.',
     * '305 - Install, configure and administer an operating system.',
     * '403 - Procedurally implement program workflows.',
     * '431 - Execute tasks autonomously in a professional environment.',
     * '129 - Commission network components.',
     * '213 - Develop team spirit.',
     * '214 - Instruct users on the proper use of IT resources.',
     * '226A - Implement object-oriented programming without inheritance.',
     * '226B - Implement object-oriented programming with inheritance.',
     * '242 - Develop applications for microcontrollers.',
     * '306 - Carry out small projects in your own professional environment.',
     * '326 - Develop and implement object-oriented programming.',
     * '335 - Develop a mobile application.',
     * '150 - Adapt an e-commerce application.',
     * '152 - Integrate multimedia content into web applications.',
     * '153 - Develop data models.',
     * '155 - Develop real-time procedures.',
     * '223 - Develop multi-user object-oriented applications.',
     * '254 - Describe business processes in your own professional environment.',
     * @return list<string>
     */
    protected function getRelevantCourses(): array
    {
        return [
            $this->trans('education.courses.build_website'), // 101
            $this->trans('education.courses.data_modeling'), // 104
            $this->trans('education.courses.security_encryption'), // 114
            $this->trans('education.courses.oop_principles'), // 404
            $this->trans('education.courses.gui_development'), // 120
            $this->trans('education.courses.automation_tasks'), // 121
            $this->trans('education.courses.scripting'), // 122
            $this->trans('education.courses.web_client_side'), // 256
            $this->trans('education.courses.interactive_web_pages'), // 307
            $this->trans('education.courses.oop_components'), // 318
            $this->trans('education.courses.data_structures_algorithms'), // 411
            $this->trans('education.courses.agile_methods'), // 426
            $this->trans('education.courses.sql_databases'), // 105
            $this->trans('education.courses.session_handling'), // 133
            $this->trans('education.courses.database_integration'), // 151
            $this->trans('education.courses.deployment'), // 154
            $this->trans('education.courses.app_security'), // 183
        ];
    }

    abstract protected function getSummary(): string;

    /**
     * Return a list of related Resume profiles needed.
     * @return array<\JustSteveKing\Resume\DataObjects\Profile>
     */
    protected function getRelatedProfiles(): array
    {
        return [
            new Profile(Network::GitHub, 'rsickenberg', new Url(self::GITHUB_URL)),
            new Profile(Network::LinkedIn, 'a320rsck', new Url(self::LINKEDIN_URL)),
        ];
    }
}
