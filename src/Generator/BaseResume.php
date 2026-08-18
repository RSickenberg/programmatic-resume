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
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\ValueObjects\Url;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseResume implements AbstractResume
{
    public const string FULL_NAME = 'Romain Sickenberg';
    public const string EMAIL = 'r.sickenberg@gmail.com';
    public const string URL = 'https://rsickenberg.me';
    public const string PHONE = '+41 78 907 32 02';
    public const string LINKEDIN_URL = 'https://linkedin.com/in/a320rsck';

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
        throw new \RuntimeException(\sprintf('Cannot invoke %s resume on its own.', __FUNCTION__));
    }

    public function basics(): Basics
    {
        throw new \RuntimeException(\sprintf('Cannot invoke %s resume on its own.', __FUNCTION__));
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
                studyType: EducationLevel::HighSchool,
                startDate: '2016-08-01',
                endDate: '2020-06-31',
                score: '4.4',
                courses: [
                    '100 - Prepare Data, distinguish and evaluate.',
                    '101 - Do and publish a web site.',
                    '104 - Data model implementations.',
                    '114 - Implement coding, compression and encryption systems.',
                    '117 - Establish the IT and network infrastructure of a small business.',
                    '123 - Activate server services.',
                    '253 - Visualize sensor signals.',
                    '301 - Apply office software tools.',
                    '302 - Use advanced Office functions.',
                    '304 - Install and configure a standalone computer.',
                    '305 - Install, configure and administer an operating system.',
                    '403 - Procedurally implement program workflows.',
                    '404 - Program according to object-oriented principles.',
                    '431 - Execute tasks autonomously in a professional environment.',
                    '120 - Implement graphical interfaces for applications.',
                    '121 - Develop automation tasks.',
                    '122 - Automate procedures using scripts.',
                    '129 - Commission network components.',
                    '213 - Develop team spirit.',
                    '214 - Instruct users on the proper use of IT resources.',
                    '226A - Implement object-oriented programming without inheritance.',
                    '226B - Implement object-oriented programming with inheritance.',
                    '242 - Develop applications for microcontrollers.',
                    '256 - Develop the client side of web applications.',
                    '307 - Develop interactive web pages.',
                    '318 - Analyze and program object-oriented components.',
                    '411 - Develop and apply data structures and algorithms.',
                    '426 - Develop software using agile methods.',
                    '105 - Work with a database using SQL.',
                    '133 - Develop web applications using session handling.',
                    '151 - Integrate databases into web applications.',
                    '306 - Carry out small projects in your own professional environment.',
                    '326 - Develop and implement object-oriented programming.',
                    '335 - Develop a mobile application.',
                    '150 - Adapt an e-commerce application.',
                    '152 - Integrate multimedia content into web applications.',
                    '153 - Develop data models.',
                    '154 - Organize the deployment of applications.',
                    '155 - Develop real-time procedures.',
                    '183 - Implement application security.',
                    '223 - Develop multi-user object-oriented applications.',
                    '254 - Describe business processes in your own professional environment.',
                ],
            ));

        return $builder;
    }

    abstract protected function getSummary(): string;

    /**
     * Return a list of related Resume profiles needed.
     * @return array<\JustSteveKing\Resume\DataObjects\Profile>
     */
    abstract protected function getRelatedProfiles(): array;
}
