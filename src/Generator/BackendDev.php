<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Award;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class BackendDev extends BaseResume
{
    private const string POSITION = 'Backend-Software Engineer';
    private const string GITHUB_URL = 'https://github.com/rsickenberg';
    private const WorkTypes RELATED_TYPE = WorkTypes::IT;

    private const bool ADD_ALL_WORK_EXPERIENCES = true;

    /**
     * @throws \JsonException | \RuntimeException
     */
    #[\Override]
    public function __invoke(): void
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

        $resume = $resume->build();

        if (!$resume->validate()) {
            throw new \RuntimeException('Resume validation failed');
        }

        echo json_encode($resume, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: self::POSITION,
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            url: new Url(self::URL),
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    public function addWorks(ResumeBuilder $builder): void
    {
        $experiences = $this->getAllWorkExperiences();
        foreach ($experiences as $field => $experience) {
            if (! self::ADD_ALL_WORK_EXPERIENCES && $field !== self::RELATED_TYPE->value) {
                continue;
            }

            $builder->addWork($experience);
        }
    }

    public function addSkills(ResumeBuilder $builder): void
    {
        $builder
            ->addSkill(
                new Skill(
                    name: 'Programming Languages',
                    level: SkillLevel::Expert,
                    keywords: [
                        'PHP (Symfony, Laravel, Drupal)',
                        'TypeScript & JavaScript (Next.js, Nuxt.js, Node, NPM & Bun)',
                        'Python',
                        'Swift 6 (iOS, WatchOS, App Intents, Alamofire, Combine, ...)',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: 'Databases',
                    level: SkillLevel::Expert,
                    keywords: [
                        'PostgreSQL',
                        'MySQL',
                        'MariaDB',
                        'Redis',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: 'DevOps',
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Docker',
                        'GitHub Actions',
                        'CI/CD',
                        'Linux',
                        'Apache & Nginx',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: 'Tools',
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Git',
                        'Composer',
                        'Jira, Confluence & Scrum',
                        'Rest APIs',
                        'Sentry',
                    ]
                )
            );
    }

    public function addAwards(ResumeBuilder $builder): void
    {
        $builder
            ->addAward(new Award(
                title: 'EPFL Hackathon AR/VR Game Experiences',
                date: '2019-12-24',
                awarder: 'RA \ VR Hackathon',
                summary: 'Built a "digital museum" in Virtual reality. Won the first place in VR experiences, built with SteamVR and Unity.'
            ));
    }

    protected function getSummary(): string
    {
        return 'Backend-oriented Software Engineer with 5+ years of professional experience building and maintaining business-critical web applications with PHP, Symfony, Laravel, React and Docker.
         Hands-on experience in CI/CD optimizations, code reviews, production deployments and technical leadership of medium-sized projects.';
    }

    protected function getRelatedProfiles(): array
    {
        return [
            new Profile(Network::GitHub, 'rsickenberg', new Url(self::GITHUB_URL)),
            new Profile(Network::LinkedIn, 'rsickenberg', new Url(self::LINKEDIN_URL)),
        ];
    }

    public function addProjects(ResumeBuilder $builder): void
    {
        $builder
            ->addProject(new Project(
                name: 'Tesla Companion',
                startDate: '2018',
                endDate: '2023',
                description: 'Paid iOS & WatchOS App to work around Tesla cars.',
                highlights: [
                    'Built and published a commercial iOS and watchOS application integrating Tesla APIs reaching over 1,000 daily active users and ranking #5 in the App Store Trips category.',
                ],
            ))
            ->addProject(new Project(
                name: 'Fort-To-Nite',
                startDate: '2017',
                endDate: '2019',
                description: 'Free iOS wiki around Fortnite with live in-game shop indications and Django back-end.',
                highlights: [
                    'Fortnite companion app with 100,000+ downloads, 4.6* rating (340+ reviews) and a peak ranking of #19 in the App Store Reference category.',
                ],
            ));
    }
}
