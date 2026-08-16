<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Interest;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\ValueObjects\Url;

abstract class BaseResume implements AbstractResume
{
    public const string FULL_NAME = 'Romain Sickenberg';
    public const string EMAIL = 'r.sickenberg@gmail.com';
    public const string URL = 'https://rsickenberg.me';
    public const string PHONE = '+41 78 907 32 02';
    public const string LINKEDIN_URL = 'https://www.linkedin.com/in/a320rsck/';

    public function __invoke(): void
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

    public function addLanguages(ResumeBuilder $builder): void
    {
        $builder
            ->addLanguage(new Language(
                language: 'French',
                fluency: 'Native',
            ))
            ->addLanguage(new Language(
                language: 'English',
                fluency: 'Fluent (C2)',
            ))
            ->addLanguage(new Language(
                language: 'German',
                fluency: 'Intermediate (B1)',
            ));
    }

    public function addInterests(ResumeBuilder $builder): void
    {
        $builder
            ->addInterest(new Interest(
                name: 'Video Games, Movies, Music',
            ))
            ->addInterest(new Interest(
                name: 'Travel',
            ))
            ->addInterest(new Interest(
                name: 'Photography',
                keywords: ['Drone', 'DSLR', 'Astro-photography']
            ));
    }

    /**
     * @return array<\Romainsickenberg\ProgrammaticResume\Generator\WorkTypes, \JustSteveKing\Resume\DataObjects\Work>
     */
    public function getAllWorkExperiences(): array
    {
        return [
            WorkTypes::IT->value => [
                new Work(
                    name: 'Academic Work SA',
                    position: 'Full-Stack Software Developer',
                    location: 'Lausanne',
                    url: new Url('https://www.academicwork.ch/'),
                    startDate: '2024-03-01',
                    endDate: '2025-12-31',
                    summary: 'IT Consulting for diverse clients via Academic Work.',
                    highlights: [
                        'Developed full-stack web applications using Vue (Nuxt.JS) & Backend in Python (Django) for the SIL in Lausanne.',
                        'Built a React (Next.JS) application with GitHub Actions CI/CD, Docker containerization and automated deployments to Jelastic.',
                    ]
                ),
                // Antistatique
                new Work(
                    name: 'Antistatique SA',
                    position: 'Backend Software Engineer',
                    location: 'Lausanne',
                    url: new Url('https://antistatique.net/'),
                    startDate: '2021-10-31',
                    endDate: '2023-01-31',
                    summary: 'From Junior to mid-level Software Engineer & highest skill jump !',
                    highlights: [
                        'Developed and maintained enterprise Symfony 6 applications, using PHP 7.4/8.0, API Platform, Doctrine ORM, PostgreSQL and GitHub Actions.',
                        'Involved as PHP Lead Developer for a client project in Symfony with 4 other devs.',
                        'Built and maintained CMS-based solutions (Drupal, WordPress).',
                        'Reduced GitHub Actions execution time by 75% through dependency caching and workflow optimization.',
                        'Deployed projects to production using structured deployment processes.',
                        'Led code reviews to improve maintainability and enforce development standards.',
                        'Collaborated within a multidisciplinary development team using Scrum.',
                        'Took technical responsibility for small to medium-sized projects.',
                        'Managed and optimized local development environments using Docker and modern IDE tooling.',
                        'Analyzed project requirements and contributed to technical estimations.',
                    ],
                ),
                // Consulting @ Berdoz Vision via Ilem
                new Work(
                    name: 'Ilem Group',
                    position: 'Junior Backend Software Engineer',
                    location: 'Geneva & Ecublens',
                    url: new Url('https://new.ilemgroup.com/'),
                    startDate: '2021-02-01',
                    endDate: '2021-10-31',
                    summary: 'IT Consulting to an external client named Berdoz Vision & Audition',
                    highlights: [
                        'Secured a custom highly critical ERP, migrated it to a newer PHP version and contributed to the development of its Laravel replacement.',
                        'Configured a fleet of 44 iPads and set up CI servers to run complex test suites with Codeception and Cypress.',
                        'Improved security and developed critical components of existing in-house CRM solution for multiple shops around Switzerland.',
                        'Built internal tools for automated testing, monitoring, and deployment, while ensuring high-quality standards with Sentry.',
                    ],
                ),
                // --------- CARRER BREAK : Swiss Military School ---------------
                // Apprenticeship.
                new Work(
                    name: 'Liip AG',
                    position: 'Apprentice Software Engineer',
                    location: 'Lausanne',
                    url: new Url('https://www.liip.ch'),
                    startDate: '2016-06-01',
                    endDate: '2020-06-31',
                    summary: 'Started my IT career while studying 2 days per week at school.',
                    highlights: [
                        'Developed and enhanced internal web applications using PHP and JavaScript.',
                        'Collaborated within an Agile development team using Git and modern web development practices.',
                        'Developed custom Moodle plugins with PHP 5.4.',
                        'Used Scrum in an Holacratic environment.',
                    ],
                ),
            ],
            WorkTypes::SECURITY->value => [
                new Work(
                    name: 'Securitas AG',
                    position: 'Security Agent',
                    location: 'Lausanne',
                    url: new Url('https://www.securitas.ch'),
                    startDate: '2023-07-01',
                    endDate: '2024-03-31',
                    summary: 'Part-time security agent for Securitas in the event sector.'
                ),
            ],
            WorkTypes::PILOT->value => [],
            WorkTypes::MISC->value => [],
            WorkTypes::BREAKS->value => [
                // 01.2026 | Studies & Job Search
                // 01.2023 - 07.2023 | SOLO WORLD TRAVEL AND PERSONAL DEVELOPMENT
                // 06.2020 - 11.2020 | SWISS ARMED FORCES
            ],
        ];
    }

    public function addEducation(ResumeBuilder $builder): void
    {
        $builder
            ->addEducation(new Education(
                institution: 'Swiss Confederation | EPSIC @ Lausanne & CFPT @ Geneva',
                url: new Url('https://www.bit.admin.ch/fr/informaticienne-cfc-developpement-applications'),
                area: 'Federal VET Diploma in Information Technology (CFC)',
                studyType: EducationLevel::Other,
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
    }

    abstract protected function getSummary(): string;

    /**
     * Return a list of related Resume profiles needed.
     * @return array<\JustSteveKing\Resume\DataObjects\Profile>
     */
    abstract protected function getRelatedProfiles(): array;
}
