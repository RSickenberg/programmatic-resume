<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use DateTimeImmutable;
use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Resume;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class BackendDev extends BaseResume
{
    private const string GITHUB_URL = 'https://github.com/rsickenberg';
    private const WorkTypes RELATED_TYPE = WorkTypes::IT;

    private const bool ADD_ALL_WORK_EXPERIENCES = true;
    private const bool ADD_CAREER_BRAKES = true;

    #[\Override]
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

        usort(
            $experiences,
            static fn(Work $a, Work $b): int => ($b->startDate) <=> ($a->startDate),
        );

        foreach ($experiences as $experience) {
            $builder->addWork($experience);
        }

        return $builder;
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
                        '...',
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
                        '...',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.lower_level_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'Swift 5 (iOS, WatchOS, Combine, Intents, Swift UI, etc.)',
                        '...',
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
                        '...',
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
                        'Serverless',
                        '...',
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
                        'Rest APIs',
                        'Sentry',
                        'Bash',
                        '...',
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

    protected function getRelatedProfiles(): array
    {
        return [
            new Profile(Network::GitHub, 'rsickenberg', new Url(self::GITHUB_URL)),
            new Profile(Network::LinkedIn, 'a320rsck', new Url(self::LINKEDIN_URL)),
        ];
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
