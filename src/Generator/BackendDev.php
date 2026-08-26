<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use DateTimeImmutable;
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class BackendDev extends BaseResume
{
    private const WorkTypes RELATED_TYPE = WorkTypes::IT;

    private const bool ADD_ALL_WORK_EXPERIENCES = true;
    private const bool ADD_CAREER_BRAKES = true;

    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.backend_dev_position'),
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            url: new Url(self::URL),
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    public function addWorks(ResumeBuilder $builder): ResumeBuilder
    {
        $experiencesBySector = $this->getAllWorkExperiences();

        /** @var \JustSteveKing\Resume\DataObjects\Work[] $experiences */
        $experiences = [];
        foreach ($experiencesBySector as $field => $sectorExperiences) {
            if (!self::ADD_ALL_WORK_EXPERIENCES && $field !== self::RELATED_TYPE->value) {
                continue;
            }
            if (!self::ADD_CAREER_BRAKES && $field === WorkTypes::BREAKS->value) {
                continue;
            }

            array_push($experiences, ...$sectorExperiences);
        }

        return $this->addSortedWorks($builder, $experiences);
    }

    public function addSkills(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.backend_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'PHP',
                        'Python',
                        'Node.js',
                        'C#',
                        'Java',
                        'REST APIs',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.databases_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'PostgreSQL',
                        'MySQL',
                        'MariaDB',
                        'Prisma / Supabase',
                        'Redis',
                        'Elasticsearch',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.tools_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Git',
                        'Composer',
                        'Jira, Confluence & Scrum',
                        'Sentry',
                        'Bash',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.cloud_devops_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Docker',
                        'GitHub Actions',
                        'CI/CD',
                        'Linux',
                        'Apache & Nginx',
                        'Google Cloud Platform (GCP)',
                        'AWS',
                        'Firebase',
                        'Serverless',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.frontend_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'TypeScript & JavaScript',
                        'React, Next.js, Nuxt.js, NPM & Bun',
                        'HTML5 & CSS, Sass',
                        'Tailwind CSS',
                        'Alpine.js',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.lower_level_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'Swift 5 (iOS, WatchOS, Combine, Intents, Swift UI, etc.)',
                    ]
                )
            );

        return $builder;
    }

    public function addAwards(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addAward(new Award(
                title: 'EPFL Hackathon AR/VR Game Experiences',
                date: '2019-12-24',
                awarder: 'RA \ VR Hackathon',
                summary: $this->trans('awards.epfl_summary')
            ));

        return $builder;
    }

    protected function getSummary(): string
    {
        return implode("\n\n", [
            $this->trans('basics.summary_role'),
            $this->trans('basics.summary_experience'),
            $this->trans('basics.summary_authorization'),
        ]);
    }

    /**
     * No coursework on the backend CV. Apprenticeship modules from 2020 carry
     * little weight next to five years of shipped work, and the block costs
     * ~48pt, the last gap between a two-page and a three-page CV. Return the
     * commented list below to bring it back.
     *
     * @return list<string>
     */
    #[\Override]
    protected function getRelevantCourses(): array
    {
        return [
            // $this->trans('education.courses.data_modeling'), // 104
            // $this->trans('education.courses.oop_principles'), // 404
            // $this->trans('education.courses.data_structures_algorithms'), // 411
            // $this->trans('education.courses.sql_databases'), // 105
            // $this->trans('education.courses.database_integration'), // 151
            // $this->trans('education.courses.app_security'), // 183
        ];
    }

    /**
     * Interests carry no ATS signal for a backend role and cost roughly 30mm,
     * the difference between a two- and a three-page CV. Delete this override
     * to bring them back.
     */
    #[\Override]
    public function addInterests(ResumeBuilder $builder): ResumeBuilder
    {
        return $builder;
    }

    public function addProjects(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addProject(new Project(
                name: 'Tesla Companion',
                startDate: '2018-01-01',
                endDate: '2023-01-01',
                description: $this->trans('projects.tesla.description'),
                highlights: [
                    $this->trans('projects.tesla.highlight_1'),
                ],
            ))
            ->addProject(new Project(
                name: 'Fort-To-Nite',
                startDate: '2017-01-01',
                endDate: '2019-01-01',
                description: $this->trans('projects.fort_to_nite.description'),
                highlights: [
                    $this->trans('projects.fort_to_nite.highlight_1'),
                ],
            ));

        return $builder;
    }
}
